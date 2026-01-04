<?php
/**
 * Lecteur Controller
 * Handles reader operations: view books, borrowings, reviews
 */

class LecteurController {
    
    private Book $bookModel;
    private Borrowing $borrowingModel;
    private Review $reviewModel;
    private Comment $commentModel;
    private Reaction $reactionModel;
    private User $userModel;
    
    public function __construct() {
        $this->bookModel = new Book();
        $this->borrowingModel = new Borrowing();
        $this->reviewModel = new Review();
        $this->commentModel = new Comment();
        $this->reactionModel = new Reaction();
        $this->userModel = new User();
    }
    
    /**
     * Show reader dashboard
     */
    public function dashboard(): void {
        RoleMiddleware::lecteur();
        
        $userId = Session::getUserId();
        
        // Get user's active borrowings
        $activeBorrowings = $this->borrowingModel->getUserBorrowings($userId, 'active');
        
        // Get user's overdue borrowings
        $overdueBorrowings = $this->borrowingModel->getUserBorrowings($userId, 'overdue');
        
        // Get user's reviews
        $reviews = $this->reviewModel->getUserReviews($userId);
        
        // Get borrowing stats
        $totalBorrowings = $this->borrowingModel->getUserBorrowingCount($userId);
        $returnedCount = $this->borrowingModel->getUserBorrowingCount($userId, 'returned');
        
        require_once ROOT_PATH . '/views/lecteur/dashboard.php';
    }
    
    /**
     * Show books catalog
     */
    public function books(): void {
        RoleMiddleware::lecteur();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        
        $search = isset($_GET['search']) ? sanitize($_GET['search']) : null;
        $category = isset($_GET['category']) ? sanitize($_GET['category']) : null;
        
        // Get filters
        $filters = [];
        if ($search) {
            $filters['search'] = $search;
        }
        if ($category) {
            $filters['category'] = $category;
        }
        
        // Get total count
        $total = $this->bookModel->count(['status' => 'active']);
        $pagination = paginate($total, $page);
        
        // Get books
        $books = $this->bookModel->getPaginatedBooks($pagination['offset'], $pagination['per_page'], $filters);
        
        // Get categories
        $categories = $this->bookModel->getCategories();
        
        require_once ROOT_PATH . '/views/lecteur/books.php';
    }
    
    /**
     * Show book details
     */
    public function bookDetails(): void {
        RoleMiddleware::lecteur();
        
        $bookId = (int)($_GET['id'] ?? 0);
        $book = $this->bookModel->getBookDetails($bookId);
        
        if (!$book || $book['status'] !== 'active') {
            Session::setFlash('error', 'Livre introuvable');
            redirect('/lecteur/books');
        }
        
        // Get reviews
        $reviews = $this->reviewModel->getBookReviews($bookId);
        
        // Get comments
        $comments = $this->commentModel->getComments('book', $bookId);
        
        // Check if user has reviewed this book
        $userId = Session::getUserId();
        $hasReviewed = $this->reviewModel->hasUserReviewed($bookId, $userId);
        
        // Check if user has active borrowing
        $hasActiveBorrowing = $this->borrowingModel->hasActiveBorrowing($userId, $bookId);
        
        require_once ROOT_PATH . '/views/lecteur/book_details.php';
    }
    
    /**
     * Show user's borrowings
     */
    public function borrowings(): void {
        RoleMiddleware::lecteur();
        
        $userId = Session::getUserId();
        $status = isset($_GET['status']) ? sanitize($_GET['status']) : null;
        
        $borrowings = $this->borrowingModel->getUserBorrowings($userId, $status);
        
        require_once ROOT_PATH . '/views/lecteur/borrowings.php';
    }
    
