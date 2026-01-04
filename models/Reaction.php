<?php
/**
 * Reaction Model
 * Handles likes and dislikes on reviews and comments
 */

class Reaction extends Model {
    
    protected string $table = 'reactions';
    
    /**
     * Toggle reaction
     */
    public function toggleReaction(string $type, int $id, int $userId, string $reactionType): bool {
        try {
            // Check if reaction exists
            $sql = "SELECT * FROM {$this->table} 
                    WHERE reactable_type = :type 
                    AND reactable_id = :id 
                    AND user_id = :user_id";
            
            $stmt = Database::query($sql, [
                'type' => $type,
                'id' => $id,
                'user_id' => $userId
            ]);
            
            $existing = $stmt->fetch();
            
            if ($existing) {
                // If same reaction, remove it
                if ($existing['reaction_type'] === $reactionType) {
                    $this->delete($existing['id']);
                } else {
                    // Update to new reaction type
                    $this->update($existing['id'], ['reaction_type' => $reactionType]);
                }
            } else {
                // Create new reaction
                $this->create([
                    'reactable_type' => $type,
                    'reactable_id' => $id,
                    'user_id' => $userId,
                    'reaction_type' => $reactionType
                ]);
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log("Reaction toggle error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get user's reaction
     */
    public function getUserReaction(string $type, int $id, int $userId): ?string {
        $sql = "SELECT reaction_type FROM {$this->table} 
                WHERE reactable_type = :type 
                AND reactable_id = :id 
                AND user_id = :user_id";
        
        $stmt = Database::query($sql, [
            'type' => $type,
            'id' => $id,
            'user_id' => $userId
        ]);
        
        $result = $stmt->fetch();
        return $result ? $result['reaction_type'] : null;
    }
}