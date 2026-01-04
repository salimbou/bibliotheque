<?php
/**
 * Announcement Model
 * Handles announcements
 */

class Announcement extends Model {
    
    protected string $table = 'announcements';
    
    /**
     * Get active announcements
     */
    public function getActiveAnnouncements(int $limit = 5): array {
        return $this->findAll(['status' => 'active'], 'display_order ASC', $limit);
    }
}