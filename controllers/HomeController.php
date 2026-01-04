<?php
/**
 * Home Controller
 * Gère les pages publiques
 */

class HomeController {
    
    private Book $bookModel;
    private Event $eventModel;
    private News $newsModel;
    private Announcement $announcementModel;
    private Category $categoryModel;
    
    public function __construct() {
        $this->bookModel = new Book();
        $this->eventModel = new Event();
        $this->newsModel = new News();
        $this->announcementModel = new Announcement();
        $this->categoryModel = new Category();
    }
    
    /**
     * Show homepage
     */
    public function index(): void {
        // Get carousel news
        $news = $this->newsModel->findAll(['status' => 'active'], 'display_order ASC', 5);
        
        // Get top books
        $topBooks = $this->bookModel->getTopBooks(6);
        
        // Get upcoming events
        $upcomingEvents = $this->eventModel->getUpcomingEvents(3);
        
        // Get active announcements
        $announcements = $this->announcementModel->findAll(['status' => 'active'], 'display_order ASC', 3);
        
        require_once ROOT_PATH . '/views/home/index.php';
    }
    
    /**
     * Show about page
     */
    public function about(): void {
        require_once ROOT_PATH . '/views/home/about.php';
    }
    
    /**
     * Show contact page
     */
    public function contact(): void {
        require_once ROOT_PATH . '/views/home/contact.php';
    }
    
    /**
     * Show all categories
     */
    public function categories(): void {
        // Get featured categories with book counts
        $sql = "SELECT c.*, COUNT(b.id) as book_count
                FROM categories c
                LEFT JOIN books b ON c.id = b.category_id AND b.status = 'active'
                WHERE c.is_featured = 1 AND c.status = 'active'
                GROUP BY c.id
                ORDER BY c.display_order ASC";
        
        $stmt = Database::query($sql);
        $featuredCategories = $stmt->fetchAll();
        
        // Get category tree
        $categoryTree = $this->categoryModel->getCategoryTree();
        
        require_once ROOT_PATH . '/views/home/categories.php';
    }
    
    /**
     * Show books by category
     */
    public function categoryBooks(): void {
        // Get category slug from URL
        $slug = sanitize($_GET['slug'] ?? '');
        
        if (!$slug) {
            Session::setFlash('error', 'Catégorie introuvable');
            redirect('/categories');
        }
        
        $category = $this->categoryModel->findBySlug($slug);
        
        if (!$category || $category['status'] !== 'active') {
            Session::setFlash('error', 'Catégorie introuvable');
            redirect('/categories');
        }
        
        // Get breadcrumb
        $breadcrumb = $this->categoryModel->getBreadcrumb($category['id']);
        
        // Get subcategories with book counts
        $sql = "SELECT c.*, COUNT(b.id) as book_count
                FROM categories c
                LEFT JOIN books b ON c.id = b.category_id AND b.status = 'active'
                WHERE c.parent_id = :parent_id AND c.status = 'active'
                GROUP BY c.id
                ORDER BY c.display_order ASC";
        
        $stmt = Database::query($sql, ['parent_id' => $category['id']]);
        $subcategories = $stmt->fetchAll();
        
        // Get books for this category
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        
        $filters = [
            'search' => isset($_GET['search']) ? sanitize($_GET['search']) : null,
        ];
        
        $filters = array_filter($filters, fn($v) => $v !== null && $v !== '');
        
        // Count total books in this category
        $sql = "SELECT COUNT(*) as total FROM books WHERE category_id = :category_id AND status = 'active'";
        $params = ['category_id' => $category['id']];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (title LIKE :search OR author LIKE :search)";
            $params['search'] = "%{$filters['search']}%";
        }
        
        $stmt = Database::query($sql, $params);
        $result = $stmt->fetch();
        $total = $result['total'];
        
        $pagination = paginate($total, $page);
        
        // Get books with details
        $sql = "SELECT b.*, 
                COALESCE(AVG(r.rating), 0) as avg_rating,
                COUNT(DISTINCT r.id) as total_reviews
                FROM books b
                LEFT JOIN reviews r ON b.id = r.book_id AND r.status = 'approved'
                WHERE b.category_id = :category_id AND b.status = 'active'";
        
        $params = ['category_id' => $category['id']];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (b.title LIKE :search OR b.author LIKE :search)";
            $params['search'] = "%{$filters['search']}%";
        }
        
        $sql .= " GROUP BY b.id ORDER BY b.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = Database::getInstance()->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':limit', $pagination['per_page'], PDO::PARAM_INT);
        $stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
        
        $stmt->execute();
        $books = $stmt->fetchAll();
        
        // Add book_count to category
        $category['book_count'] = $total;
        
        require_once ROOT_PATH . '/views/home/category_books.php';
    }
}