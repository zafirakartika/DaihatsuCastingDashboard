<?php
/**
 * Real-Time Data Connector
 * Generic API handler that uses configuration to fetch data from MES database
 *
 * This script automatically maps database columns to dashboard format
 * No code changes needed when database structure changes - just update config!
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Include configuration files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/data-mapping.php';

/**
 * Fetch casting performance data
 *
 * @param string $part Part type (WA, TR, KR, NR, 3SZ)
 * @param int $limit Number of records to fetch
 * @param string $since Fetch records since this timestamp
 * @return array
 */
function fetchCastingData($part, $limit = null, $since = null) {
    global $CASTING_DATA_CONFIG;

    if (!isset($CASTING_DATA_CONFIG[$part])) {
        throw new Exception("Part type '$part' not configured");
    }

    $config = $CASTING_DATA_CONFIG[$part];
    $pdo = getDatabaseConnection();

    // Build column selection with mapping
    $selectColumns = [];
    foreach ($config['columns'] as $dashboardField => $dbColumn) {
        $selectColumns[] = "`$dbColumn` AS `$dashboardField`";
    }

    $sql = "SELECT " . implode(', ', $selectColumns) . " FROM `{$config['table']}`";

    // Add WHERE clause for incremental updates
    $params = [];
    if ($since && isset($config['columns']['timestamp'])) {
        $sql .= " WHERE `{$config['columns']['timestamp']}` > :since";
        $params[':since'] = $since;
    }

    // Add ORDER BY
    $sql .= " ORDER BY {$config['order_by']}";

    // Add LIMIT
    $fetchLimit = $limit ?? $config['limit'];
    $sql .= " LIMIT :limit";
    $params[':limit'] = $fetchLimit;

    $stmt = $pdo->prepare($sql);

    // Bind parameters
    foreach ($params as $key => $value) {
        if ($key === ':limit') {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value);
        }
    }

    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Fetch finishing performance data
 *
 * @param string $part Part type (WA, TR)
 * @param int $limit Number of records
 * @return array
 */
