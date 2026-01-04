<?php
/**
 * Review Model
 * Handles all review-related database operations
 */

class Review extends Model {
    
    protected string $table = 'reviews';
    
    /**
     * Create review
     */
    public function createReview(int $bookId, int $userId, int $rating, string $reviewText): ?int {
        // Check if user already reviewed this book
        if ($this->hasUserReviewed($bookId, $userId)) {
            return null;
        }
        
        return $this->create([
            'book_id' => $bookId,
            'user_id' => $userId,
            'rating' => $rating,
            'review_text' => $reviewText,
            'status' => 'pending'
        ]);
    }
    
    /**
     * Check if user has reviewed a book
     */
    public function hasUserReviewed(int $bookId, int $userId): bool {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE book_id = :book_id AND user_id = :user_id";
        
        $stmt = Database::query($sql, [
            'book_id' => $bookId,
            'user_id' => $userId
        ]);
        
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Get book reviews
     */
    public function getBookReviews(int $bookId, string $status = 'approved'): array {
        $sql = "SELECT r.*, 
                u.first_name, u.last_name,
                (SELECT COUNT(*) FROM reactions 
                 WHERE reactable_type = 'review' 
                 AND reactable_id = r.id 
                 AND reaction_type = 'like') as likes,
                (SELECT COUNT(*) FROM reactions 
                 WHERE reactable_type = 'review' 
                 AND reactable_id = r.id 
                 AND reaction_type = 'dislike') as dislikes
                FROM {$this->table} r
                INNER JOIN users u ON r.user_id = u.id
                WHERE r.book_id = :book_id AND r.status = :status
                ORDER BY r.created_at DESC";
        
        $stmt = Database::query($sql, [
            'book_id' => $bookId,
            'status' => $status
        ]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get pending reviews
     */
    public function getPendingReviews(int $offset, int $limit): array {
        $sql = "SELECT r.*, 
                b.title as book_title,
                u.first_name, u.last_name
                FROM {$this->table} r
                INNER JOIN books b ON r.book_id = b.id
                INNER JOIN users u ON r.user_id = u.id
                WHERE r.status = 'pending'
                ORDER BY r.created_at ASC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Moderate review
     */
    public function moderateReview(int $reviewId, string $status, int $moderatorId): bool {
        if (!in_array($status, ['approved', 'rejected'])) {
            return false;
        }
        
        return $this->update($reviewId, [
            'status' => $status,
            'moderated_by' => $moderatorId
        ]);
    }
    
    /**
     * Get user reviews
     */
    public function getUserReviews(int $userId): array {
        $sql = "SELECT r.*, b.title as book_title, b.cover_image
                FROM {$this->table} r
                INNER JOIN books b ON r.book_id = b.id
                WHERE r.user_id = :user_id
                ORDER BY r.created_at DESC";
        
        $stmt = Database::query($sql, ['user_id' => $userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get review statistics
     */
    public function getStatistics(): array {
        $sql = "SELECT 
                    COUNT(*) as total_reviews,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                    AVG(rating) as avg_rating
                FROM {$this->table}";
        
        $stmt = Database::query($sql);
        return $stmt->fetch();
    }
}