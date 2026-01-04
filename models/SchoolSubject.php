<?php
/**
 * School Subject Model
 * Gère les matières scolaires
 */

class SchoolSubject extends Model {
    
    protected string $table = 'school_subjects';
    
    /**
     * Get all active subjects
     */
    public function getAllSubjects(): array {
        return $this->findAll(['status' => 'active'], 'display_order ASC');
    }
    
    /**
     * Get subject by slug
     */
    public function findBySlug(string $slug): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
        $stmt = Database::query($sql, ['slug' => $slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Get subjects for a level
     */
    public function getSubjectsForLevel(int $levelId): array {
        $sql = "SELECT DISTINCT s.*
                FROM {$this->table} s
                INNER JOIN exercises e ON s.id = e.subject_id
                WHERE e.school_level_id = :level_id AND s.status = 'active'
                ORDER BY s.display_order ASC";
        
        $stmt = Database::query($sql, ['level_id' => $levelId]);
        return $stmt->fetchAll();
    }
}