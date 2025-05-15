<?php
// controllers/QuizController.php
// Contrôleur pour gérer les quiz

class QuizController {
    private $quizModel;
    private $questionModel;
    private $categoryModel;
    private $scoreModel;
    
    // Constructeur
    public function __construct() {
        $this->quizModel = new Quiz();
        $this->questionModel = new Question();
        $this->categoryModel = new Category();
        $this->scoreModel = new Score();
    }
    
    // Méthode pour afficher tous les quiz disponibles
    public function index() {
        // Vérification de la connexion
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=user&action=showLoginForm');
            exit;
        }
        
        $quizzes = $this->quizModel->getAll();
        
        require_once 'views/quiz/index.php';
    }
    
    // Méthode pour afficher le formulaire de création de quiz
    public function showCreateForm() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour accéder à cette page.";
            header('Location: index.php');
            exit;
        }
        
        $categories = $this->categoryModel->getAll();
        $questions = $this->questionModel->getAll();
        
        require_once 'views/quiz/create.php';
    }
    
    // Méthode pour traiter la création d'un quiz
    public function create() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour effectuer cette action.";
            header('Location: index.php');
            exit;
    }
    // 1. Vérification que l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Vous devez être connecté pour créer un quiz.";
        header('Location: index.php?controller=user&action=showLoginForm');
        exit;
    }
    
    // 2. Vérification que l'utilisateur est un formateur
    if ($_SESSION['user_type'] !== 'formateur') {
        $_SESSION['error'] = "Seuls les formateurs peuvent créer des quiz.";
        header('Location: index.php');
        exit;
    }
    
    // 3. Vérification optionnelle en utilisant la classe Auth (si elle existe)
    // Auth::requireTrainer();

        // Validation des données
        $titre = filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $questions = filter_input(INPUT_POST, 'questions', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        
        // Validation des champs obligatoires
        if (empty($titre) || empty($questions)) {
            $_SESSION['error'] = "Le titre du quiz et au moins une question sont obligatoires.";
            header('Location: index.php?controller=quiz&action=showCreateForm');
            exit;
        }
        
        // Création du quiz
        $id_quiz = $this->quizModel->create($titre, $description, $_SESSION['user_id']);
        
        if ($id_quiz) {
            // Ajout des questions au quiz
            $result = $this->quizModel->addQuestions($id_quiz, $questions);
            
            if ($result) {
                $_SESSION['success'] = "Quiz créé avec succès.";
                header('Location: index.php?controller=quiz&action=view&id=' . $id_quiz);
                exit;
            } else {
                // Suppression du quiz en cas d'erreur
                $this->quizModel->delete($id_quiz);
                $_SESSION['error'] = "Erreur lors de l'ajout des questions au quiz.";
                header('Location: index.php?controller=quiz&action=showCreateForm');
                exit;
            }
        } else {
            $_SESSION['error'] = "Erreur lors de la création du quiz.";
            header('Location: index.php?controller=quiz&action=showCreateForm');
            exit;
        }
    }
    
    // Méthode pour voir les détails d'un quiz
    public function view() {
        // Vérification de la connexion
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=user&action=showLoginForm');
            exit;
        }
        
        // Récupération de l'id du quiz
        $id_quiz = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$id_quiz) {
            $_SESSION['error'] = "ID de quiz invalide.";
            header('Location: index.php?controller=quiz&action=index');
            exit;
        }
        
        // Récupération du quiz avec ses questions
        $quiz = $this->quizModel->getById($id_quiz);
        
        if (!$quiz) {
            $_SESSION['error'] = "Quiz non trouvé.";
            header('Location: index.php?controller=quiz&action=index');
            exit;
        }
        
        // Récupération des scores pour ce quiz si l'utilisateur est formateur
        $scores = null;
        if ($_SESSION['user_type'] === 'formateur') {
            $scores = $this->scoreModel->getByQuiz($id_quiz);
        }
        
        // Récupération du meilleur score du stagiaire pour ce quiz
        $bestScore = null;
        if ($_SESSION['user_type'] === 'stagiaire') {
            $bestScore = $this->scoreModel->getBestScore($_SESSION['user_id'], $id_quiz);
        }
        
        require_once 'views/quiz/view.php';
    }
    
    // Méthode pour afficher le formulaire de modification de quiz
    public function showEditForm() {
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
        
        // Vérification que le formateur est le propriétaire du quiz
        if (!$this->quizModel->isOwner($id_quiz, $_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous n'êtes pas autorisé à modifier ce quiz.";
            header('Location: index.php?controller=quiz&action=index');
            exit;
        }
        
        // Récupération du quiz avec ses questions
        $quiz = $this->quizModel->getById($id_quiz);
        
        if (!$quiz) {
            $_SESSION['error'] = "Quiz non trouvé.";
            header('Location: index.php?controller=quiz&action=index');
            exit;
        }
        
        // Récupération de toutes les catégories et questions
        $categories = $this->categoryModel->getAll();
        $questions = $this->questionModel->getAll();
        
        // Préparation des questions déjà sélectionnées
        $selectedQuestions = [];
        foreach ($quiz['questions'] as $question) {
            $selectedQuestions[] = $question['id_question'];
        }
        
        require_once 'views/quiz/edit.php';
    }
    
    // Méthode pour traiter la modification d'un quiz
    public function update() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour effectuer cette action.";
            header('Location: index.php');
            exit;
        }
        
        // Validation des données
        $id_quiz = filter_input(INPUT_POST, 'id_quiz', FILTER_VALIDATE_INT);
        $titre = filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $questions = filter_input(INPUT_POST, 'questions', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        
        // Validation des champs obligatoires
        if (!$id_quiz || empty($titre) || empty($questions)) {
            $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis.";
            header('Location: index.php?controller=quiz&action=showEditForm&id=' . $id_quiz);
            exit;
        }
        
        // Vérification que le formateur est le propriétaire du quiz
        if (!$this->quizModel->isOwner($id_quiz, $_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous n'êtes pas autorisé à modifier ce quiz.";
            header('Location: index.php?controller=quiz&action=index');
            exit;
        }
        
        // Mise à jour du quiz
        $quizResult = $this->quizModel->update($id_quiz, $titre, $description);
        
        if ($quizResult) {
            // Mise à jour des questions du quiz
            $questionsResult = $this->quizModel->addQuestions($id_quiz, $questions);
            
            if ($questionsResult) {
                $_SESSION['success'] = "Quiz mis à jour avec succès.";
                header('Location: index.php?controller=quiz&action=view&id=' . $id_quiz);
                exit;
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour des questions du quiz.";
                header('Location: index.php?controller=quiz&action=showEditForm&id=' . $id_quiz);
                exit;
            }
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour du quiz.";
            header('Location: index.php?controller=quiz&action=showEditForm&id=' . $id_quiz);
            exit;
        }
    }
    
    // Méthode pour supprimer un quiz
    public function delete() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour effectuer cette action.";
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
        
        // Vérification que le formateur est le propriétaire du quiz
        if (!$this->quizModel->isOwner($id_quiz, $_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous n'êtes pas autorisé à supprimer ce quiz.";
            header('Location: index.php?controller=quiz&action=index');
            exit;
        }
        
        // Suppression du quiz
        $result = $this->quizModel->delete($id_quiz);
        
        if ($result) {
            $_SESSION['success'] = "Quiz supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression du quiz.";
        }
        
        header('Location: index.php?controller=quiz&action=index');
        exit;
    }
    
    // Méthode pour afficher la page de jeu
    public function play() {
        // Vérification de la connexion
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=user&action=showLoginForm');
            exit;
        }
        
        // Récupération de l'id du quiz
        $id_quiz = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$id_quiz) {
            $_SESSION['error'] = "ID de quiz invalide.";
            header('Location: index.php?controller=quiz&action=index');
            exit;
        }
        
        // Récupération du quiz avec ses questions
        $quiz = $this->quizModel->getById($id_quiz);
        
        if (!$quiz) {
            $_SESSION['error'] = "Quiz non trouvé.";
            header('Location: index.php?controller=quiz&action=index');
            exit;
        }
        
        require_once 'views/quiz/play.php';
    }
    
    // Méthode pour enregistrer le score après le jeu
    public function saveScore() {
        // Vérification de la connexion
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=user&action=showLoginForm');
            exit;
        }
        
        // Validation des données
        $id_quiz = filter_input(INPUT_POST, 'id_quiz', FILTER_VALIDATE_INT);
        $points = filter_input(INPUT_POST, 'points', FILTER_VALIDATE_INT);
        $temps_total = filter_input(INPUT_POST, 'temps_total', FILTER_VALIDATE_INT);
        
        // Validation des champs obligatoires
        if (!$id_quiz || !isset($points) || !isset($temps_total)) {
            $_SESSION['error'] = "Données invalides.";
            header('Location: index.php?controller=quiz&action=index');
            exit;
        }
        
        // Enregistrement du score
        $result = $this->scoreModel->create($points, $temps_total, $_SESSION['user_id'], $id_quiz);
        
        if ($result) {
            $_SESSION['success'] = "Score enregistré avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de l'enregistrement du score.";
        }
        
        header('Location: index.php?controller=quiz&action=view&id=' . $id_quiz);
        exit;
    }
}