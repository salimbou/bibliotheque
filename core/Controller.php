<?php
/**
 * Base Controller
 * Parent class for all controllers
 */

class Controller {
    
    /**
     * Load view
     */
    protected function view(string $view, array $data = []): void {
        extract($data);
        $viewPath = ROOT_PATH . '/views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View not found: $view");
        }
    }
    
    /**
     * Redirect to URL
     */
    protected function redirect(string $path): void {
        redirect($path);
    }
}