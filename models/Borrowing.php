<?php
/**
 * Borrowing Model
 * Handles all borrowing-related database operations
 */

class Borrowing extends Model {
    
    protected string $table = 'borrowings';
    
    /**
     * Create new borrowing with transaction
     */
    public function createBorrowing(int $userId, int $bookId, int $processedBy, int $daysToReturn = 14): ?int {
        try {
            Database::beginTransaction();
            
            // Check if book is available
            $bookModel = new Book();
            if (!$bookModel->isAvailable($bookId)) {
                Database::rollBack();
                return null;
            }
            
            // Create borrowing record
            $borrowingId = $this->create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'borrowed_date' => date('Y-m-d'),
                'due_date' => date('Y-m-d', strtotime("+$daysToReturn days")),
                'status' => 'active',
                'processed_by' => $processedBy
            ]);
            
            // Update book availability
            if (!$bookModel->updateAvailability($bookId, -1)) {
                Database::rollBack();
                return null;
            }
            
            Database::commit();
            return $borrowingId;
            
        } catch (Exception $e) {
            Database::rollBack();
            error_log("Borrowing creation error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Return book with transaction
     */
    public function returnBook(int $borrowingId): bool {
        try {
            Database::beginTransaction();
            
            $borrowing = $this->findById($borrowingId);
            if (!$borrowing || $borrowing['status'] !== 'active') {
                Database::rollBack();
                return false;
            }
            
            // Update borrowing record
            $this->update($borrowingId, [
                'return_date' => date('Y-m-d'),
                'status' => 'returned'
            ]);
            
            // Update book availability
            $bookModel = new Book();
            if (!$bookModel->updateAvailability($borrowing['book_id'], 1)) {
                Database::rollBack();
                return false;
            }
            
            Database::commit();
            return true;
            
        } catch (Exception $e) {
            Database::rollBack();
            error_log("Book return error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get user's borrowings with book details
     */
    public function getUserBorrowings(int $userId, ?string $status = null): array {
        $sql = "SELECT br.*, 
                b.title, b.author, b.isbn, b.cover_image,
                u.first_name as processed_by_first_name,
                u.last_name as processed_by_last_name
                FROM {$this->table} br
                INNER JOIN books b ON br.book_id = b.id
                LEFT JOIN users u ON br.processed_by = u.id
                WHERE br.user_id = :user_id";
        
        $params = ['user_id' => $userId];
        
        if ($status !== null) {
            $sql .= " AND br.status = :status";
            $params['status'] = $status;
        }
        
        $sql .= " ORDER BY br.created_at DESC";
        
        $stmt = Database::query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Get all borrowings with details (for librarian/admin)
     */
    public function getAllBorrowingsWithDetails(int $offset, int $limit, array $filters = []): array {
        $sql = "SELECT br.*, 
                b.title, b.author, b.isbn,
                u.first_name as user_first_name,
                u.last_name as user_last_name,
                u.email as user_email
                FROM {$this->table} br
                INNER JOIN books b ON br.book_id = b.id
                INNER JOIN users u ON br.user_id = u.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND br.status = :status";
            $params['status'] = $filters['status'];
        }
        
        if (!empty($filters['user_id'])) {
            $sql .= " AND br.user_id = :user_id";
            $params['user_id'] = $filters['user_id'];
        }
        
        $sql .= " ORDER BY br.created_at DESC LIMIT :limit OFFSET :offset";
        
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
     * Get overdue borrowings
     */
    public function getOverdueBorrowings(): array {
        $sql = "SELECT br.*, 
                b.title, b.author,
                u.first_name as user_first_name,
                u.last_name as user_last_name,
                u.email as user_email,
                DATEDIFF(CURDATE(), br.due_date) as days_overdue
                FROM {$this->table} br
                INNER JOIN books b ON br.book_id = b.id
                INNER JOIN users u ON br.user_id = u.id
                WHERE br.status = 'active' 
                AND br.due_date < CURDATE()
                ORDER BY days_overdue DESC";
        
        $stmt = Database::query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Update overdue status
     */
    public function updateOverdueStatus(): int {
        $sql = "UPDATE {$this->table} 
                SET status = 'overdue' 
                WHERE status = 'active' 
                AND due_date < CURDATE()";
        
        $stmt = Database::query($sql);
        return $stmt->rowCount();
    }
    
    /**
     * Check if user has active borrowing for book
     */
    public function hasActiveBorrowing(int $userId, int $bookId): bool {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE user_id = :user_id 
                AND book_id = :book_id 
                AND status IN ('active', 'overdue')";
        
        $stmt = Database::query($sql, [
            'user_id' => $userId,
            'book_id' => $bookId
        ]);
        
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Get borrowing statistics
     */
    public function getStatistics(): array {
        $sql = "SELECT 
                    COUNT(*) as total_borrowings,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = 'returned' THEN 1 ELSE 0 END) as returned,
                    SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) as overdue,
                    SUM(CASE WHEN status = 'lost' THEN 1 ELSE 0 END) as lost
                FROM {$this->table}";
        
        $stmt = Database::query($sql);
        return $stmt->fetch();
    }
    
    /**
     * Get borrowing count by user
     */
    public function getUserBorrowingCount(int $userId, ?string $status = null): int {
        $conditions = ['user_id' => $userId];
        if ($status !== null) {
            $conditions['status'] = $status;
        }
        return $this->count($conditions);
    }
}