<?php
require_once ROOT_PATH . '/core/Model.php';
/**
 * News Model
 * Handles carousel news items
 */

class News extends Model {
    
    protected string $table = 'news';
    
    /**
     * Get active news ordered by display order
     */
    public function getActiveNews(int $limit = 5): array {
        return $this->findAll(['status' => 'active'], 'display_order ASC', $limit);
    }
}