function fetchFinishingData($part, $limit = null) {
    global $FINISHING_DATA_CONFIG;

    if (!isset($FINISHING_DATA_CONFIG[$part])) {
        throw new Exception("Finishing data for part '$part' not configured");
    }

    $config = $FINISHING_DATA_CONFIG[$part];
    $pdo = getDatabaseConnection();

    $selectColumns = [];
    foreach ($config['columns'] as $dashboardField => $dbColumn) {
        $selectColumns[] = "`$dbColumn` AS `$dashboardField`";
    }

    $sql = "SELECT " . implode(', ', $selectColumns) . " FROM `{$config['table']}`";
    $sql .= " ORDER BY {$config['order_by']}";
    $sql .= " LIMIT " . ($limit ?? $config['limit']);

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

/**
 * Fetch general ALPC production summary
 *
 * @param string $part Part type (WA, TR)
 * @param string $dateFrom Start date
 * @param string $dateTo End date
 * @return array
 */
function fetchGeneralALPCData($part, $dateFrom = null, $dateTo = null) {
    global $GENERAL_ALPC_CONFIG;

    if (!isset($GENERAL_ALPC_CONFIG[$part])) {
        throw new Exception("General ALPC data for part '$part' not configured");
    }

    $config = $GENERAL_ALPC_CONFIG[$part];
    $pdo = getDatabaseConnection();

    $selectColumns = [];
    foreach ($config['columns'] as $dashboardField => $dbColumn) {
        $selectColumns[] = "`$dbColumn` AS `$dashboardField`";
    }

    $sql = "SELECT " . implode(', ', $selectColumns) . " FROM `{$config['production_table']}`";

    $params = [];
    $whereClauses = [];

    if ($dateFrom) {
        $whereClauses[] = "`{$config['columns']['timestamp']}` >= :date_from";
        $params[':date_from'] = $dateFrom;
    }

    if ($dateTo) {
        $whereClauses[] = "`{$config['columns']['timestamp']}` <= :date_to";
        $params[':date_to'] = $dateTo;
    }

    if (!empty($whereClauses)) {
        $sql .= " WHERE " . implode(' AND ', $whereClauses);
    }

    $sql .= " ORDER BY `{$config['columns']['timestamp']}` DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Fetch counter data from counter tables
 *
 * @return array
 */
function fetchCounterData() {
    $pdo = getDatabaseConnection();
    $result = [];

    try {
        // Fetch TR counter data (LPC 1, 2, 3, 4, 6) - get latest record
        $stmt = $pdo->query("SELECT LPC1, LPC2, LPC3, LPC4, LPC6 FROM tr_counter ORDER BY time DESC LIMIT 1");
        $trData = $stmt->fetch(PDO::FETCH_ASSOC);

        $result['TR'] = [
            'LPC1' => $trData['LPC1'] ?? 0,
            'LPC2' => $trData['LPC2'] ?? 0,
            'LPC3' => $trData['LPC3'] ?? 0,
            'LPC4' => $trData['LPC4'] ?? 0,
            'LPC6' => $trData['LPC6'] ?? 0
        ];

        // Fetch KR/SZ counter data (LPC 9) - get latest record
        $stmt = $pdo->query("SELECT LPC9 FROM sz_kr_counter ORDER BY time DESC LIMIT 1");
        $krSzData = $stmt->fetch(PDO::FETCH_ASSOC);

        $result['3SZ-KR'] = [
            'LPC9' => $krSzData['LPC9'] ?? 0
        ];

        // Fetch NR counter data (LPC 12, 13, 14) - get latest record
        $stmt = $pdo->query("SELECT LPC12, LPC13, LPC14 FROM nr_counter ORDER BY time DESC LIMIT 1");
        $nrData = $stmt->fetch(PDO::FETCH_ASSOC);

        $result['NR'] = [
            'LPC12' => $nrData['LPC12'] ?? 0,
            'LPC13' => $nrData['LPC13'] ?? 0,
            'LPC14' => $nrData['LPC14'] ?? 0
        ];

        // Fetch WA counter data (LPC 11) - get latest record
        $stmt = $pdo->query("SELECT LPC11 FROM wa_counter ORDER BY time DESC LIMIT 1");
        $waData = $stmt->fetch(PDO::FETCH_ASSOC);

        $result['WA'] = [
            'LPC11' => $waData['LPC11'] ?? 0
        ];

    } catch (Exception $e) {
        // If database query fails, return zeros
        $result = [
            'TR' => [
                'LPC1' => 0,
                'LPC2' => 0,
                'LPC3' => 0,
                'LPC4' => 0,
                'LPC6' => 0
            ],
            '3SZ-KR' => [
                'LPC9' => 0
            ],
            'NR' => [
                'LPC12' => 0,
                'LPC13' => 0,
                'LPC14' => 0
            ],
            'WA' => [
                'LPC11' => 0
            ]
        ];
    }

    return $result;
}

/**
 * Search traceability records
 *
 * @param string $searchTerm Search keyword
 * @param string $partType Filter by part type
 * @return array
 */
function searchTraceability($searchTerm = '', $partType = '') {
    global $TRACEABILITY_CONFIG;

    $pdo = getDatabaseConnection();

    $selectColumns = [];
    foreach ($TRACEABILITY_CONFIG['columns'] as $dashboardField => $dbColumn) {
        $selectColumns[] = "`$dbColumn` AS `$dashboardField`";
    }

    $sql = "SELECT " . implode(', ', $selectColumns) . " FROM `{$TRACEABILITY_CONFIG['table']}`";

    $params = [];
    $whereClauses = [];

    // Search across multiple fields
    if (!empty($searchTerm)) {
        $searchClauses = [];
        foreach ($TRACEABILITY_CONFIG['search_fields'] as $field) {
            $searchClauses[] = "`$field` LIKE :search";
        }
        $whereClauses[] = '(' . implode(' OR ', $searchClauses) . ')';
        $params[':search'] = "%$searchTerm%";
    }

    // Filter by part type
    if (!empty($partType)) {
        $whereClauses[] = "`{$TRACEABILITY_CONFIG['columns']['part_type']}` = :part_type";
        $params[':part_type'] = $partType;
    }

    if (!empty($whereClauses)) {
        $sql .= " WHERE " . implode(' AND ', $whereClauses);
    }

    $sql .= " ORDER BY `{$TRACEABILITY_CONFIG['columns']['production_date']}` DESC LIMIT 100";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// ============================================
// API ENDPOINT ROUTING
// ============================================

try {
    $endpoint = $_GET['endpoint'] ?? '';
    $part = $_GET['part'] ?? '';

    switch ($endpoint) {
        case 'casting':
            $limit = $_GET['limit'] ?? null;
            $since = $_GET['since'] ?? null;
            $data = fetchCastingData($part, $limit, $since);
            echo json_encode(['success' => true, 'data' => $data]);
            break;

        case 'finishing':
            $limit = $_GET['limit'] ?? null;
            $data = fetchFinishingData($part, $limit);
            echo json_encode(['success' => true, 'data' => $data]);
            break;

        case 'general-alpc':
            $dateFrom = $_GET['date_from'] ?? null;
            $dateTo = $_GET['date_to'] ?? null;
            $data = fetchGeneralALPCData($part, $dateFrom, $dateTo);
            echo json_encode(['success' => true, 'data' => $data]);
            break;

        case 'traceability':
            $searchTerm = $_GET['search'] ?? '';
            $partType = $_GET['part_type'] ?? '';
            $data = searchTraceability($searchTerm, $partType);
            echo json_encode(['success' => true, 'data' => $data]);
            break;

        case 'counters':
            $data = fetchCounterData();
            echo json_encode(['success' => true, 'data' => $data]);
            break;

        case 'test-connection':
            $result = testDatabaseConnection();
            echo json_encode($result);
            break;

        default:
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid endpoint. Available: casting, finishing, general-alpc, traceability, counters, test-connection'
            ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
