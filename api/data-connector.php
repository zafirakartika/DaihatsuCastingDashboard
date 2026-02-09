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
 */
function fetchCastingData($part, $limit = null, $since = null) {
    global $CASTING_DATA_CONFIG;

    if (!isset($CASTING_DATA_CONFIG[$part])) {
        throw new Exception("Part type '$part' not configured");
    }

    $config = $CASTING_DATA_CONFIG[$part];
    $pdo = getDatabaseConnection();

    $selectColumns = [];
    foreach ($config['columns'] as $dashboardField => $dbColumn) {
        $selectColumns[] = "`$dbColumn` AS `$dashboardField`";
    }

    $sql = "SELECT " . implode(', ', $selectColumns) . " FROM `{$config['table']}`";

    $params = [];
    if ($since && isset($config['columns']['timestamp'])) {
        $sql .= " WHERE `{$config['columns']['timestamp']}` > :since";
        $params[':since'] = $since;
    }

    $sql .= " ORDER BY {$config['order_by']}";

    $fetchLimit = $limit ?? $config['limit'];
    $sql .= " LIMIT :limit";
    $params[':limit'] = $fetchLimit;

    $stmt = $pdo->prepare($sql);

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
 * Fetch counter data from specified counter tables
 * * IMPROVED: Uses SELECT * and PHP key normalization to handle 
 * case-sensitivity (lpc1 vs LPC1) and missing IDs robustly.
 */
function fetchCounterData() {
    $pdo = getDatabaseConnection();
    $result = [];
    $debugErrors = [];

    // Define the tables we want to fetch
    $tables = ['tr_counter', 'sz_kr_counter', 'nr_counter', 'wa_counter'];

    foreach ($tables as $tableName) {
        try {
            // 1. Try to fetch the specific row (id=1)
            $stmt = $pdo->prepare("SELECT * FROM $tableName WHERE id = 1 LIMIT 1");
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            // 2. Fallback: If id=1 not found, fetch the LATEST row (handling log-style tables)
            if (!$data) {
                // Try ordering by 'time' if it exists, or 'id' as a fallback
                try {
                    $stmt = $pdo->prepare("SELECT * FROM $tableName ORDER BY time DESC LIMIT 1");
                    $stmt->execute();
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                } catch (Exception $ex) {
                    // If 'time' column doesn't exist, try ID
                    $stmt = $pdo->prepare("SELECT * FROM $tableName ORDER BY id DESC LIMIT 1");
                    $stmt->execute();
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }

            if ($data) {
                // IMPORTANT: Normalize keys to UPPERCASE (lpc1 -> LPC1)
                // This fixes issues where DB columns are lowercase but JS expects uppercase
                $result[$tableName] = array_change_key_case($data, CASE_UPPER);
            } else {
                $result[$tableName] = []; // Empty object if no data found
                $debugErrors[$tableName] = "No data found (checked id=1 and latest)";
            }

        } catch (Exception $e) {
            $result[$tableName] = [];
            $debugErrors[$tableName] = $e->getMessage();
        }
    }

    // Attach debug info
    $result['_debug'] = $debugErrors;

    return $result;
}

/**
 * Search traceability records
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

    if (!empty($searchTerm)) {
        $searchClauses = [];
        foreach ($TRACEABILITY_CONFIG['search_fields'] as $field) {
            $searchClauses[] = "`$field` LIKE :search";
        }
        $whereClauses[] = '(' . implode(' OR ', $searchClauses) . ')';
        $params[':search'] = "%$searchTerm%";
    }

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
                'error' => 'Invalid endpoint'
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