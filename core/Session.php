<?php
/**
 * Session Management Class
 * Handles secure session operations
 */

class Session {
    
    /**
     * Initialize session with security settings
     */
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS
            ini_set('session.cookie_samesite', 'Strict');
            
            session_name(SESSION_NAME);
            session_start();
            
            // Regenerate session ID periodically for security
            if (!isset($_SESSION['created'])) {
                self::regenerate();
            } elseif (time() - $_SESSION['created'] > 1800) {
                self::regenerate();
            }
        }
    }
    
    /**
     * Regenerate session ID
     */
    public static function regenerate(): void {
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
    
    /**
     * Set session value
     */
    public static function set(string $key, $value): void {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Get session value
     */
    public static function get(string $key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Check if session key exists
     */
    public static function has(string $key): bool {
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove session key
     */
    public static function remove(string $key): void {
        unset($_SESSION[$key]);
    }
    
    /**
     * Destroy session completely
     */
    public static function destroy(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }
            
            session_destroy();
        }
    }
    
    /**
     * Generate CSRF token
     */
    public static function generateCsrfToken(): string {
        if (!self::has(CSRF_TOKEN_NAME)) {
            self::set(CSRF_TOKEN_NAME, bin2hex(random_bytes(32)));
        }
        return self::get(CSRF_TOKEN_NAME);
    }
    
    /**
     * Verify CSRF token
     */
    public static function verifyCsrfToken(string $token): bool {
        return self::has(CSRF_TOKEN_NAME) && hash_equals(self::get(CSRF_TOKEN_NAME), $token);
    }
    
    /**
     * Set flash message
     */
    public static function setFlash(string $type, string $message): void {
        $_SESSION['flash'][$type] = $message;
    }
    
    /**
     * Get and clear flash message
     */
    public static function getFlash(string $type): ?string {
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        return null;
    }
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn(): bool {
        return self::has('user_id') && self::has('user_role');
    }
    
    /**
     * Get current user ID
     */
    public static function getUserId(): ?int {
        return self::get('user_id');
    }
    
    /**
     * Get current user role
     */
    public static function getUserRole(): ?string {
        return self::get('user_role');
    }
    
    /**
     * Check if user has specific role
     */
    public static function hasRole(string $role): bool {
        return self::getUserRole() === $role;
    }
    
    /**
     * Login user
     */
    public static function login(array $user): void {
        self::regenerate();
        self::set('user_id', $user['id']);
        self::set('user_email', $user['email']);
        self::set('user_role', $user['role']);
        self::set('user_name', $user['first_name'] . ' ' . $user['last_name']);
    }
    
    /**
     * Logout user
     */
    public static function logout(): void {
        self::destroy();
    }
}

?>