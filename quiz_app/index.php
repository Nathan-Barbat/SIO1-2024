<?php
// index.php
// Point d'entrée de l'application

// Inclusion des fichiers de configuration
require_once 'config/config.php';
require_once 'config/database.php';

// Inclusion des utilitaires
require_once 'utils/Auth.php';
require_once 'utils/Session.php';
require_once 'utils/Validator.php';

// Inclusion des modèles
require_once 'models/Database.php';
require_once 'models/User.php';
require_once 'models/Category.php';
require_once 'models/Question.php';
require_once 'models/Quiz.php';
require_once 'models/Score.php';

// Inclusion des contrôleurs
require_once 'controllers/UserController.php';
require_once 'controllers/CategoryController.php';
require_once 'controllers/QuestionController.php';
require_once 'controllers/QuizController.php';
require_once 'controllers/ScoreController.php';
require_once 'controllers/DashboardController.php';

// Initialisation de la session
Session::init();

// Récupération du contrôleur et de l'action à partir de l'URL
$controller = filter_input(INPUT_GET, 'controller', FILTER_SANITIZE_STRING) ?: 'dashboard';
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) ?: 'home';

// Instanciation du contrôleur approprié
switch ($controller) {
    case 'user':
        $controllerInstance = new UserController();
        break;
    case 'category':
        $controllerInstance = new CategoryController();
        break;
    case 'question':
        $controllerInstance = new QuestionController();
        break;
    case 'quiz':
        $controllerInstance = new QuizController();
        break;
    case 'score':
        $controllerInstance = new ScoreController();
        break;
    case 'dashboard':
    default:
        $controllerInstance = new DashboardController();
        break;
}

// Exécution de l'action appropriée
if (method_exists($controllerInstance, $action)) {
    $controllerInstance->$action();
} else {
    // Action par défaut si l'action demandée n'existe pas
    $controllerInstance->home();
}