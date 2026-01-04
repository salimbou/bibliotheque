<?php
require_once ROOT_PATH . '/core/Session.php'; 
/**
 * General Helper Functions
 */

/**
 * Redirect to URL
 */
function redirect(string $path): void {
    header("Location: " . APP_URL . $path);
    exit;
}

/**
 * Generate URL
 */
function url(string $path = ''): string {
    return APP_URL . $path;
}

/**
 * Display flash message
 */
function flash(string $type): ?string {
    return Session::getFlash($type);
}

/**
 * Display all flash messages
 */
function display_flash(): void {
    $types = ['success', 'error', 'warning', 'info'];
    foreach ($types as $type) {
        $message = flash($type);
        if ($message) {
            $alertType = match($type) {
                'error' => 'danger',
                default => $type
            };
            echo '<div class="alert alert-' . $alertType . ' alert-dismissible fade show" role="alert">';
            echo escape($message);
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            echo '</div>';
        }
    }
}

/**
 * Format date
 */
function format_date(?string $date, string $format = 'd/m/Y'): string {
    if (empty($date)) {
        return '-';
    }
    return date($format, strtotime($date));
}

/**
 * Format datetime
 */
function format_datetime(?string $datetime, string $format = 'd/m/Y H:i'): string {
    if (empty($datetime)) {
        return '-';
    }
    return date($format, strtotime($datetime));
}

/**
 * Truncate text
 */
function truncate(?string $text, int $length = 100, string $suffix = '...'): string {
    if (empty($text)) {
        return '';
    }
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . $suffix;
}

/**
 * Get asset URL
 */
function asset(string $path): string {
    return APP_URL . '/assets/' . $path;
}

/**
 * Upload file
 */
function upload_file(array $file, string $directory = 'books'): ?string {
    $uploadDir = UPLOAD_PATH . '/' . $directory;
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . '/' . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $directory . '/' . $filename;
    }
    
    return null;
}

/**
 * Delete file
 */
function delete_file(?string $path): bool {
    if (empty($path)) {
        return false;
    }
    $filepath = UPLOAD_PATH . '/' . $path;
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    return false;
}

/**
 * Check if user is authenticated
 */
function is_authenticated(): bool {
    return Session::isLoggedIn();
}

/**
 * Check if user has role
 */
function has_role(string $role): bool {
    return Session::hasRole($role);
}

/**
 * Get current user
 */
function current_user(): ?array {
    if (!is_authenticated()) {
        return null;
    }
    
    return [
        'id' => Session::getUserId(),
        'email' => Session::get('user_email'),
        'role' => Session::getUserRole(),
        'name' => Session::get('user_name')
    ];
}

/**
 * Pagination helper
 */
function paginate(int $total, int $page = 1, int $perPage = ITEMS_PER_PAGE): array {
    $totalPages = ceil($total / $perPage);
    $page = max(1, min($page, $totalPages));
    $offset = ($page - 1) * $perPage;
    
    return [
        'total' => $total,
        'per_page' => $perPage,
        'current_page' => $page,
        'total_pages' => $totalPages,
        'offset' => $offset,
        'has_prev' => $page > 1,
        'has_next' => $page < $totalPages
    ];
}