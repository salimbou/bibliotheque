<?php
/**
 * Book Model
 * Handles all book-related database operations
 */

class Book extends Model {
    
    protected string $table = 'books';
    
    /**
     * Find book by ISBN
     */
    public function findByIsbn(string $isbn): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE isbn = :isbn LIMIT 1";
        $stmt = Database::query($sql, ['isbn' => $isbn]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Get books with pagination
     */
    public function getPaginatedBooks(int $offset, int $limit, array $filters = []): array {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active'";
        $params = [];
        
        if (!empty($filters['category'])) {
            $sql .= " AND category = :category";
            $params['category'] = $filters['category'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (title LIKE :search OR author LIKE :search OR description LIKE :search)";
            $params['search'] = "%{$filters['search']}%";
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
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
     * Get all active books
     */
    public function getActiveBooks(): array {
        return $this->findAll(['status' => 'active'], 'title ASC');
    }
    
    /**
     * Get top books (most borrowed)
     */
    public function getTopBooks(int $limit = 6): array {
        $sql = "SELECT b.*, COUNT(br.id) as borrow_count,
                COALESCE(AVG(r.rating), 0) as avg_rating
                FROM {$this->table} b
                LEFT JOIN borrowings br ON b.id = br.book_id
                LEFT JOIN reviews r ON b.id = r.book_id AND r.status = 'approved'
                WHERE b.status = 'active'
                GROUP BY b.id
                ORDER BY borrow_count DESC, avg_rating DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get book details with average rating and total reviews
     */
    public function getBookDetails(int $bookId): ?array {
        $sql = "SELECT b.*, 
                COALESCE(AVG(r.rating), 0) as avg_rating,
                COUNT(DISTINCT r.id) as total_reviews,
                u.first_name as creator_first_name,
                u.last_name as creator_last_name
                FROM {$this->table} b
                LEFT JOIN reviews r ON b.id = r.book_id AND r.status = 'approved'
                LEFT JOIN users u ON b.created_by = u.id
                WHERE b.id = :id
                GROUP BY b.id
                LIMIT 1";
        
        $stmt = Database::query($sql, ['id' => $bookId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Update book availability
     */
    public function updateAvailability(int $bookId, int $change): bool {
        $sql = "UPDATE {$this->table} 
                SET available_copies = available_copies + :change 
                WHERE id = :id 
                AND available_copies + :change >= 0 
                AND available_copies + :change <= total_copies";
        
        $stmt = Database::query($sql, [
            'id' => $bookId,
            'change' => $change
        ]);
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Get books by category
     */
    public function getBooksByCategory(string $category): array {
        return $this->findAll(['category' => $category, 'status' => 'active'], 'title ASC');
    }
    
    /**
     * Get all categories
     */
    public function getCategories(): array {
        $sql = "SELECT DISTINCT category FROM {$this->table} 
                WHERE status = 'active' AND category IS NOT NULL 
                ORDER BY category ASC";
        
        $stmt = Database::query($sql);
        return array_column($stmt->fetchAll(), 'category');
    }
    
    /**
     * Search books (full-text search if available)
     */
    public function searchBooks(string $query): array {
        $searchTerm = "%$query%";
        $sql = "SELECT b.*, 
                COALESCE(AVG(r.rating), 0) as avg_rating
                FROM {$this->table} b
                LEFT JOIN reviews r ON b.id = r.book_id AND r.status = 'approved'
                WHERE b.status = 'active'
                AND (b.title LIKE :search 
                    OR b.author LIKE :search 
                    OR b.description LIKE :search
                    OR b.isbn LIKE :search)
                GROUP BY b.id
                ORDER BY b.title ASC";
        
        $stmt = Database::query($sql, ['search' => $searchTerm]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get book statistics
     */
    public function getStatistics(): array {
        $sql = "SELECT 
                    COUNT(*) as total_books,
                    SUM(total_copies) as total_copies,
                    SUM(available_copies) as available_copies,
                    SUM(total_copies - available_copies) as borrowed_copies,
                    COUNT(DISTINCT category) as total_categories
                FROM {$this->table}
                WHERE status = 'active'";
        
        $stmt = Database::query($sql);
        return $stmt->fetch();
    }
    
    /**
     * Check if book is available
     */
    public function isAvailable(int $bookId): bool {
        $book = $this->findById($bookId);
        return $book && $book['available_copies'] > 0 && $book['status'] === 'active';
    }
}