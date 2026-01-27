<?php
/**
 * Generic Traceability Data API
 * Consolidated API for all traceability data (WA, TR, KR, NR, 3SZ)
 * Eliminates code duplication and centralizes database access
 * Version: 1.1.0
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Load configuration from the main config file
require_once __DIR__ . '/../../config/database.php';

// Default settings if not configured in database.php
$TRACEABILITY_CONFIG = $TRACEABILITY_CONFIG ?? [
    'WA' => [
        'table' => 'wa_loger_cyh_wa',
        'columns' => ['no_shot', 'id_part', 'type', 'mc', 'create_timestamp']
    ],
    'TR' => [
        'table' => 'tr_loger_cyh_tr',
        'columns' => ['no_shot', 'id_part', 'timestamp']
    ],
    'KR' => [
        'table' => 'kr_loger_cyh_kr',
        'columns' => ['no_shot', 'id_part', 'timestamp']
    ],
    'NR' => [
        'table' => 'nr_loger_cyh_nr',
        'columns' => ['no_shot', 'id_part', 'timestamp']
    ],
    '3SZ' => [
        'table' => 'sz_loger_cyh_3sz',
        'columns' => ['no_shot', 'id_part', 'timestamp']
    ]
];

try {
    // Create database connection using config file
    $host = DB_HOST ?? '127.0.0.1';
    $dbname = DB_NAME ?? 'alpc';
    $username = DB_USER ?? 'root';
    $password = DB_PASS ?? '';

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Get parameters
    $action = $_GET['action'] ?? 'recent';
    $part = $_GET['part'] ?? 'WA';
    $limitParam = $_GET['limit'] ?? '100';
    $date = $_GET['date'] ?? null;

    // Validate part type
    if (!isset($TRACEABILITY_CONFIG[$part])) {
        throw new Exception("Invalid part type: $part");
    }

    $config = $TRACEABILITY_CONFIG[$part];
    $table = $config['table'];
    $columns = $config['columns'];

    // Build query based on action
    if ($action === 'recent') {
        $columnList = implode(', ', $columns);
        $sql = "SELECT $columnList
                FROM $table
                WHERE id_part IS NOT NULL
                AND id_part != ''
                ORDER BY no_shot DESC";

        // Add LIMIT clause only if not "all"
        if ($limitParam !== 'all') {
            $sql .= " LIMIT :limit";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':limit', (int)$limitParam, PDO::PARAM_INT);
        } else {
            $stmt = $pdo->prepare($sql);
        }

        $stmt->execute();
        $data = $stmt->fetchAll();

        echo json_encode([
            'status' => 'success',
            'data' => $data,
            'count' => count($data)
        ], JSON_PRETTY_PRINT);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid action'
        ], JSON_PRETTY_PRINT);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Server error: ' . $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
