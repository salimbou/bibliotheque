<?php
/**
 * Advertisement Model
 * Gère les publicités
 */

class Advertisement extends Model {
    
    protected string $table = 'advertisements';
    
    /**
     * Get active ads for a specific position and page
     */
    public function getActiveAds(string $adType, string $page = 'home', int $limit = 3): array {
        $today = date('Y-m-d');
        
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = 'active' 
                -- AND start_date <= :start_day 
                -- AND end_date >= :end_day 
                AND ad_type = :ad_type
                AND (display_pages IS NULL OR display_pages LIKE :page)
                ORDER BY RAND()
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        // $stmt->bindValue(':start_day', $today);
        // $stmt->bindValue(':end_day', $today);
        $stmt->bindValue(':ad_type', $adType);
        $stmt->bindValue(':page', "%$page%");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Track ad impression
     */
    public function trackImpression(int $adId, ?int $userId = null): void {
        $sql = "INSERT INTO ad_tracking (ad_id, event_type, user_id, ip_address, user_agent, page_url) 
                VALUES (:ad_id, 'impression', :user_id, :ip, :user_agent, :page_url)";
        
        Database::query($sql, [
            'ad_id' => $adId,
            'user_id' => $userId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'page_url' => $_SERVER['REQUEST_URI'] ?? null
        ]);
        
        // Update impression count
        $this->db->prepare("UPDATE {$this->table} SET impressions = impressions + 1 WHERE id = ?")->execute([$adId]);
    }
    
    /**
     * Track ad click
     */
    public function trackClick(int $adId, ?int $userId = null): void {
        $sql = "INSERT INTO ad_tracking (ad_id, event_type, user_id, ip_address, user_agent, page_url) 
                VALUES (:ad_id, 'click', :user_id, :ip, :user_agent, :page_url)";
        
        Database::query($sql, [
            'ad_id' => $adId,
            'user_id' => $userId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'page_url' => $_SERVER['REQUEST_URI'] ?? null
        ]);
        
        // Update click count
        $this->db->prepare("UPDATE {$this->table} SET clicks = clicks + 1 WHERE id = ?")->execute([$adId]);
    }
    
    /**
     * Get all ads with pagination
     */
    public function getPaginatedAds(int $offset, int $limit, array $filters = []): array {
        $sql = "SELECT a.*, u.first_name, u.last_name 
                FROM {$this->table} a
                LEFT JOIN users u ON a.created_by = u.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND a.status = :status";
            $params['status'] = $filters['status'];
        }
        
        $sql .= " ORDER BY a.created_at DESC LIMIT :limit OFFSET :offset";
        
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
     * Get ad statistics
     */
    public function getAdStatistics(int $adId): array {
        $sql = "SELECT 
                    a.*,
                    COUNT(DISTINCT CASE WHEN t.event_type = 'impression' THEN t.id END) as total_impressions,
                    COUNT(DISTINCT CASE WHEN t.event_type = 'click' THEN t.id END) as total_clicks,
                    ROUND(
                        (COUNT(DISTINCT CASE WHEN t.event_type = 'click' THEN t.id END) * 100.0) / 
                        NULLIF(COUNT(DISTINCT CASE WHEN t.event_type = 'impression' THEN t.id END), 0), 
                        2
                    ) as ctr
                FROM {$this->table} a
                LEFT JOIN ad_tracking t ON a.id = t.ad_id
                WHERE a.id = :ad_id
                GROUP BY a.id";
        
        $stmt = Database::query($sql, ['ad_id' => $adId]);
        return $stmt->fetch() ?: [];
    }
}