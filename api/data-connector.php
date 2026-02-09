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
 * Fetch counter data from specified counter tables for ID = 1
 *
 * @return array
 */
function fetchCounterData() {
    $pdo = getDatabaseConnection();
    $result = [];
    $debugErrors = [];

    // Define the tables and the columns we want to fetch
    $tables = [
        'tr_counter'    => ['LPC1', 'LPC2', 'LPC3', 'LPC4', 'LPC6'],
        'sz_kr_counter' => ['LPC9'],
        'nr_counter'    => ['LPC12', 'LPC13', 'LPC14'],
        'wa_counter'    => ['LPC11']
    ];

    foreach ($tables as $tableName => $columns) {
        try {
            // Force column alias to ensure case sensitivity matches JS (LPC1 as LPC1)
            $selectParts = [];
            foreach ($columns as $col) {
                $selectParts[] = "$col AS $col";
            }
            $columnList = implode(', ', $selectParts);

            // Fetch row where id = 1
            $stmt = $pdo->prepare("SELECT $columnList FROM $tableName WHERE id = 1 LIMIT 1");
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            // If data exists, use it; otherwise, fill with 0s
            if ($data) {
                $result[$tableName] = $data;
            } else {
                $result[$tableName] = array_fill_keys($columns, 0);
                $debugErrors[$tableName] = "Row with id=1 not found";
            }
        } catch (Exception $e) {
            // If ONE table fails, only that table gets 0s. The others continue.
            $result[$tableName] = array_fill_keys($columns, 0);
            $debugErrors[$tableName] = $e->getMessage();
        }
    }

    // Attach debug info to the result for troubleshooting
    $result['_debug'] = $debugErrors;

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
?>