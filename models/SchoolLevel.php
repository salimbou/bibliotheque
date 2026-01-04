<?php
/**
 * School Level Model
 * Gère les niveaux scolaires
 */

class SchoolLevel extends Model {
    
    protected string $table = 'school_levels';
    
    /**
     * Get all active levels ordered
     */
    public function getAllLevels(): array {
        return $this->findAll(['status' => 'active'], 'level_order ASC');
    }
    
    /**
     * Get level by slug
     */
    public function findBySlug(string $slug): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
        $stmt = Database::query($sql, ['slug' => $slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Get levels grouped by cycle
     */
    public function getLevelsByCycle(): array {
        $levels = $this->getAllLevels();
        
        $grouped = [
            'primaire' => [],
            'moyenne' => [],
            'secondaire' => []
        ];
        
        foreach ($levels as $level) {
            if ($level['level_order'] <= 5) {
                $grouped['primaire'][] = $level;
            } elseif ($level['level_order'] <= 9) {
                $grouped['moyenne'][] = $level;
            } else {
                $grouped['secondaire'][] = $level;
            }
        }
        
        return $grouped;
    }
    
    /**
     * Get level with book and exercise count
     */
    public function getLevelWithCounts(int $levelId): ?array {
        $sql = "SELECT sl.*, 
                COUNT(DISTINCT bsl.book_id) as book_count,
                COUNT(DISTINCT e.id) as exercise_count
                FROM {$this->table} sl
                LEFT JOIN book_school_levels bsl ON sl.id = bsl.school_level_id
                LEFT JOIN exercises e ON sl.id = e.school_level_id AND e.status = 'active'
                WHERE sl.id = :id
                GROUP BY sl.id";
        
        $stmt = Database::query($sql, ['id' => $levelId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}