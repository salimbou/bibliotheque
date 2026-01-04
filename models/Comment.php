<?php
/**
 * Comment Model
 * Handles comments on books and events
 */

class Comment extends Model {
    
    protected string $table = 'comments';
    
    /**
     * Create comment
     */
    public function createComment(string $type, int $id, int $userId, string $text): int {
        return $this->create([
            'commentable_type' => $type,
            'commentable_id' => $id,
            'user_id' => $userId,
            'comment_text' => $text,
            'status' => 'pending'
        ]);
    }
    
    /**
     * Get comments for entity
     */
    public function getComments(string $type, int $id, string $status = 'approved'): array {
        $sql = "SELECT c.*, 
                u.first_name, u.last_name,
                (SELECT COUNT(*) FROM reactions 
                 WHERE reactable_type = 'comment' 
                 AND reactable_id = c.id 
                 AND reaction_type = 'like') as likes,
                (SELECT COUNT(*) FROM reactions 
                 WHERE reactable_type = 'comment' 
                 AND reactable_id = c.id 
                 AND reaction_type = 'dislike') as dislikes
                FROM {$this->table} c
                INNER JOIN users u ON c.user_id = u.id
                WHERE c.commentable_type = :type 
                AND c.commentable_id = :id 
                AND c.status = :status
                ORDER BY c.created_at DESC";
        
        $stmt = Database::query($sql, [
            'type' => $type,
            'id' => $id,
            'status' => $status
        ]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get pending comments
     */
    public function getPendingComments(int $offset, int $limit): array {
        $sql = "SELECT c.*, 
                u.first_name, u.last_name,
                CASE 
                    WHEN c.commentable_type = 'book' THEN b.title
                    WHEN c.commentable_type = 'event' THEN e.title
                END as entity_title
                FROM {$this->table} c
                INNER JOIN users u ON c.user_id = u.id
                LEFT JOIN books b ON c.commentable_type = 'book' AND c.commentable_id = b.id
                LEFT JOIN events e ON c.commentable_type = 'event' AND c.commentable_id = e.id
                WHERE c.status = 'pending'
                ORDER BY c.created_at ASC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Moderate comment
     */
    public function moderateComment(int $commentId, string $status, int $moderatorId): bool {
        if (!in_array($status, ['approved', 'rejected'])) {
            return false;
        }
        
        return $this->update($commentId, [
            'status' => $status,
            'moderated_by' => $moderatorId
        ]);
    }
}