<?php
/**
 * Authentication Controller
 * Handles login, registration, and logout
 */

class AuthController {
    
    private User $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Show login page
     */
    public function showLogin(): void {
        AuthMiddleware::guest();
        require_once ROOT_PATH . '/views/auth/login.php';
    }
    
    /**
     * Process login
     */
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login');
        }
        
        // Verify CSRF token
        if (!verify_csrf()) {
            Session::setFlash('error', 'Token de sécurité invalide');
            redirect('/login');
        }
        
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validation
        $errors = [];
        
        if (empty($email)) {
$errors[] = "L'email est obligatoire";
} elseif (!validate_email($email)) {
$errors[] = "L'email n'est pas valide";
}
if (empty($password)) {
        $errors[] = "Le mot de passe est obligatoire";
    }
    
    if (!empty($errors)) {
        Session::setFlash('error', implode('<br>', $errors));
        redirect('/login');
    }
    
    // Verify credentials
    $user = $this->userModel->verifyCredentials($email, $password);
    
    if ($user === null) {
        Session::setFlash('error', 'Email ou mot de passe incorrect, ou compte non activé');
        redirect('/login');
    }
    
    // Login successful
    Session::login($user);
    Session::setFlash('success', 'Bienvenue ' . escape($user['first_name']) . ' !');
    
    // Redirect based on role
    redirect('/' . $user['role'] . '/dashboard');
}

/**
 * Show registration page
 */
public function showRegister(): void {
    AuthMiddleware::guest();
    require_once ROOT_PATH . '/views/auth/register.php';
}

/**
 * Process registration
 */
public function register(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('/register');
    }
    
    // Verify CSRF token
    if (!verify_csrf()) {
        Session::setFlash('error', 'Token de sécurité invalide');
        redirect('/register');
    }
    
    // Sanitize inputs
    $data = [
        'email' => sanitize($_POST['email'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'password_confirm' => $_POST['password_confirm'] ?? '',
        'first_name' => sanitize($_POST['first_name'] ?? ''),
        'last_name' => sanitize($_POST['last_name'] ?? ''),
        'phone' => sanitize($_POST['phone'] ?? ''),
        'address' => sanitize($_POST['address'] ?? ''),
        'role' => 'lecteur'
    ];
    
    // Validation
    $errors = [];
    
    // Required fields
    $required = validate_required(['email', 'password', 'password_confirm', 'first_name', 'last_name'], $data);
    $errors = array_merge($errors, $required);
    
    // Email validation
    if (!empty($data['email']) && !validate_email($data['email'])) {
        $errors['email'] = "L'email n'est pas valide";
    }
    
    // Check if email exists
    if (!empty($data['email']) && $this->userModel->findByEmail($data['email'])) {
        $errors['email'] = "Cet email est déjà utilisé";
    }
    
    // Password validation
    if (!empty($data['password'])) {
        $passwordErrors = validate_password($data['password']);
        if (!empty($passwordErrors)) {
            $errors['password'] = implode('<br>', $passwordErrors);
        }
    }
    
    // Password confirmation
    if ($data['password'] !== $data['password_confirm']) {
        $errors['password_confirm'] = "Les mots de passe ne correspondent pas";
    }
    
    // Phone validation (optional)
    if (!empty($data['phone']) && !validate_phone($data['phone'])) {
        $errors['phone'] = "Le numéro de téléphone n'est pas valide";
    }
    
    if (!empty($errors)) {
        Session::set('registration_errors', $errors);
        Session::set('registration_data', $data);
        redirect('/register');
    }
    
    // Create user
    unset($data['password_confirm']);
    
    try {
        $userId = $this->userModel->createUser($data);
        
        if ($userId) {
            Session::setFlash('success', 'Inscription réussie ! Votre compte sera activé par un administrateur.');
            redirect('/login');
        } else {
            Session::setFlash('error', 'Une erreur est survenue lors de l\'inscription');
            redirect('/register');
        }
        
    } catch (Exception $e) {
        error_log("Registration error: " . $e->getMessage());
        Session::setFlash('error', 'Une erreur est survenue lors de l\'inscription');
        redirect('/register');
    }
}

/**
 * Logout
 */
public function logout(): void {
    Session::logout();
    Session::setFlash('success', 'Vous avez été déconnecté avec succès');
    redirect('/login');
}
}