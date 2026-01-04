<?php
require_once ROOT_PATH . '/core/Database.php';
/**
 * Base Model Class
 * Provides common database operations for all models
 */

abstract class Model {
    protected PDO $db;
    protected string $table;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Find record by ID
     */
    public function findById(int $id): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = Database::query($sql, ['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Find all records with optional conditions
     */
    public function findAll(array $conditions = [], string $orderBy = 'id DESC', ?int $limit = null): array {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = :$key";
                $params[$key] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        
        $sql .= " ORDER BY $orderBy";
        
        if ($limit !== null) {
            $sql .= " LIMIT $limit";
        }
        
        $stmt = Database::query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Create new record
     */
    public function create(array $data): int {
        $fields = array_keys($data);
        $values = array_map(fn($field) => ":$field", $fields);
        
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(', ', $fields),
            implode(', ', $values)
        );
        
        Database::query($sql, $data);
        return (int) Database::lastInsertId();
    }
    
    /**
     * Update record by ID
     */
    public function update(int $id, array $data): bool {
        $fields = [];
        foreach (array_keys($data) as $field) {
            $fields[] = "$field = :$field";
        }
        
        $sql = sprintf(
            "UPDATE %s SET %s WHERE id = :id",
            $this->table,
            implode(', ', $fields)
        );
        
        $data['id'] = $id;
        $stmt = Database::query($sql, $data);
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Delete record by ID
     */
    public function delete(int $id): bool {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = Database::query($sql, ['id' => $id]);
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Count records with optional conditions
     */
    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
    if (!empty($conditions)) {
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "$key = :$key";
            $params[$key] = $value;
        }
        $sql .= " WHERE " . implode(' AND ', $where);
    }
    
    $stmt = Database::query($sql, $params);
    $result = $stmt->fetch();
    return (int) $result['total'];
}
}

?>