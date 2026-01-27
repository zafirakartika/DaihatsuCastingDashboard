<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
$host = '127.0.0.1';
$dbname = 'alpc';
$username = 'root';
$password = '';

try {
    // Create database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Get parameters
    $line = $_GET['line'] ?? 'all';

    // SAMPLE DATA - Replace with actual database query when table is ready
    // Expected table structure: trials_dandori
    // Columns: id, timestamp, machine_line, product_order, setup_duration, downtime_duration,
    //          trial_units, defect_rate, defect_type, qa_status, oee_availability,
    //          oee_performance, oee_quality, notes

    // For now, return sample/simulated data
    $sampleData = generateSampleData($line);

    echo json_encode([
        'status' => 'success',
        'data' => $sampleData,
        'count' => count($sampleData),
        'note' => 'Sample data - Replace with actual database query when trials_dandori table is created'
    ], JSON_PRETTY_PRINT);

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

/**
 * Generate sample data for testing
 * Replace this with actual database query when ready
 */
function generateSampleData($line) {
    $lines = ['WA', 'TR', 'KR', 'NR', '3SZ'];
    $productOrders = ['Order-2024-001', 'Order-2024-002', 'Order-2024-003', 'Order-2024-004'];
    $defectTypes = ['Goresan', 'Dimensi Tidak Sesuai', 'Finishing', 'Berpori', 'Retak', 'Lainnya'];
    $qaStatuses = ['Approved', 'Pending', 'Rejected'];

    $data = [];
    $count = 50; // Generate 50 sample records

    for ($i = 0; $i < $count; $i++) {
        $selectedLine = $line === 'all' ? $lines[array_rand($lines)] : $line;

        // Generate timestamp (last 30 days)
        $timestamp = date('Y-m-d H:i:s', strtotime("-" . rand(0, 30) . " days -" . rand(0, 23) . " hours"));

        $record = [
            'id' => $i + 1,
            'timestamp' => $timestamp,
            'machine_line' => $selectedLine,
            'product_order' => $productOrders[array_rand($productOrders)],
            'setup_duration' => rand(30, 90), // minutes
            'downtime_duration' => rand(5, 45), // minutes
            'trial_units' => rand(5, 30),
            'defect_rate' => round(rand(0, 20) + (rand(0, 100) / 100), 2), // 0-20%
            'defect_type' => $defectTypes[array_rand($defectTypes)],
            'qa_status' => $qaStatuses[array_rand($qaStatuses)],
            'oee_availability' => rand(75, 95),
            'oee_performance' => rand(80, 98),
            'oee_quality' => rand(85, 99),
            'oee' => 0, // Will be calculated
            'notes' => 'Sample trial record #' . ($i + 1)
        ];

        // Calculate OEE
        $record['oee'] = round(
            ($record['oee_availability'] * $record['oee_performance'] * $record['oee_quality']) / 10000,
            1
        );

        $data[] = $record;
    }

    // Sort by timestamp (newest first)
    usort($data, function($a, $b) {
        return strtotime($b['timestamp']) - strtotime($a['timestamp']);
    });

    return $data;
}

/**
 * Database Query Example (Use this when table is ready)
 *
 * $sql = "SELECT
 *             id, timestamp, machine_line, product_order,
 *             setup_duration, downtime_duration, trial_units,
 *             defect_rate, defect_type, qa_status,
 *             oee_availability, oee_performance, oee_quality,
 *             notes
 *         FROM trials_dandori";
 *
 * if ($line !== 'all') {
 *     $sql .= " WHERE machine_line = :line";
 * }
 *
 * $sql .= " ORDER BY timestamp DESC LIMIT 100";
 *
 * $stmt = $pdo->prepare($sql);
 * if ($line !== 'all') {
 *     $stmt->bindValue(':line', $line, PDO::PARAM_STR);
 * }
 * $stmt->execute();
 * $data = $stmt->fetchAll();
 */
