<?php
// utils/Session.php
// Classe utilitaire pour la gestion des sessions

class Session {
    // Méthode pour initialiser la session
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // Méthode pour définir un message flash
    public static function setFlash($type, $message) {
        $_SESSION[$type] = $message;
    }
    
    // Méthode pour récupérer et afficher un message flash
    public static function flash($type) {
        if (isset($_SESSION[$type])) {
            $message = $_SESSION[$type];
            unset($_SESSION[$type]);
            return $message;
        }
        return '';
    }
    
    // Méthode pour vérifier si un message flash existe
    public static function hasFlash($type) {
        return isset($_SESSION[$type]);
    }
    
    // Méthode pour afficher un message flash avec un style Bootstrap
    public static function displayFlash() {
        $html = '';
        
        if (self::hasFlash('success')) {
            $html .= '<div class="alert alert-success alert-dismissible fade show" role="alert">';
            $html .= self::flash('success');
            $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>';
            $html .= '</div>';
        }
        
        if (self::hasFlash('error')) {
            $html .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            $html .= self::flash('error');
            $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>';
            $html .= '</div>';
        }
        
        if (self::hasFlash('warning')) {
            $html .= '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
            $html .= self::flash('warning');
            $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>';
            $html .= '</div>';
        }
        
        if (self::hasFlash('info')) {
            $html .= '<div class="alert alert-info alert-dismissible fade show" role="alert">';
            $html .= self::flash('info');
            $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>';
            $html .= '</div>';
        }
        
        return $html;
    }
    
    // Méthode pour détruire la session
    public static function destroy() {
        session_unset();
        session_destroy();
    }
}