<?php
/**
 * Simple Router
 * Maps URLs to controller actions
 */

class Router {
    
    private array $routes = [];
    
    /**
     * Add GET route
     */
    public function get(string $path, $handler): void {
        $this->routes['GET'][$path] = $handler;
    }
    
    /**
     * Add POST route
     */
    public function post(string $path, $handler): void {
        $this->routes['POST'][$path] = $handler;
    }
    
    /**
     * Dispatch request to appropriate handler
     */
    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path if application is in subdirectory
        $basePath = parse_url(APP_URL, PHP_URL_PATH);
        if ($basePath && $basePath !== '/') {
            $path = substr($path, strlen($basePath));
        }
        
        // Ensure path starts with /
        if (!$path || $path[0] !== '/') {
            $path = '/' . $path;
        }
        
        // Find exact match first
        if (isset($this->routes[$method][$path])) {
            $this->executeHandler($this->routes[$method][$path]);
            return;
        }
        
        // Try to match dynamic routes
        foreach ($this->routes[$method] ?? [] as $routePath => $handler) {
            // Handle wildcard routes like /categories/*
            if (strpos($routePath, '*') !== false) {
                $pattern = str_replace('*', '([^/]+)', $routePath);
                $pattern = '#^' . $pattern . '$#';
                
                if (preg_match($pattern, $path, $matches)) {
                    // Store the matched parameter
                    if (isset($matches[1])) {
                        $_GET['slug'] = $matches[1];
                    }
                    $this->executeHandler($handler);
                    return;
                }
            }
            
            // Handle routes with parameters like /ads/click/{id}
            if (strpos($routePath, '{') !== false) {
                $pattern = preg_replace('/\{[^\}]+\}/', '([^/]+)', $routePath);
                $pattern = '#^' . $pattern . '$#';
                
                if (preg_match($pattern, $path, $matches)) {
                    // Extract parameter names from route
                    preg_match_all('/\{([^\}]+)\}/', $routePath, $paramNames);
                    
                    // Store matched parameters
                    foreach ($paramNames[1] as $index => $paramName) {
                        if (isset($matches[$index + 1])) {
                            $_GET[$paramName] = $matches[$index + 1];
                        }
                    }
                    
                    $this->executeHandler($handler);
                    return;
                }
            }
        }
        
        // 404 Not Found
        $this->notFound();
    }
    
    /**
     * Execute route handler
     */
    private function executeHandler($handler): void {
        if (is_callable($handler)) {
            call_user_func($handler);
        } elseif (is_array($handler) && count($handler) === 2) {
            [$controller, $method] = $handler;
            $controllerInstance = new $controller();
            $controllerInstance->$method();
        }
    }
    
    /**
     * Match route pattern
     */
    private function matchRoute(string $pattern, string $path): bool {
        return $pattern === $path;
    }
    
    /**
     * 404 Not Found handler
     */
    private function notFound(): void {
        http_response_code(404);
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 - Page Non Trouvée</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                }
                .error-container {
                    text-align: center;
                    color: white;
                }
                .error-code {
                    font-size: 150px;
                    font-weight: bold;
                    text-shadow: 4px 4px 8px rgba(0,0,0,0.3);
                }
                .error-message {
                    font-size: 24px;
                    margin-bottom: 30px;
                }
            </style>
        </head>
        <body>
            <div class="error-container">
                <div class="error-code">404</div>
                <div class="error-message">Page Non Trouvée</div>
                <p class="mb-4">La page que vous recherchez n'existe pas ou a été déplacée.</p>
                <a href="<?php echo APP_URL; ?>" class="btn btn-light btn-lg">
                    <i class="bi bi-house"></i> Retour à l'accueil
                </a>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}