<?php
/**
 * Test Database Connection
 */

require_once __DIR__ . '/vendor/autoload.php';

use CTFd\Database;

// Load environment variables
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

echo "Testing database connection...\n\n";

try {
    $db = Database::getInstance();
    $connection = $db->getConnection();
    
    echo "✓ Database connection successful!\n";
    
    // Test query
    $result = $db->fetchOne("SELECT 1 as test");
    if ($result) {
        echo "✓ Database query successful!\n";
    }
    
    // Check tables
    $tables = $db->fetchAll("SHOW TABLES");
    echo "\n✓ Found " . count($tables) . " table(s) in database\n";
    
    if (count($tables) > 0) {
        echo "\nTables:\n";
        foreach ($tables as $table) {
            $tableName = array_values($table)[0];
            echo "  - {$tableName}\n";
        }
    } else {
        echo "\n⚠ No tables found. Run migration: php database/migrate.php\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "\nTroubleshooting:\n";
    echo "1. Check DATABASE_URL in .env file\n";
    echo "2. Verify database server is running\n";
    echo "3. Check database credentials\n";
    echo "4. Verify database user has proper permissions\n";
    exit(1);
}

