<?php
/**
 * School Controller
 * Gère les livres scolaires et exercices
 */

class SchoolController {
    
    private SchoolLevel $levelModel;
    private SchoolSubject $subjectModel;
    private Exercise $exerciseModel;
    private Book $bookModel;
    
    public function __construct() {
        $this->levelModel = new SchoolLevel();
        $this->subjectModel = new SchoolSubject();
        $this->exerciseModel = new Exercise();
        $this->bookModel = new Book();
    }
    
    /**
     * Show school homepage
     */
    public function index(): void {
        $levelsByCycle = $this->levelModel->getLevelsByCycle();
        $popularExercises = $this->exerciseModel->getPopularExercises(8);
        
        require_once ROOT_PATH . '/views/school/index.php';
    }
    
    /**
     * Show level page
     */
    public function showLevel(): void {
        $slug = sanitize($_GET['slug'] ?? '');
        
        if (!$slug) {
            Session::setFlash('error', 'Niveau introuvable');
            redirect('/school');
        }
        
        $level = $this->levelModel->findBySlug($slug);
        
        if (!$level || $level['status'] !== 'active') {
            Session::setFlash('error', 'Niveau introuvable');
            redirect('/school');
        }
        
        $levelDetails = $this->levelModel->getLevelWithCounts($level['id']);
        $subjects = $this->subjectModel->getSubjectsForLevel($level['id']);
        
        // Get books for this level
        $sql = "SELECT DISTINCT b.*, 
                COALESCE(AVG(r.rating), 0) as avg_rating
                FROM books b
                INNER JOIN book_school_levels bsl ON b.id = bsl.book_id
                LEFT JOIN reviews r ON b.id = r.book_id AND r.status = 'approved'
                WHERE bsl.school_level_id = :level_id AND b.status = 'active'
                GROUP BY b.id
                ORDER BY b.title ASC
                LIMIT 12";
        
        $stmt = Database::getInstance()->prepare($sql);
        $stmt->execute(['level_id' => $level['id']]);
        $books = $stmt->fetchAll();
        
        // Get exercises for this level
        $filters = ['level_id' => $level['id']];
        $exercises = $this->exerciseModel->getExercises($filters, 0, 8);
        
        require_once ROOT_PATH . '/views/school/level.php';
    }
    
    /**
     * Show exercises page
     */
    public function exercises(): void {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        
        $filters = [
            'level_id' => isset($_GET['level']) ? (int)$_GET['level'] : null,
            'subject_id' => isset($_GET['subject']) ? (int)$_GET['subject'] : null,
            'difficulty' => isset($_GET['difficulty']) ? sanitize($_GET['difficulty']) : null,
            'search' => isset($_GET['search']) ? sanitize($_GET['search']) : null,
        ];
        
        $filters = array_filter($filters, fn($v) => $v !== null && $v !== '');
        
        $total = $this->exerciseModel->countExercises($filters);
        $pagination = paginate($total, $page, 12);
        
        $exercises = $this->exerciseModel->getExercises($filters, $pagination['offset'], $pagination['per_page']);
        
        $levels = $this->levelModel->getAllLevels();
        $subjects = $this->subjectModel->getAllSubjects();
        
        require_once ROOT_PATH . '/views/school/exercises.php';
    }
    
    /**
     * Show exercise details
     */
    public function exerciseDetails(): void {
        $exerciseId = (int)($_GET['id'] ?? 0);
        
        if (!$exerciseId) {
            Session::setFlash('error', 'Exercice introuvable');
            redirect('/school/exercises');
        }
        
        $exercise = $this->exerciseModel->getExerciseDetails($exerciseId);
        
        if (!$exercise || $exercise['status'] !== 'active') {
            Session::setFlash('error', 'Exercice introuvable');
            redirect('/school/exercises');
        }
        
        // Increment view count
        $this->exerciseModel->incrementViewCount($exerciseId);
        
        // Get related exercises
        $filters = [
            'level_id' => $exercise['school_level_id'],
            'subject_id' => $exercise['subject_id']
        ];
        $relatedExercises = $this->exerciseModel->getExercises($filters, 0, 4);
        
        require_once ROOT_PATH . '/views/school/exercise_details.php';
    }
    
    /**
     * Download exercise file
     */
    public function downloadExercise(): void {
        $exerciseId = (int)($_GET['id'] ?? 0);
        
        if (!$exerciseId) {
            Session::setFlash('error', 'Exercice introuvable');
            redirect('/school/exercises');
        }
        
        $exercise = $this->exerciseModel->findById($exerciseId);
        
        if (!$exercise || $exercise['status'] !== 'active' || empty($exercise['file_path'])) {
            Session::setFlash('error', 'Fichier introuvable');
            redirect('/school/exercises');
        }
        
        $filePath = UPLOAD_PATH . '/exercises/' . $exercise['file_path'];
        
        if (!file_exists($filePath)) {
            Session::setFlash('error', 'Fichier introuvable sur le serveur');
            redirect('/school/exercise?id=' . $exerciseId);
        }
        
        // Increment download count
        $this->exerciseModel->incrementDownloadCount($exerciseId);
        
        // Force download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($exercise['file_path']) . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        
        readfile($filePath);
        exit;
    }
    
    /**
     * Download solution file
     */
    public function downloadSolution(): void {
        $exerciseId = (int)($_GET['id'] ?? 0);
        
        if (!$exerciseId) {
            Session::setFlash('error', 'Exercice introuvable');
            redirect('/school/exercises');
        }
        
        $exercise = $this->exerciseModel->findById($exerciseId);
        
        if (!$exercise || $exercise['status'] !== 'active' || !$exercise['has_solutions'] || empty($exercise['solutions_file'])) {
            Session::setFlash('error', 'Fichier de correction introuvable');
            redirect('/school/exercise?id=' . $exerciseId);
        }
        
        $filePath = UPLOAD_PATH . '/exercises/solutions/' . $exercise['solutions_file'];
        
        if (!file_exists($filePath)) {
            Session::setFlash('error', 'Fichier de correction introuvable sur le serveur');
            redirect('/school/exercise?id=' . $exerciseId);
        }
        
        // Force download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="correction_' . basename($exercise['solutions_file']) . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        
        readfile($filePath);
        exit;
    }
}