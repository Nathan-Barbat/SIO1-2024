<?php
// config/config.php
// Configuration générale de l'application

// Définition de la constante de l'URL de base
define('BASE_URL', 'http://localhost/quiz_app');

// Définition des constantes d'environnement
define('ENV', 'development'); // Peut être 'development' ou 'production'

// Gestion des erreurs
if (ENV === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Fuseau horaire
date_default_timezone_set('Europe/Paris');

// Constantes pour la sécurité
define('SECRET_KEY', 'votre_clé_secrète_pour_le_hachage');
define('TOKEN_EXPIRY', 3600); // Expiration du token en secondes (1 heure)

// Configuration des sessions
session_start();