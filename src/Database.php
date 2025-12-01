<?php

namespace CTFd;

use PDO;
use PDOException;

/**
 * Database Connection Handler
 */
class Database
{
    private static $instance = null;
    private $connection = null;
    
    private function __construct()
    {
        $this->connect();
    }
    
    /**
     * Get database instance (Singleton)
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Connect to database
     * Supports Supabase (PostgreSQL) and MySQL
     */
    private function connect()
    {
        $databaseUrl = $_ENV['DATABASE_URL'] ?? '';
        
        if (empty($databaseUrl)) {
            // Try individual components
            $host = $_ENV['DATABASE_HOST'] ?? 'localhost';
            $port = $_ENV['DATABASE_PORT'] ?? 3306;
            $name = $_ENV['DATABASE_NAME'] ?? 'ctfd';
            $user = $_ENV['DATABASE_USER'] ?? 'root';
            $password = $_ENV['DATABASE_PASSWORD'] ?? '';
            $driver = $_ENV['DATABASE_DRIVER'] ?? 'mysql';
            
            if ($driver === 'pgsql' || $driver === 'postgresql') {
                $dsn = "pgsql:host={$host};port={$port};dbname={$name}";
            } else {
                $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
            }
        } else {
            // Parse DATABASE_URL (Supabase format: postgresql://user:pass@host:port/dbname)
            $parsed = parse_url($databaseUrl);
            $scheme = $parsed['scheme'] ?? 'mysql';
            $host = $parsed['host'] ?? 'localhost';
            $port = $parsed['port'] ?? 5432;
            $name = ltrim($parsed['path'] ?? '/postgres', '/');
            $user = $parsed['user'] ?? 'postgres';
            $password = $parsed['pass'] ?? '';
            
            // Handle Supabase connection string
            if ($scheme === 'postgresql' || $scheme === 'postgres') {
                // Supabase uses PostgreSQL
                $dsn = "pgsql:host={$host};port={$port};dbname={$name}";
                // Add SSL mode for Supabase if needed
                if (isset($_ENV['DATABASE_SSL']) && $_ENV['DATABASE_SSL'] === 'true') {
                    $dsn .= ";sslmode=require";
                }
            } elseif ($scheme === 'mysql' || $scheme === 'mysql+pymysql') {
                $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
            } else {
                throw new \Exception("Unsupported database scheme: {$scheme}");
            }
        }
        
        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->connection = new PDO($dsn, $user, $password, $options);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new \Exception("Database connection failed");
        }
    }
    
    /**
     * Get PDO connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
    
    /**
     * Execute query
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    /**
     * Get one row
     */
    public function fetchOne($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    /**
     * Get all rows
     */
    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Prevent cloning
     */
    private function __clone() {}
    
    /**
     * Prevent unserialization
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}

