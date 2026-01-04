<?php
/**
 * Authentication Middleware
 * Ensures user is logged in before accessing protected routes
 */

class AuthMiddleware {
    
    /**
     * Check if user is authenticated
     */
    public static function handle(): void {
        if (!Session::isLoggedIn()) {
            Session::setFlash('error', 'Vous devez être connecté pour accéder à cette page');
            redirect('/login');
        }
    }
    
    /**
     * Redirect authenticated users (for login/register pages)
     */
    public static function guest(): void {
        if (Session::isLoggedIn()) {
            $role = Session::getUserRole();
            redirect("/$role/dashboard");
        }
    }
}