    /**
     * Add review
     */
    public function addReview(): void {
        RoleMiddleware::lecteur();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/lecteur/books');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/lecteur/books');
        }
        
        $bookId = (int)($_POST['book_id'] ?? 0);
        $rating = (int)($_POST['rating'] ?? 0);
        $reviewText = sanitize($_POST['review_text'] ?? '');
        
        // Validation
        if ($rating < 1 || $rating > 5) {
            Session::setFlash('error', 'Note invalide (1-5)');
            redirect('/lecteur/books/details?id=' . $bookId);
        }
        
        if (empty($reviewText)) {
            Session::setFlash('error', 'Le texte de l\'avis est obligatoire');
            redirect('/lecteur/books/details?id=' . $bookId);
        }
        
        $userId = Session::getUserId();
        
        // Create review
        $reviewId = $this->reviewModel->createReview($bookId, $userId, $rating, $reviewText);
        
        if ($reviewId) {
            Session::setFlash('success', 'Votre avis a été soumis et sera modéré prochainement');
        } else {
            Session::setFlash('error', 'Vous avez déjà donné votre avis sur ce livre');
        }
        
        redirect('/lecteur/books/details?id=' . $bookId);
    }
    
    /**
     * Add comment
     */
    public function addComment(): void {
        RoleMiddleware::lecteur();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/lecteur/books');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/lecteur/books');
        }
        
        $type = sanitize($_POST['type'] ?? '');
        $id = (int)($_POST['id'] ?? 0);
        $commentText = sanitize($_POST['comment_text'] ?? '');
        
        // Validation
        if (!in_array($type, ['book', 'event'])) {
            Session::setFlash('error', 'Type invalide');
            redirect('/lecteur/books');
        }
        
        if (empty($commentText)) {
            Session::setFlash('error', 'Le commentaire ne peut pas être vide');
            redirect('/lecteur/books/details?id=' . $id);
        }
        
        $userId = Session::getUserId();
        
        // Create comment
        $commentId = $this->commentModel->createComment($type, $id, $userId, $commentText);
        
        if ($commentId) {
            Session::setFlash('success', 'Votre commentaire a été soumis et sera modéré prochainement');
        } else {
            Session::setFlash('error', 'Erreur lors de l\'ajout du commentaire');
        }
        
        redirect('/lecteur/books/details?id=' . $id);
    }
    
    /**
     * Show profile
     */
    public function profile(): void {
        RoleMiddleware::lecteur();
        
        $userId = Session::getUserId();
        $user = $this->userModel->findById($userId);
        
        require_once ROOT_PATH . '/views/lecteur/profile.php';
    }
    
    /**
     * Update profile
     */
    public function updateProfile(): void {
        RoleMiddleware::lecteur();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/lecteur/profile');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/lecteur/profile');
        }
        
        $userId = Session::getUserId();
        
        $data = [
            'first_name' => sanitize($_POST['first_name'] ?? ''),
            'last_name' => sanitize($_POST['last_name'] ?? ''),
            'phone' => sanitize($_POST['phone'] ?? ''),
            'address' => sanitize($_POST['address'] ?? ''),
            'created_at' => sanitize($_POST['created_at'] ?? ''),
        ];
        
        // Validation
        $errors = validate_required(['first_name', 'last_name'], $data);
        
        if (!empty($data['phone']) && !validate_phone($data['phone'])) {
            $errors['phone'] = "Le numéro de téléphone n'est pas valide";
        }
        
        if (!empty($errors)) {
            Session::set('profile_errors', $errors);
            redirect('/lecteur/profile');
        }
        
        // Update profile
        if ($this->userModel->updateProfile($userId, $data)) {
            // Update session name
            Session::set('first_name', $data['first_name']);
            Session::set('last_name', $data['last_name'] );
            Session::setFlash('success', 'Profil mis à jour avec succès');
        } else {
            Session::setFlash('error', 'Erreur lors de la mise à jour');
        }
        
        redirect('/lecteur/profile');
    }
    
    /**
     * Change password
     */
    public function changePassword(): void {
        RoleMiddleware::lecteur();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/lecteur/profile');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/lecteur/profile');
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
            redirect('/lecteur/profile');
        }
        
        // Validate new password
        $passwordErrors = validate_password($newPassword);
        if (!empty($passwordErrors)) {
            Session::setFlash('error', implode('<br>', $passwordErrors));
            redirect('/lecteur/profile');
        }
        
        // Check confirmation
        if ($newPassword !== $confirmPassword) {
            Session::setFlash('error', 'Les mots de passe ne correspondent pas');
            redirect('/lecteur/profile');
        }
        
        // Update password
        if ($this->userModel->changePassword($userId, $newPassword)) {
            Session::setFlash('success', 'Mot de passe modifié avec succès');
        } else {
            Session::setFlash('error', 'Erreur lors de la modification du mot de passe');
        }
        
        redirect('/lecteur/profile');
    }
}