
<?php
/**
 * Security Helper Functions
 */

/**
 * Escape HTML output to prevent XSS
 */
function escape(?string $value): string {
    if ($value === null) {
        return '';
    }
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token field for forms
 */
function csrf_field(): string {
    $token = Session::generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . escape($token) . '">';
}

/**
 * Verify CSRF token from request
 */
function verify_csrf(): bool {
    $token = $_POST['csrf_token'] ?? '';
    return Session::verifyCsrfToken($token);
}

/**
 * Hash password securely
 */
function hash_password(string $password): string {
    return password_hash($password, HASH_ALGO, ['cost' => HASH_COST]);
}

/**
 * Verify password against hash
 */
function verify_password(string $password, string $hash): bool {
    return password_verify($password, $hash);
}

/**
 * Sanitize user input
 */
function sanitize(?string $value): string {
    if ($value === null) {
        return '';
    }
    return trim(strip_tags($value));
}

/**
 * Sanitize array of inputs
 */
function sanitize_array(array $data): array {
    return array_map(function($value) {
        return is_string($value) ? sanitize($value) : $value;
    }, $data);
}





