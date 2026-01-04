<?php
/**
 * Application Entry Point
 * Bootstraps the application and handles all requests
 */

// Start output buffering
ob_start();

// Define root path
//define('ROOT_PATH', dirname(__DIR__));

// Load configuration
require_once dirname(__DIR__) . '/config/app.php';

// Autoloader for classes
spl_autoload_register(function ($class) {
    $paths = [
        ROOT_PATH . '/core/',
        ROOT_PATH . '/models/',
        ROOT_PATH . '/controllers/',
        ROOT_PATH . '/middleware/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Load helper functions
require_once ROOT_PATH . '/helpers/security.php';
require_once ROOT_PATH . '/helpers/validation.php';
require_once ROOT_PATH . '/helpers/helpers.php';

// Initialize session
Session::start();

// Load and dispatch routes
$router = require_once ROOT_PATH . '/routes/web.php';
$router->dispatch();

// Flush output buffer
ob_end_flush();