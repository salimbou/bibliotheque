<?php
/**
 * Role Middleware
 * Ensures user has required role to access route
 */

class RoleMiddleware {
    
    /**
     * Check if user has required role
     */
    public static function handle(string $requiredRole): void {
        AuthMiddleware::handle();
        
        $userRole = Session::getUserRole();
        
        if ($userRole !== $requiredRole) {
            Session::setFlash('error', 'Vous n\'avez pas les permissions nécessaires');
            redirect("/$userRole/dashboard");
        }
    }
    
    /**
     * Check if user is admin
     */
    public static function admin(): void {
        self::handle('admin');
    }
    
    /**
     * Check if user is bibliothecaire or admin
     */
    public static function bibliothecaire(): void {
        AuthMiddleware::handle();
        
        $userRole = Session::getUserRole();
        
        if (!in_array($userRole, ['bibliothecaire', 'admin'])) {
            Session::setFlash('error', 'Vous n\'avez pas les permissions nécessaires');
            redirect("/$userRole/dashboard");
        }
    }
    
    /**
     * Check if user is lecteur
     */
    public static function lecteur(): void {
        self::handle('lecteur');
    }
}