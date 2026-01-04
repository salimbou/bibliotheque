<?php
/**
 * Database Connection Singleton
 * Manages PDO connection with error handling
 */

class Database {
    private static ?PDO $instance = null;
    private static array $config;
    
    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct() {}
    
    /**
     * Prevent cloning of the instance
     */
    private function __clone() {}
    
    /**
     * Get database instance (Singleton pattern)
     */
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            self::$config = require ROOT_PATH . '/config/database.php';
            
            try {
                $dsn = sprintf(
                    "mysql:host=%s;port=%s;dbname=%s;charset=%s",
                    self::$config['host'],
                    self::$config['port'],
                    self::$config['database'],
                    self::$config['charset']
                );
                
                self::$instance = new PDO(
                    $dsn,
                    self::$config['username'],
                    self::$config['password'],
                    self::$config['options']
                );
                
            } catch (PDOException $e) {
                error_log("Database Connection Error: " . $e->getMessage());
                die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
            }
        }
        
        return self::$instance;
    }
    
    /**
     * Execute a query and return results
     */
    public static function query(string $sql, array $params = []): PDOStatement {
        try {
            $stmt = self::getInstance()->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query Error: " . $e->getMessage() . " | SQL: " . $sql);
            throw $e;
        }
    }
    
    /**
     * Begin transaction
     */
    public static function beginTransaction(): bool {
        return self::getInstance()->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public static function commit(): bool {
        return self::getInstance()->commit();
    }
    
    /**
     * Rollback transaction
     */
    public static function rollBack(): bool {
        return self::getInstance()->rollBack();
    }
    
    /**
     * Get last insert ID
     */
    public static function lastInsertId(): string {
        return self::getInstance()->lastInsertId();
    }
}
?>