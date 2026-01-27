<?php
/**
 * Database Configuration
 * Configure your MES database connection here
 *
 * IMPORTANT: Keep this file secure and never commit credentials to version control
 */

// Database connection settings
define('DB_HOST', 'localhost');           // Your database server (e.g., 'localhost', '192.168.1.100')
define('DB_PORT', '3306');                // Database port (MySQL default: 3306, PostgreSQL: 5432)
define('DB_NAME', 'alpc');        // Database name
define('DB_USER', 'root');                // Database username
define('DB_PASS', '');                    // Database password
define('DB_CHARSET', 'utf8mb4');          // Character set

// Database type (mysql, postgresql, sqlserver)
define('DB_TYPE', 'mysql');

// Connection timeout (seconds)
define('DB_TIMEOUT', 5);

// Enable/disable query logging for debugging
define('DB_DEBUG', true);

/**
 * Create PDO connection
 *
 * @return PDO
 * @throws Exception
 */
function getDatabaseConnection() {
    try {
        $dsn = '';

        switch (DB_TYPE) {
            case 'mysql':
                $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                break;
            case 'postgresql':
                $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
                break;
            case 'sqlserver':
                $dsn = "sqlsrv:Server=" . DB_HOST . "," . DB_PORT . ";Database=" . DB_NAME;
                break;
            default:
                throw new Exception("Unsupported database type: " . DB_TYPE);
        }

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_TIMEOUT            => DB_TIMEOUT
        ];

        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

        if (DB_DEBUG) {
            error_log("Database connection established successfully");
        }

        return $pdo;

    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        throw new Exception("Database connection failed: " . $e->getMessage());
    }
}

/**
 * Test database connection
 *
 * @return array Status information
 */
function testDatabaseConnection() {
    try {
        $pdo = getDatabaseConnection();
        $stmt = $pdo->query("SELECT 1");

        return [
            'success' => true,
            'message' => 'Database connection successful',
            'server_info' => $pdo->getAttribute(PDO::ATTR_SERVER_VERSION)
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}
