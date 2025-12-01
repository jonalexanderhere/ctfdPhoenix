<?php
/**
 * Database Migration Script
 * Run this to create all necessary tables
 */

require_once __DIR__ . '/../vendor/autoload.php';

use CTFd\Database;

// Load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

echo "Starting database migration...\n";

try {
    $db = Database::getInstance();
    $connection = $db->getConnection();
    
    // Read schema file
    $schemaFile = __DIR__ . '/schema.sql';
    if (!file_exists($schemaFile)) {
        die("Schema file not found: {$schemaFile}\n");
    }
    
    $schema = file_get_contents($schemaFile);
    
    // Split by semicolon and execute each statement
    $statements = array_filter(
        array_map('trim', explode(';', $schema)),
        function($stmt) {
            return !empty($stmt) && !preg_match('/^--/', $stmt);
        }
    );
    
    foreach ($statements as $statement) {
        if (!empty(trim($statement))) {
            try {
                $connection->exec($statement);
                echo "✓ Executed statement\n";
            } catch (PDOException $e) {
                // Ignore "table already exists" errors
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "⚠ Warning: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "\n✓ Database migration completed successfully!\n";
    
    // Set default config if not exists
    $ctfName = CTFd\Utils\Config::get('ctf_name');
    if (!$ctfName) {
        CTFd\Utils\Config::set('ctf_name', 'CTFd');
        echo "✓ Set default CTF name\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

