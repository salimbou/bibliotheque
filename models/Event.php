<?php
/**
 * Event Model
 * Handles all event-related database operations
 */

class Event extends Model {
    
    protected string $table = 'events';
    
    /**
     * Get upcoming events
     */
    public function getUpcomingEvents(int $limit = 10): array {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 'upcoming' 
                AND event_date >= CURDATE()
                ORDER BY event_date ASC, event_time ASC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get event details with participant count
     */
    public function getEventDetails(int $eventId): ?array {
        $sql = "SELECT e.*, 
                COUNT(ep.id) as registered_count,
                u.first_name as creator_first_name,
                u.last_name as creator_last_name
                FROM {$this->table} e
                LEFT JOIN event_participants ep ON e.id = ep.event_id AND ep.status = 'registered'
                LEFT JOIN users u ON e.created_by = u.id
                WHERE e.id = :id
                GROUP BY e.id
                LIMIT 1";
        
        $stmt = Database::query($sql, ['id' => $eventId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Register user for event
     */
    public function registerParticipant(int $eventId, int $userId): bool {
        try {
            $event = $this->getEventDetails($eventId);
            
            if (!$event || $event['status'] !== 'upcoming') {
                return false;
            }
            
            // Check if event is full
            if ($event['max_participants'] && $event['registered_count'] >= $event['max_participants']) {
                return false;
            }
            
            // Check if user already registered
            if ($this->isUserRegistered($eventId, $userId)) {
                return false;
            }
            
            Database::beginTransaction();
            
            $sql = "INSERT INTO event_participants (event_id, user_id, status) 
                    VALUES (:event_id, :user_id, 'registered')";
            Database::query($sql, [
                'event_id' => $eventId,
                'user_id' => $userId
            ]);
            
            // Update participant count
            $this->update($eventId, [
                'current_participants' => $event['registered_count'] + 1
            ]);
            
            Database::commit();
            return true;
            
        } catch (Exception $e) {
            Database::rollBack();
            error_log("Event registration error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if user is registered for event
     */
    public function isUserRegistered(int $eventId, int $userId): bool {
        $sql = "SELECT COUNT(*) as count FROM event_participants 
                WHERE event_id = :event_id AND user_id = :user_id";
        
        $stmt = Database::query($sql, [
            'event_id' => $eventId,
            'user_id' => $userId
        ]);
        
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Get user's registered events
     */
    public function getUserEvents(int $userId): array {
        $sql = "SELECT e.*, ep.status as registration_status
                FROM {$this->table} e
                INNER JOIN event_participants ep ON e.id = ep.event_id
                WHERE ep.user_id = :user_id
                ORDER BY e.event_date DESC";
        
        $stmt = Database::query($sql, ['user_id' => $userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get event statistics
     */
    public function getStatistics(): array {
        $sql = "SELECT 
                    COUNT(*) as total_events,
                    SUM(CASE WHEN status = 'upcoming' THEN 1 ELSE 0 END) as upcoming,
                    SUM(CASE WHEN status = 'ongoing' THEN 1 ELSE 0 END) as ongoing,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(current_participants) as total_participants
                FROM {$this->table}";
        
        $stmt = Database::query($sql);
        return $stmt->fetch();
    }
}