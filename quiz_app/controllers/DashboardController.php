<?php
// controllers/DashboardController.php
// Contrôleur pour gérer les tableaux de bord

class DashboardController {
    private $quizModel;
    private $scoreModel;
    private $userModel;
    
    // Constructeur
    public function __construct() {
        $this->quizModel = new Quiz();
        $this->scoreModel = new Score();
        $this->userModel = new User();
    }
    
    // Méthode pour afficher le tableau de bord du formateur
    public function trainer() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour accéder à cette page.";
            header('Location: index.php');
            exit;
        }
        
        // Récupération des quiz créés par le formateur
        $quizzes = $this->quizModel->getByTrainer($_SESSION['user_id']);
        
        // Récupération des statistiques globales
        $stats = $this->scoreModel->getGlobalStats();
        
        // Récupération du classement des stagiaires
        $ranking = $this->scoreModel->getStudentRanking(5);
        
        require_once 'views/dashboard/trainer.php';
    }
    
    // Méthode pour afficher le tableau de bord du stagiaire
    public function student() {
        // Vérification de la connexion
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=user&action=showLoginForm');
            exit;
        }
        
        // Récupération des scores du stagiaire
        $scores = $this->scoreModel->getByStudent($_SESSION['user_id']);
        
        // Récupération des statistiques du stagiaire
        $stats = $this->scoreModel->getStudentStats($_SESSION['user_id']);
        
        // Récupération de tous les quiz disponibles
        $quizzes = $this->quizModel->getAll();
        
        // Filtrer les quiz que le stagiaire n'a pas encore fait
        $newQuizzes = [];
        $doneQuizIds = [];
        
        foreach ($scores as $score) {
            $doneQuizIds[] = $score['id_quiz'];
        }
        
        foreach ($quizzes as $quiz) {
            if (!in_array($quiz['id_quiz'], $doneQuizIds)) {
                $newQuizzes[] = $quiz;
            }
        }
        
        require_once 'views/dashboard/student.php';
    }
    
    // Méthode pour afficher la page d'accueil
    public function home() {
        // Si l'utilisateur est connecté, redirection vers son tableau de bord
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['user_type'] === 'formateur') {
                header('Location: index.php?controller=dashboard&action=trainer');
            } else {
                header('Location: index.php?controller=dashboard&action=student');
            }
            exit;
        }
        
        // Récupération de quelques statistiques pour la page d'accueil
        $quizCount = count($this->quizModel->getAll());
        $stats = $this->scoreModel->getGlobalStats();
        
        require_once 'views/home/index.php';
    }
}