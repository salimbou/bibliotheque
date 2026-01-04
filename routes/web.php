<?php
/**
 * Web Routes - Configuration complète
 */

// Initialize router
$router = new Router();

// ============================================
// PUBLIC ROUTES
// ============================================

// Home
$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/contact', [HomeController::class, 'contact']);

// Categories
$router->get('/categories', [HomeController::class, 'categories']);
$router->get('/categories/*', [HomeController::class, 'categoryBooks']);

// Authentication
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// ============================================
// ADMIN ROUTES
// ============================================

$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);
$router->get('/admin/users', [AdminController::class, 'users']);
$router->post('/admin/users/activate', [AdminController::class, 'activateUser']);
$router->post('/admin/users/deactivate', [AdminController::class, 'deactivateUser']);
$router->post('/admin/users/delete', [AdminController::class, 'deleteUser']);
$router->get('/admin/stats', [AdminController::class, 'stats']);

// Admin Profile
$router->get('/admin/profile', [AdminController::class, 'profile']);
$router->post('/admin/profile/update', [AdminController::class, 'updateProfile']);
$router->post('/admin/profile/change-password', [AdminController::class, 'changePassword']);

// ============================================
// BIBLIOTHECAIRE ROUTES
// ============================================

$router->get('/bibliothecaire/dashboard', [BiblioController::class, 'dashboard']);

// Books management
$router->get('/bibliothecaire/books', [BiblioController::class, 'books']);
$router->get('/bibliothecaire/books/add', [BiblioController::class, 'showAddBook']);
$router->post('/bibliothecaire/books/add', [BiblioController::class, 'addBook']);
$router->get('/bibliothecaire/books/edit', [BiblioController::class, 'showEditBook']);
$router->post('/bibliothecaire/books/update', [BiblioController::class, 'updateBook']);
$router->post('/bibliothecaire/books/delete', [BiblioController::class, 'deleteBook']);

// Borrowings management
$router->get('/bibliothecaire/borrowings', [BiblioController::class, 'borrowings']);
$router->get('/bibliothecaire/borrowings/create', [BiblioController::class, 'showCreateBorrowing']);
$router->post('/bibliothecaire/borrowings/create', [BiblioController::class, 'createBorrowing']);
$router->post('/bibliothecaire/borrowings/return', [BiblioController::class, 'returnBook']);

// Moderation
$router->get('/bibliothecaire/moderate', [BiblioController::class, 'moderate']);
$router->post('/bibliothecaire/moderate/review', [BiblioController::class, 'moderateReview']);
$router->post('/bibliothecaire/moderate/comment', [BiblioController::class, 'moderateComment']);

// ============================================
// LECTEUR ROUTES
// ============================================

$router->get('/lecteur/dashboard', [LecteurController::class, 'dashboard']);
$router->get('/lecteur/books', [LecteurController::class, 'books']);
$router->get('/lecteur/books/details', [LecteurController::class, 'bookDetails']);
$router->get('/lecteur/borrowings', [LecteurController::class, 'borrowings']);

// Reviews and Comments
$router->post('/lecteur/reviews/add', [LecteurController::class, 'addReview']);
$router->post('/lecteur/comments/add', [LecteurController::class, 'addComment']);

// Profile
$router->get('/lecteur/profile', [LecteurController::class, 'profile']);
$router->post('/lecteur/profile/update', [LecteurController::class, 'updateProfile']);
$router->post('/lecteur/profile/change-password', [LecteurController::class, 'changePassword']);

// ============================================
// SCHOOL ROUTES (Espace Scolaire)
// ============================================

$router->get('/school', [SchoolController::class, 'index']);
$router->get('/school/level', [SchoolController::class, 'showLevel']);
$router->get('/school/exercises', [SchoolController::class, 'exercises']);
$router->get('/school/exercise', [SchoolController::class, 'exerciseDetails']);
$router->get('/school/download', [SchoolController::class, 'downloadExercise']);
$router->get('/school/download-solution', [SchoolController::class, 'downloadSolution']);

// ============================================
// SEARCH ROUTES
// ============================================

$router->get('/search', [SearchController::class, 'index']);
$router->get('/search/suggestions', [SearchController::class, 'suggestions']);
$router->get('/search/advanced', [SearchController::class, 'advanced']);

// ============================================
// ADS ROUTES
// ============================================

$router->get('/ads/track/{id}', [AdController::class, 'trackClick']);

// ============================================
// DISPATCH
// ============================================

return $router;