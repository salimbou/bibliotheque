<?php
/**
 * Bibliothecaire Controller
 * Handles librarian operations: books, borrowings, moderation
 */

class BiblioController {
    
    private Book $bookModel;
    private Borrowing $borrowingModel;
    private Review $reviewModel;
    private Comment $commentModel;
    private Event $eventModel;
    private User $userModel;
    
    public function __construct() {
        $this->bookModel = new Book();
        $this->borrowingModel = new Borrowing();
        $this->reviewModel = new Review();
        $this->commentModel = new Comment();
        $this->eventModel = new Event();
        $this->userModel = new User();
    }
    
    /**
     * Show librarian dashboard
     */
    public function dashboard(): void {
        RoleMiddleware::bibliothecaire();
        
        // Get statistics
        $bookStats = $this->bookModel->getStatistics();
        $borrowingStats = $this->borrowingModel->getStatistics();
        $eventStats = $this->eventModel->getStatistics();
        
        // Get pending items
        $pendingReviews = $this->reviewModel->count(['status' => 'pending']);
        $pendingComments = $this->commentModel->count(['status' => 'pending']);
        
        // Get overdue borrowings
        $this->borrowingModel->updateOverdueStatus();
        $overdueBorrowings = $this->borrowingModel->getOverdueBorrowings();
        
        require_once ROOT_PATH . '/views/bibliothecaire/dashboard.php';
    }
    
    /**
     * Show books list
     */
    public function books(): void {
        RoleMiddleware::bibliothecaire();
        
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
        
        require_once ROOT_PATH . '/views/bibliothecaire/books.php';
    }
    
    /**
     * Show add book form
     */
    public function showAddBook(): void {
        RoleMiddleware::bibliothecaire();
        require_once ROOT_PATH . '/views/bibliothecaire/add_book.php';
    }
    
