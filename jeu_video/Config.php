<?php
// Configuration du chemin d'inclusion
define('BASE_PATH', __DIR__);

// Inclusion des classes
require_once BASE_PATH . '/classes/Database.php';
require_once BASE_PATH . '/classes/Personnage.php';
require_once BASE_PATH . '/classes/Arme.php';
require_once BASE_PATH . '/classes/Pouvoir.php';

// Création d'une instance de la base de données
$database = new Database();
$db = $database->getConnection();
?>