<?php
/**
 * Admin Controller
 * Handles admin dashboard and user management
 */

class AdminController {
    
    private User $userModel;
    private Book $bookModel;
    private Borrowing $borrowingModel;
    private Event $eventModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->bookModel = new Book();
        $this->borrowingModel = new Borrowing();
        $this->eventModel = new Event();
    }
    
    /**
     * Show admin dashboard
     */
    public function dashboard(): void {
        RoleMiddleware::admin();
        
        // Get statistics
        $userStats = $this->userModel->getStatistics();
        $bookStats = $this->bookModel->getStatistics();
        $borrowingStats = $this->borrowingModel->getStatistics();
        $eventStats = $this->eventModel->getStatistics();
        
        // Get recent activities
        $recentUsers = $this->userModel->findAll([], 'created_at DESC', 5);
        $overdueBorrowings = $this->borrowingModel->getOverdueBorrowings();
        
        require_once ROOT_PATH . '/views/admin/dashboard.php';
    }
    
    /**
     * Show users list
     */
    public function users(): void {
        RoleMiddleware::admin();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        
        $role = isset($_GET['role']) ? sanitize($_GET['role']) : null;
        $search = isset($_GET['search']) ? sanitize($_GET['search']) : null;
        
        // Get total count
        if ($search) {
            $users = $this->userModel->searchUsers($search);
            $total = count($users);
        } else {
            $total = $this->userModel->count($role ? ['role' => $role] : []);
        }
        
        $pagination = paginate($total, $page);
        
        // Get users for current page
        if (!$search) {
            $users = $this->userModel->getPaginatedUsers($pagination['offset'], $pagination['per_page'], $role);
        } else {
            // Apply pagination to search results
            $users = array_slice($users, $pagination['offset'], $pagination['per_page']);
        }
        
        require_once ROOT_PATH . '/views/admin/users.php';
    }
    
    /**
     * Activate user
     */
    public function activateUser(): void {
        RoleMiddleware::admin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/users');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/admin/users');
        }
        
        $userId = (int)($_POST['user_id'] ?? 0);
        
        if ($this->userModel->updateStatus($userId, 'active')) {
            Session::setFlash('success', 'Utilisateur activé avec succès');
        } else {
            Session::setFlash('error', 'Erreur lors de l\'activation');
        }
        
        redirect('/admin/users');
    }
    
    /**
     * Deactivate user
     */
    public function deactivateUser(): void {
        RoleMiddleware::admin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/users');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/admin/users');
        }
        
        $userId = (int)($_POST['user_id'] ?? 0);
        
        // Prevent admin from deactivating themselves
        if ($userId === Session::getUserId()) {
            Session::setFlash('error', 'Vous ne pouvez pas désactiver votre propre compte');
            redirect('/admin/users');
        }
        
        if ($this->userModel->updateStatus($userId, 'inactive')) {
            Session::setFlash('success', 'Utilisateur désactivé avec succès');
        } else {
            Session::setFlash('error', 'Erreur lors de la désactivation');
        }
        
        redirect('/admin/users');
    }
    
    /**
     * Delete user
     */
    public function deleteUser(): void {
        RoleMiddleware::admin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/users');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/admin/users');
        }
        
        $userId = (int)($_POST['user_id'] ?? 0);
        
        // Prevent admin from deleting themselves
        if ($userId === Session::getUserId()) {
            Session::setFlash('error', 'Vous ne pouvez pas supprimer votre propre compte');
            redirect('/admin/users');
        }
        
        try {
            if ($this->userModel->delete($userId)) {
                Session::setFlash('success', 'Utilisateur supprimé avec succès');
            } else {
                Session::setFlash('error', 'Erreur lors de la suppression');
            }
        } catch (Exception $e) {
            Session::setFlash('error', 'Impossible de supprimer cet utilisateur (données liées existantes)');
        }
        
        redirect('/admin/users');
    }
    
    /**
     * Show statistics
     */
    public function stats(): void {
        RoleMiddleware::admin();
        
        $userStats = $this->userModel->getStatistics();
        $bookStats = $this->bookModel->getStatistics();
        $borrowingStats = $this->borrowingModel->getStatistics();
        $eventStats = $this->eventModel->getStatistics();
        
        require_once ROOT_PATH . '/views/admin/stats.php';
    }
    /**
 * Show admin profile
 */
public function profile(): void {
    RoleMiddleware::admin();
    
    $userId = Session::getUserId();
    $user = $this->userModel->findById($userId);
    
    require_once ROOT_PATH . '/views/admin/profile.php';
}

/**
 * Update admin profile
 */
public function updateProfile(): void {
    RoleMiddleware::admin();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('/admin/profile');
    }
    
    if (!verify_csrf()) {
        Session::setFlash('error', 'Token de sécurité invalide');
        redirect('/admin/profile');
    }
    
    $userId = Session::getUserId();
    
    $data = [
        'first_name' => sanitize($_POST['first_name'] ?? ''),
        'last_name' => sanitize($_POST['last_name'] ?? ''),
        'phone' => sanitize($_POST['phone'] ?? ''),
        'address' => sanitize($_POST['address'] ?? ''),
    ];
    
    // Validation
    $errors = validate_required(['first_name', 'last_name'], $data);
    
    if (!empty($data['phone']) && !validate_phone($data['phone'])) {
        $errors['phone'] = "Le numéro de téléphone n'est pas valide";
    }
    
    if (!empty($errors)) {
        Session::set('profile_errors', $errors);
        redirect('/admin/profile');
    }
    
    // Update profile
    if ($this->userModel->updateProfile($userId, $data)) {
        // Update session name
        Session::set('user_name', $data['first_name'] . ' ' . $data['last_name']);
        Session::setFlash('success', 'Profil mis à jour avec succès');
    } else {
        Session::setFlash('error', 'Erreur lors de la mise à jour');
    }
    
    redirect('/admin/profile');
}

/**
 * Change admin password
 */
public function changePassword(): void {
    RoleMiddleware::admin();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('/admin/profile');
    }
    
    if (!verify_csrf()) {
        Session::setFlash('error', 'Token de sécurité invalide');
        redirect('/admin/profile');
    }
    
    $userId = Session::getUserId();
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Get user
    $user = $this->userModel->findById($userId);
    
    // Verify current password
    if (!verify_password($currentPassword, $user['password'])) {
        Session::setFlash('error', 'Mot de passe actuel incorrect');
        redirect('/admin/profile');
    }
    
    // Validate new password
    $passwordErrors = validate_password($newPassword);
    if (!empty($passwordErrors)) {
        Session::setFlash('error', implode('<br>', $passwordErrors));
        redirect('/admin/profile');
    }
    
    // Check confirmation
    if ($newPassword !== $confirmPassword) {
        Session::setFlash('error', 'Les mots de passe ne correspondent pas');
        redirect('/admin/profile');
    }
    
    // Update password
    if ($this->userModel->changePassword($userId, $newPassword)) {
        Session::setFlash('success', 'Mot de passe modifié avec succès');
    } else {
        Session::setFlash('error', 'Erreur lors de la modification du mot de passe');
    }
    
    redirect('/admin/profile');
}
}