    /**
     * Add new book
     */
    public function addBook(): void {
        RoleMiddleware::bibliothecaire();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/bibliothecaire/books');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/bibliothecaire/books/add');
        }
        
        // Sanitize inputs
        $data = [
            'isbn' => sanitize($_POST['isbn'] ?? ''),
            'title' => sanitize($_POST['title'] ?? ''),
            'author' => sanitize($_POST['author'] ?? ''),
            'publisher' => sanitize($_POST['publisher'] ?? ''),
            'publication_year' => sanitize($_POST['publication_year'] ?? ''),
            'category' => sanitize($_POST['category'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'total_copies' => (int)($_POST['total_copies'] ?? 1),
            'available_copies' => (int)($_POST['total_copies'] ?? 1),
            'created_by' => Session::getUserId()
        ];
        
        // Validation
        $errors = validate_required(['title', 'author'], $data);
        
        if (!empty($data['isbn']) && !validate_isbn($data['isbn'])) {
            $errors['isbn'] = "L'ISBN n'est pas valide";
        }
        
        if ($data['total_copies'] < 1) {
            $errors['total_copies'] = "Le nombre d'exemplaires doit être au moins 1";
        }
        
        // Handle cover image upload
        if (!empty($_FILES['cover_image']['name'])) {
            $imageErrors = validate_image($_FILES['cover_image']);
            if (!empty($imageErrors)) {
                $errors['cover_image'] = implode('<br>', $imageErrors);
            } else {
                $uploadedImage = upload_file($_FILES['cover_image'], 'books');
                if ($uploadedImage) {
                    $data['cover_image'] = $uploadedImage;
                } else {
                    $errors['cover_image'] = "Erreur lors du téléchargement de l'image";
                }
            }
        }
        
        if (!empty($errors)) {
            Session::set('book_errors', $errors);
            Session::set('book_data', $data);
            redirect('/bibliothecaire/books/add');
        }
        
        // Create book
        try {
            $bookId = $this->bookModel->create($data);
            
            if ($bookId) {
                Session::setFlash('success', 'Livre ajouté avec succès');
                redirect('/bibliothecaire/books');
            } else {
                Session::setFlash('error', 'Erreur lors de l\'ajout du livre');
                redirect('/bibliothecaire/books/add');
            }
            
        } catch (Exception $e) {
            error_log("Add book error: " . $e->getMessage());
            Session::setFlash('error', 'Une erreur est survenue');
            redirect('/bibliothecaire/books/add');
        }
    }
    
    /**
     * Show edit book form
     */
    public function showEditBook(): void {
        RoleMiddleware::bibliothecaire();
        
        $bookId = (int)($_GET['id'] ?? 0);
        $book = $this->bookModel->findById($bookId);
        
        if (!$book) {
            Session::setFlash('error', 'Livre introuvable');
            redirect('/bibliothecaire/books');
        }
        
        require_once ROOT_PATH . '/views/bibliothecaire/edit_book.php';
    }
    
    /**
     * Update book
     */
    public function updateBook(): void {
        RoleMiddleware::bibliothecaire();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/bibliothecaire/books');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/bibliothecaire/books');
        }
        
        $bookId = (int)($_POST['book_id'] ?? 0);
        $book = $this->bookModel->findById($bookId);
        
        if (!$book) {
            Session::setFlash('error', 'Livre introuvable');
            redirect('/bibliothecaire/books');
        }
        
        // Sanitize inputs
        $data = [
            'isbn' => sanitize($_POST['isbn'] ?? ''),
            'title' => sanitize($_POST['title'] ?? ''),
            'author' => sanitize($_POST['author'] ?? ''),
            'publisher' => sanitize($_POST['publisher'] ?? ''),
            'publication_year' => sanitize($_POST['publication_year'] ?? ''),
            'category' => sanitize($_POST['category'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'total_copies' => (int)($_POST['total_copies'] ?? 1),
        ];
        
        // Calculate available copies
        $borrowed = $book['total_copies'] - $book['available_copies'];
        $data['available_copies'] = max(0, $data['total_copies'] - $borrowed);
        
        // Validation
        $errors = validate_required(['title', 'author'], $data);
        
        if (!empty($data['isbn']) && !validate_isbn($data['isbn'])) {
            $errors['isbn'] = "L'ISBN n'est pas valide";
        }
        
        if ($data['total_copies'] < $borrowed) {
            $errors['total_copies'] = "Le nombre d'exemplaires ne peut pas être inférieur au nombre d'emprunts actifs ($borrowed)";
        }
        
        // Handle cover image upload
        if (!empty($_FILES['cover_image']['name'])) {
            $imageErrors = validate_image($_FILES['cover_image']);
            if (!empty($imageErrors)) {
                $errors['cover_image'] = implode('<br>', $imageErrors);
            } else {
                // Delete old image if exists
                if (!empty($book['cover_image'])) {
                    delete_file($book['cover_image']);
                }
                
                $uploadedImage = upload_file($_FILES['cover_image'], 'books');
                if ($uploadedImage) {
                    $data['cover_image'] = $uploadedImage;
                } else {
                    $errors['cover_image'] = "Erreur lors du téléchargement de l'image";
                }
            }
        }
        
        if (!empty($errors)) {
            Session::set('book_errors', $errors);
            redirect('/bibliothecaire/books/edit?id=' . $bookId);
        }
        
        // Update book
        try {
            if ($this->bookModel->update($bookId, $data)) {
                Session::setFlash('success', 'Livre modifié avec succès');
            } else {
                Session::setFlash('error', 'Aucune modification effectuée');
            }
            redirect('/bibliothecaire/books');
            
        } catch (Exception $e) {
            error_log("Update book error: " . $e->getMessage());
            Session::setFlash('error', 'Une erreur est survenue');
            redirect('/bibliothecaire/books/edit?id=' . $bookId);
        }
    }
    
    /**
     * Delete book
     */
    public function deleteBook(): void {
        RoleMiddleware::bibliothecaire();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/bibliothecaire/books');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/bibliothecaire/books');
        }
        
        $bookId = (int)($_POST['book_id'] ?? 0);
        $book = $this->bookModel->findById($bookId);
        
        if (!$book) {
            Session::setFlash('error', 'Livre introuvable');
            redirect('/bibliothecaire/books');
        }
        
        // Check if book has active borrowings
        $activeBorrowings = $this->borrowingModel->count([
            'book_id' => $bookId,
            'status' => 'active'
        ]);
        
        if ($activeBorrowings > 0) {
            Session::setFlash('error', 'Impossible de supprimer un livre avec des emprunts actifs');
            redirect('/bibliothecaire/books');
        }
        
        try {
            // Delete cover image
            if (!empty($book['cover_image'])) {
                delete_file($book['cover_image']);
            }
            
            if ($this->bookModel->delete($bookId)) {
                Session::setFlash('success', 'Livre supprimé avec succès');
            } else {
                Session::setFlash('error', 'Erreur lors de la suppression');
            }
            
        } catch (Exception $e) {
            Session::setFlash('error', 'Impossible de supprimer ce livre (données liées existantes)');
        }
        
        redirect('/bibliothecaire/books');
    }
    
    /**
     * Show borrowings list
     */
    public function borrowings(): void {
        RoleMiddleware::bibliothecaire();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        
        $status = isset($_GET['status']) ? sanitize($_GET['status']) : null;
        
        // Get filters
        $filters = [];
        if ($status) {
            $filters['status'] = $status;
        }
        
        // Get total count
        $total = $this->borrowingModel->count($filters);
        $pagination = paginate($total, $page);
        
        // Get borrowings
        $borrowings = $this->borrowingModel->getAllBorrowingsWithDetails($pagination['offset'], $pagination['per_page'], $filters);
        
        require_once ROOT_PATH . '/views/bibliothecaire/borrowings.php';
    }
    
    /**
     * Show create borrowing form
     */
    public function showCreateBorrowing(): void {
        RoleMiddleware::bibliothecaire();
        
        // Get active users (lecteurs)
        $users = $this->userModel->getUsersByRole('lecteur');
        $users = array_filter($users, fn($u) => $u['status'] === 'active');
        
        // Get available books
        $books = $this->bookModel->findAll(['status' => 'active'], 'title ASC');
        $books = array_filter($books, fn($b) => $b['available_copies'] > 0);
        
        require_once ROOT_PATH . '/views/bibliothecaire/create_borrowing.php';
    }
    
    /**
     * Create borrowing
     */
    public function createBorrowing(): void {
        RoleMiddleware::bibliothecaire();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/bibliothecaire/borrowings');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/bibliothecaire/borrowings/create');
        }
        
        $userId = (int)($_POST['user_id'] ?? 0);
        $bookId = (int)($_POST['book_id'] ?? 0);
        $daysToReturn = (int)($_POST['days_to_return'] ?? 14);
        
        // Validation
        if (!$userId || !$bookId) {
            Session::setFlash('error', 'Données invalides');
            redirect('/bibliothecaire/borrowings/create');
        }
        
        // Check if user already has this book
        if ($this->borrowingModel->hasActiveBorrowing($userId, $bookId)) {
            Session::setFlash('error', 'Cet utilisateur a déjà emprunté ce livre');
            redirect('/bibliothecaire/borrowings/create');
        }
        
        // Create borrowing
        $borrowingId = $this->borrowingModel->createBorrowing(
            $userId,
            $bookId,
            Session::getUserId(),
            $daysToReturn
        );
        
        if ($borrowingId) {
            Session::setFlash('success', 'Emprunt créé avec succès');
        } else {
            Session::setFlash('error', 'Erreur lors de la création de l\'emprunt');
        }
        
        redirect('/bibliothecaire/borrowings');
    }
    
    /**
     * Return book
     */
    public function returnBook(): void {
        RoleMiddleware::bibliothecaire();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/bibliothecaire/borrowings');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/bibliothecaire/borrowings');
        }
        
        $borrowingId = (int)($_POST['borrowing_id'] ?? 0);
        
        if ($this->borrowingModel->returnBook($borrowingId)) {
            Session::setFlash('success', 'Livre retourné avec succès');
        } else {
            Session::setFlash('error', 'Erreur lors du retour du livre');
        }
        
        redirect('/bibliothecaire/borrowings');
    }
    
    /**
     * Show moderation page
     */
    public function moderate(): void {
        RoleMiddleware::bibliothecaire();
        
        // Get pending reviews and comments
        $pendingReviews = $this->reviewModel->getPendingReviews(0, 20);
        $pendingComments = $this->commentModel->getPendingComments(0, 20);
        
        require_once ROOT_PATH . '/views/bibliothecaire/moderate.php';
    }
    
    /**
     * Moderate review
     */
    public function moderateReview(): void {
        RoleMiddleware::bibliothecaire();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/bibliothecaire/moderate');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/bibliothecaire/moderate');
        }
        
        $reviewId = (int)($_POST['review_id'] ?? 0);
        $status = sanitize($_POST['status'] ?? '');
        
        if ($this->reviewModel->moderateReview($reviewId, $status, Session::getUserId())) {
            Session::setFlash('success', 'Avis modéré avec succès');
        } else {
            Session::setFlash('error', 'Erreur lors de la modération');
        }
        
        redirect('/bibliothecaire/moderate');
    }
    
    /**
     * Moderate comment
     */
    public function moderateComment(): void {
        RoleMiddleware::bibliothecaire();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/bibliothecaire/moderate');
        }
        
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/bibliothecaire/moderate');
        }
        
        $commentId = (int)($_POST['comment_id'] ?? 0);
        $status = sanitize($_POST['status'] ?? '');
        
        if ($this->commentModel->moderateComment($commentId, $status, Session::getUserId())) {
            Session::setFlash('success', 'Commentaire modéré avec succès');
        } else {
            Session::setFlash('error', 'Erreur lors de la modération');
        }
        
        redirect('/bibliothecaire/moderate');
    }
}