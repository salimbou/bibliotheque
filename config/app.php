<?php
// Application configuration

// Timezone
date_default_timezone_set('Europe/Paris');

// Application Settings
define('APP_NAME', 'Bibliothèque');
define('APP_URL', 'http://localhost/bibliotheque');
define('APP_ENV', 'development'); // development or production

// Path Constants
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/assets/images/uploads');

// Session Configuration
define('SESSION_LIFETIME', 3600); // 1 hour
define('SESSION_NAME', 'BIBLIOTHEQUE_SESSION');

// Security Settings
define('CSRF_TOKEN_NAME', 'csrf_token');
define('HASH_ALGO', PASSWORD_BCRYPT);
define('HASH_COST', 10);

// Pagination
define('ITEMS_PER_PAGE', 10);

// File Upload Settings
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Error Reporting
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}


?>