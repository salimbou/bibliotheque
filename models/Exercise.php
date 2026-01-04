<?php
/**
 * Exercise Model
 * Gère les exercices scolaires
 */

class Exercise extends Model {
    
    protected string $table = 'exercises';
    
    /**
     * Get exercises with filters
     */
    public function getExercises(array $filters = [], int $offset = 0, int $limit = 12): array {
        $sql = "SELECT e.*, 
                sl.name as level_name,
                ss.name as subject_name,
                ss.icon as subject_icon,
                ss.color as subject_color
                FROM {$this->table} e
                INNER JOIN school_levels sl ON e.school_level_id = sl.id
                INNER JOIN school_subjects ss ON e.subject_id = ss.id
                WHERE e.status = 'active'";
        
        $params = [];
        
        if (!empty($filters['level_id'])) {
            $sql .= " AND e.school_level_id = :level_id";
            $params['level_id'] = $filters['level_id'];
        }
        
        if (!empty($filters['subject_id'])) {
            $sql .= " AND e.subject_id = :subject_id";
            $params['subject_id'] = $filters['subject_id'];
        }
        
        if (!empty($filters['difficulty'])) {
            $sql .= " AND e.difficulty = :difficulty";
            $params['difficulty'] = $filters['difficulty'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (e.title LIKE :search OR e.description LIKE :search)";
            $params['search'] = "%{$filters['search']}%";
        }
        
        $sql .= " ORDER BY e.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get exercise details with tags
     */
    public function getExerciseDetails(int $exerciseId): ?array {
        $sql = "SELECT e.*, 
                sl.name as level_name, sl.slug as level_slug,
                ss.name as subject_name, ss.icon as subject_icon, ss.color as subject_color,
                b.title as book_title,
                u.first_name, u.last_name
                FROM {$this->table} e
                INNER JOIN school_levels sl ON e.school_level_id = sl.id
                INNER JOIN school_subjects ss ON e.subject_id = ss.id
                LEFT JOIN books b ON e.book_id = b.id
                LEFT JOIN users u ON e.created_by = u.id
                WHERE e.id = :id";
        
        $stmt = Database::query($sql, ['id' => $exerciseId]);
        $exercise = $stmt->fetch();
        
        if ($exercise) {
            // Get tags
            $sql = "SELECT t.* FROM exercise_tags t
                    INNER JOIN exercise_tag_relations etr ON t.id = etr.tag_id
                    WHERE etr.exercise_id = :exercise_id";
            $stmt = Database::query($sql, ['exercise_id' => $exerciseId]);
            $exercise['tags'] = $stmt->fetchAll();
        }
        
        return $exercise ?: null;
    }
    
    /**
     * Increment view count
     */
    public function incrementViewCount(int $exerciseId): void {
        $sql = "UPDATE {$this->table} SET view_count = view_count + 1 WHERE id = :id";
        Database::query($sql, ['id' => $exerciseId]);
    }
    
    /**
     * Increment download count
     */
    public function incrementDownloadCount(int $exerciseId): void {
        $sql = "UPDATE {$this->table} SET download_count = download_count + 1 WHERE id = :id";
        Database::query($sql, ['id' => $exerciseId]);
    }
    
    /**
     * Get popular exercises
     */
    public function getPopularExercises(int $limit = 10): array {
        $sql = "SELECT e.*, 
                sl.name as level_name,
                ss.name as subject_name,
                ss.color as subject_color
                FROM {$this->table} e
                INNER JOIN school_levels sl ON e.school_level_id = sl.id
                INNER JOIN school_subjects ss ON e.subject_id = ss.id
                WHERE e.status = 'active'
                ORDER BY (e.view_count + e.download_count) DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Count exercises with filters
     */
    public function countExercises(array $filters = []): int {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} e WHERE e.status = 'active'";
        $params = [];
        
        if (!empty($filters['level_id'])) {
            $sql .= " AND e.school_level_id = :level_id";
            $params['level_id'] = $filters['level_id'];
        }
        
        if (!empty($filters['subject_id'])) {
            $sql .= " AND e.subject_id = :subject_id";
            $params['subject_id'] = $filters['subject_id'];
        }
        
        if (!empty($filters['difficulty'])) {
            $sql .= " AND e.difficulty = :difficulty";
            $params['difficulty'] = $filters['difficulty'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (e.title LIKE :search OR e.description LIKE :search)";
            $params['search'] = "%{$filters['search']}%";
        }
        
        $stmt = Database::query($sql, $params);
        $result = $stmt->fetch();
        return (int) $result['total'];
    }
}