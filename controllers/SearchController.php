<?php
/**
 * Search Controller
 * Gère la recherche avancée globale
 */

class SearchController {
    
    private Search $searchModel;
    private Category $categoryModel;
    private SchoolLevel $levelModel;
    private SchoolSubject $subjectModel;
    
    public function __construct() {
        $this->searchModel = new Search();
        $this->categoryModel = new Category();
        $this->levelModel = new SchoolLevel();
        $this->subjectModel = new SchoolSubject();
    }
    
    /**
     * Global search page
     */
    public function index(): void {
        $query = sanitize($_GET['q'] ?? '');
        $type = sanitize($_GET['type'] ?? 'all');
        $page = max(1, (int)($_GET['page'] ?? 1));
        
        // Get filters
        $filters = [
            'category_id' => !empty($_GET['category']) ? (int)$_GET['category'] : null,
            'level_id' => !empty($_GET['level']) ? (int)$_GET['level'] : null,
            'subject_id' => !empty($_GET['subject']) ? (int)$_GET['subject'] : null,
            'difficulty' => !empty($_GET['difficulty']) ? sanitize($_GET['difficulty']) : null,
            'available_only' => !empty($_GET['available']),
            'sort' => sanitize($_GET['sort'] ?? 'relevance'),
            'author' => sanitize($_GET['author'] ?? ''),
        ];
        
        $filters = array_filter($filters, fn($v) => $v !== null && $v !== '');
        
        $results = [
            'books' => [],
            'exercises' => [],
            'total_books' => 0,
            'total_exercises' => 0
        ];
        
        // Search books
        if ($type === 'all' || $type === 'books') {
            $results['total_books'] = $this->searchModel->countBookResults($query, $filters);
            $pagination = paginate($results['total_books'], $page, 12);
            $results['books'] = $this->searchModel->searchBooks($query, $filters, $pagination['offset'], $pagination['per_page']);
            $results['pagination_books'] = $pagination;
        }
        
        // Search exercises
        if ($type === 'all' || $type === 'exercises') {
            $results['total_exercises'] = $this->searchModel->countExerciseResults($query, $filters);
            $pagination = paginate($results['total_exercises'], $page, 12);
            $results['exercises'] = $this->searchModel->searchExercises($query, $filters, $pagination['offset'], $pagination['per_page']);
            $results['pagination_exercises'] = $pagination;
        }
        
        // Save search history
        if (!empty($query)) {
            $totalResults = $results['total_books'] + $results['total_exercises'];
            $this->searchModel->saveSearchHistory($query, $type, $filters, $totalResults, Session::getUserId());
        }
        
        // Get filter options
        $categories = $this->categoryModel->getAllCategoriesWithCounts();
        $levels = $this->levelModel->getAllLevels();
        $subjects = $this->subjectModel->getAllSubjects();
        $popularSearches = $this->searchModel->getPopularSearches(10);
        
        require_once ROOT_PATH . '/views/search/index.php';
    }
    
    /**
     * AJAX search suggestions
     */
    public function suggestions(): void {
        header('Content-Type: application/json');
        
        $query = sanitize($_GET['q'] ?? '');
        
        if (strlen($query) < 2) {
            echo json_encode([]);
            exit;
        }
        
        $suggestions = $this->searchModel->getSearchSuggestions($query, 10);
        
        echo json_encode($suggestions);
        exit;
    }
    
    /**
     * Advanced search page
     */
    public function advanced(): void {
        $categories = $this->categoryModel->getAllCategoriesWithCounts();
        $levels = $this->levelModel->getAllLevels();
        $subjects = $this->subjectModel->getAllSubjects();
        
        require_once ROOT_PATH . '/views/search/advanced.php';
    }
}