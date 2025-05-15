<?php
// controllers/ScoreController.php
// Contrôleur pour gérer les scores

class ScoreController {
    private $scoreModel;
    private $quizModel;
    
    // Constructeur
    public function __construct() {
        $this->scoreModel = new Score();
        $this->quizModel = new Quiz();
    }
    
    // Méthode pour afficher les scores d'un stagiaire
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
        
        require_once 'views/scores/student.php';
    }
    
    // Méthode pour afficher les scores d'un quiz
    public function quiz() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour accéder à cette page.";
            header('Location: index.php');
            exit;
        }
        
        // Récupération de l'id du quiz
        $id_quiz = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$id_quiz) {
            $_SESSION['error'] = "ID de quiz invalide.";
            header('Location: index.php?controller=quiz&action=index');
            exit;
        }
        
        // Récupération du quiz
        $quiz = $this->quizModel->getById($id_quiz);
        
        if (!$quiz) {
            $_SESSION['error'] = "Quiz non trouvé.";
            header('Location: index.php?controller=quiz&action=index');
            exit;
        }
        
        // Vérification que le formateur est le propriétaire du quiz
        if (!$this->quizModel->isOwner($id_quiz, $_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous n'êtes pas autorisé à voir les scores de ce quiz.";
            header('Location: index.php?controller=quiz&action=index');
            exit;
        }
        
        // Récupération des scores pour ce quiz
        $scores = $this->scoreModel->getByQuiz($id_quiz);
        
        require_once 'views/scores/quiz.php';
    }
    
    // Méthode pour afficher le classement général
    public function ranking() {
        // Vérification de la connexion
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=user&action=showLoginForm');
            exit;
        }
        
        // Récupération du classement des stagiaires
        $ranking = $this->scoreModel->getStudentRanking(20);
        
        // Récupération des statistiques globales
        $stats = $this->scoreModel->getGlobalStats();
        
        require_once 'views/scores/ranking.php';
    }
}