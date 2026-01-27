<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    echo "=== Testing Database Connection ===\n\n";
    
    // Test 1: Direct PDO connection
    echo "1. Testing direct MySQL connection...\n";
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    echo "   ✓ Connected to MySQL server\n\n";
    
    // Test 2: Select database
    echo "2. Testing 'alpc' database...\n";
    $pdo->exec("USE alpc");
    echo "   ✓ Selected 'alpc' database\n\n";
    
    // Test 3: Check table exists
    echo "3. Checking 'wa_loger_lpdc20191029' table...\n";
    $result = $pdo->query("SHOW TABLES LIKE 'wa_loger_lpdc20191029'");
    $table = $result->fetch();
    if ($table) {
        echo "   ✓ Table found\n\n";
    } else {
        echo "   ✗ Table NOT found\n\n";
    }
    
    // Test 4: Count rows
    echo "4. Counting rows in table...\n";
    $result = $pdo->query("SELECT COUNT(*) as cnt FROM wa_loger_lpdc20191029");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    echo "   ✓ Total rows: " . $row['cnt'] . "\n\n";
    
    // Test 5: Get latest record
    echo "5. Fetching latest record...\n";
    $result = $pdo->query("SELECT * FROM wa_loger_lpdc20191029 ORDER BY datetime_stamp DESC LIMIT 1");
    $record = $result->fetch(PDO::FETCH_ASSOC);
    if ($record) {
        echo "   ✓ Latest record:\n";
        foreach ($record as $key => $value) {
            echo "     - $key: $value\n";
        }
    } else {
        echo "   ✗ No records found\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n";
}
?>
