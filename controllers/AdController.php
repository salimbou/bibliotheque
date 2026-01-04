<?php
/**
 * Ad Controller
 * Gère les publicités et le tracking
 */

class AdController {
    
    private Advertisement $adModel;
    
    public function __construct() {
        $this->adModel = new Advertisement();
    }
    
    /**
     * Track click and redirect
     */
    public function trackClick(): void {
        $adId = (int)($_GET['id'] ?? 0);
        
        if (!$adId) {
            Session::setFlash('error', 'Publicité introuvable');
            redirect('/');
        }
        
        $ad = $this->adModel->findById($adId);
        
        if (!$ad) {
            Session::setFlash('error', 'Publicité introuvable');
            redirect('/');
        }
        
        if ($ad['status'] !== 'active') {
            Session::setFlash('error', 'Cette publicité n\'est plus active');
            redirect('/');
        }
        
        // Track click
        try {
            $this->adModel->trackClick($adId, Session::getUserId());
        } catch (Exception $e) {
            error_log("Error tracking ad click: " . $e->getMessage());
        }
        
        // Redirect to ad URL
        header("Location: " . $ad['link_url']);
        exit;
    }
    
    /**
     * Admin: List all ads
     */
    public function listAds(): void {
        RoleMiddleware::admin();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        
        $status = isset($_GET['status']) ? sanitize($_GET['status']) : null;
        
        $filters = [];
        if ($status) {
            $filters['status'] = $status;
        }
        
        $total = $this->adModel->count($filters);
        $pagination = paginate($total, $page);
        
        $ads = $this->adModel->getPaginatedAds($pagination['offset'], $pagination['per_page'], $filters);
        
        require_once ROOT_PATH . '/views/admin/ads.php';
    }
    
    /**
     * Admin: View ad statistics
     */
    public function viewStats(): void {
        RoleMiddleware::admin();
        
        $adId = (int)($_GET['id'] ?? 0);
        
        if (!$adId) {
            redirect('/admin/ads');
        }
        
        $adStats = $this->adModel->getAdStatistics($adId);
        
        if (!$adStats) {
            Session::setFlash('error', 'Publicité introuvable');
            redirect('/admin/ads');
        }
        
        require_once ROOT_PATH . '/views/admin/ad_stats.php';
    }
    
    /**
     * Admin: Approve ad
     */
    public function approveAd(): void {
        RoleMiddleware::admin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/ads');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/admin/ads');
        }
        
        $adId = (int)($_POST['ad_id'] ?? 0);
        
        $data = [
            'status' => 'active',
            'approved_by' => Session::getUserId()
        ];
        
        if ($this->adModel->update($adId, $data)) {
            Session::setFlash('success', 'Publicité approuvée avec succès');
        } else {
            Session::setFlash('error', 'Erreur lors de l\'approbation');
        }
        
        redirect('/admin/ads');
    }
    
    /**
     * Admin: Pause ad
     */
    public function pauseAd(): void {
        RoleMiddleware::admin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/ads');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/admin/ads');
        }
        
        $adId = (int)($_POST['ad_id'] ?? 0);
        
        if ($this->adModel->update($adId, ['status' => 'paused'])) {
            Session::setFlash('success', 'Publicité mise en pause');
        } else {
            Session::setFlash('error', 'Erreur lors de la mise en pause');
        }
        
        redirect('/admin/ads');
    }
    
    /**
     * Admin: Delete ad
     */
    public function deleteAd(): void {
        RoleMiddleware::admin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/ads');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/admin/ads');
        }
        
        $adId = (int)($_POST['ad_id'] ?? 0);
        
        try {
            if ($this->adModel->delete($adId)) {
                Session::setFlash('success', 'Publicité supprimée avec succès');
            } else {
                Session::setFlash('error', 'Erreur lors de la suppression');
            }
        } catch (Exception $e) {
            Session::setFlash('error', 'Impossible de supprimer cette publicité');
        }
        
        redirect('/admin/ads');
    }
}