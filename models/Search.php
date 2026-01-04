<?php
/**
 * Search Model
 * Système de recherche avancée multi-critères
 */

class Search extends Model {
    
    protected string $table = 'search_history';
    
    /**
     * Search books with advanced filters
     */
    public function searchBooks(string $query, array $filters = [], int $offset = 0, int $limit = 20): array {
        $sql = "SELECT b.*, c.name as category_name, c.color as category_color,
                COALESCE(AVG(r.rating), 0) as avg_rating,
                COUNT(DISTINCT r.id) as review_count";
        
        // Add relevance score for full-text search
        if (!empty($query)) {
            $sql .= ", MATCH(b.title, b.author, b.description, b.publisher) AGAINST(:query IN NATURAL LANGUAGE MODE) as relevance";
        }
        
        $sql .= " FROM books b
                LEFT JOIN categories c ON b.category_id = c.id
                LEFT JOIN reviews r ON b.id = r.book_id AND r.status = 'approved'
                WHERE b.status = 'active'";
        
        $params = [];
        
        // Full-text search
        if (!empty($query)) {
            $sql .= " AND MATCH(b.title, b.author, b.description, b.publisher) AGAINST(:query IN NATURAL LANGUAGE MODE)";
            $params['query'] = $query;
        }
        
        // Category filter
        if (!empty($filters['category_id'])) {
            $sql .= " AND b.category_id = :category_id";
            $params['category_id'] = $filters['category_id'];
        }
        
        // School level filter
        if (!empty($filters['level_id'])) {
            $sql .= " AND EXISTS (SELECT 1 FROM book_school_levels bsl WHERE bsl.book_id = b.id AND bsl.school_level_id = :level_id)";
            $params['level_id'] = $filters['level_id'];
        }
        
        // Subject filter
        if (!empty($filters['subject_id'])) {
            $sql .= " AND EXISTS (SELECT 1 FROM book_subjects bs WHERE bs.book_id = b.id AND bs.subject_id = :subject_id)";
            $params['subject_id'] = $filters['subject_id'];
        }
        
        // Availability filter
        if (!empty($filters['available_only'])) {
            $sql .= " AND b.available_copies > 0";
        }
        
        // Publication year filter
        if (!empty($filters['year_from'])) {
            $sql .= " AND b.publication_year >= :year_from";
            $params['year_from'] = $filters['year_from'];
        }
        if (!empty($filters['year_to'])) {
            $sql .= " AND b.publication_year <= :year_to";
            $params['year_to'] = $filters['year_to'];
        }
        
        // Author filter
        if (!empty($filters['author'])) {
            $sql .= " AND b.author LIKE :author";
            $params['author'] = "%{$filters['author']}%";
        }
        
        $sql .= " GROUP BY b.id";
        
        // Sorting
        $orderBy = match($filters['sort'] ?? 'relevance') {
            'title' => 'b.title ASC',
            'author' => 'b.author ASC',
            'recent' => 'b.created_at DESC',
            'rating' => 'avg_rating DESC',
            'popular' => 'review_count DESC',
            default => !empty($query) ? 'relevance DESC' : 'b.created_at DESC'
        };
        
        $sql .= " ORDER BY $orderBy LIMIT :limit OFFSET :offset";
        
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
     * Count search results for books
     */
    public function countBookResults(string $query, array $filters = []): int {
        $sql = "SELECT COUNT(DISTINCT b.id) as total FROM books b
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.status = 'active'";
        
        $params = [];
        
        if (!empty($query)) {
            $sql .= " AND MATCH(b.title, b.author, b.description, b.publisher) AGAINST(:query IN NATURAL LANGUAGE MODE)";
            $params['query'] = $query;
        }
        
        if (!empty($filters['category_id'])) {
            $sql .= " AND b.category_id = :category_id";
            $params['category_id'] = $filters['category_id'];
        }
        
        if (!empty($filters['level_id'])) {
            $sql .= " AND EXISTS (SELECT 1 FROM book_school_levels bsl WHERE bsl.book_id = b.id AND bsl.school_level_id = :level_id)";
            $params['level_id'] = $filters['level_id'];
        }
        
        if (!empty($filters['available_only'])) {
            $sql .= " AND b.available_copies > 0";
        }
        
        if (!empty($filters['author'])) {
            $sql .= " AND b.author LIKE :author";
            $params['author'] = "%{$filters['author']}%";
        }
        
        $stmt = Database::query($sql, $params);
        $result = $stmt->fetch();
        return (int) $result['total'];
    }
    
    /**
     * Search exercises with filters
     */
    public function searchExercises(string $query, array $filters = [], int $offset = 0, int $limit = 20): array {
        $sql = "SELECT e.*, 
                sl.name as level_name,
                ss.name as subject_name, ss.icon as subject_icon, ss.color as subject_color";
        
        if (!empty($query)) {
            $sql .= ", MATCH(e.title, e.description) AGAINST(:query IN NATURAL LANGUAGE MODE) as relevance";
        }
        
        $sql .= " FROM exercises e
                INNER JOIN school_levels sl ON e.school_level_id = sl.id
                INNER JOIN school_subjects ss ON e.subject_id = ss.id
                WHERE e.status = 'active'";
        
        $params = [];
        
        if (!empty($query)) {
            $sql .= " AND MATCH(e.title, e.description) AGAINST(:query IN NATURAL LANGUAGE MODE)";
            $params['query'] = $query;
        }
        
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
        
        $orderBy = !empty($query) ? 'relevance DESC' : 'e.created_at DESC';
        $sql .= " ORDER BY $orderBy LIMIT :limit OFFSET :offset";
        
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
     * Count exercise results
     */
    public function countExerciseResults(string $query, array $filters = []): int {
        $sql = "SELECT COUNT(*) as total FROM exercises e WHERE e.status = 'active'";
        $params = [];
        
        if (!empty($query)) {
            $sql .= " AND MATCH(e.title, e.description) AGAINST(:query IN NATURAL LANGUAGE MODE)";
            $params['query'] = $query;
        }
        
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
        
        $stmt = Database::query($sql, $params);
        $result = $stmt->fetch();
        return (int) $result['total'];
    }
    
    /**
     * Save search to history
     */
    public function saveSearchHistory(string $query, string $type, array $filters, int $resultsCount, ?int $userId = null): void {
        $this->create([
            'user_id' => $userId,
            'search_query' => $query,
            'search_type' => $type,
            'filters' => json_encode($filters),
            'results_count' => $resultsCount,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
        ]);
        
        // Update popular searches
        $sql = "INSERT INTO popular_searches (search_term, search_count) 
                VALUES (:term, 1) 
                ON DUPLICATE KEY UPDATE search_count = search_count + 1";
        Database::query($sql, ['term' => strtolower(trim($query))]);
    }
    
    /**
     * Get popular searches
     */
    public function getPopularSearches(int $limit = 10): array {
        $sql = "SELECT search_term, search_count FROM popular_searches 
                ORDER BY search_count DESC, last_searched DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get user search history
     */
    public function getUserSearchHistory(int $userId, int $limit = 10): array {
        $sql = "SELECT DISTINCT search_query, search_type, MAX(created_at) as last_search
                FROM {$this->table}
                WHERE user_id = :user_id
                GROUP BY search_query, search_type
                ORDER BY last_search DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get search suggestions
     */
    public function getSearchSuggestions(string $query, int $limit = 5): array {
        $query = strtolower(trim($query));
        
        $sql = "SELECT DISTINCT search_term, search_count 
                FROM popular_searches 
                WHERE search_term LIKE :query
                ORDER BY search_count DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':query', "$query%");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}