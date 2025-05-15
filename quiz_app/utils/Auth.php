<?php
// utils/Auth.php
// Classe utilitaire pour la gestion de l'authentification

class Auth {
    // Méthode pour vérifier si un utilisateur est connecté
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    // Méthode pour vérifier si l'utilisateur connecté est un formateur
    public static function isTrainer() {
        return self::isLoggedIn() && $_SESSION['user_type'] === 'formateur';
    }
    
    // Méthode pour vérifier si l'utilisateur connecté est un stagiaire
    public static function isStudent() {
        return self::isLoggedIn() && $_SESSION['user_type'] === 'stagiaire';
    }
    
    // Méthode pour rediriger si l'utilisateur n'est pas connecté
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page.";
            header('Location: index.php?controller=user&action=showLoginForm');
            exit;
        }
    }
    
    // Méthode pour rediriger si l'utilisateur n'est pas un formateur
    public static function requireTrainer() {
        self::requireLogin();
        if (!self::isTrainer()) {
            $_SESSION['error'] = "Vous n'avez pas les droits pour accéder à cette page.";
            header('Location: index.php');
            exit;
        }
    }
    
    // Méthode pour rediriger si l'utilisateur n'est pas un stagiaire
    public static function requireStudent() {
        self::requireLogin();
        if (!self::isStudent()) {
            $_SESSION['error'] = "Vous n'avez pas les droits pour accéder à cette page.";
            header('Location: index.php');
            exit;
        }
    }
    
    // Méthode pour rediriger si l'utilisateur est déjà connecté
    public static function redirectIfLoggedIn() {
        if (self::isLoggedIn()) {
            if (self::isTrainer()) {
                header('Location: index.php?controller=dashboard&action=trainer');
            } else {
                header('Location: index.php?controller=dashboard&action=student');
            }
            exit;
        }
    }
    
    // Méthode pour obtenir l'utilisateur connecté
    public static function getUser() {
        if (self::isLoggedIn()) {
            $user = [
                'id' => $_SESSION['user_id'],
                'nom' => $_SESSION['user_nom'],
                'prenom' => $_SESSION['user_prenom'],
                'email' => $_SESSION['user_email'],
                'type' => $_SESSION['user_type']
            ];
            return $user;
        }
        return null;
    }
}