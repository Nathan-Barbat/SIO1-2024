<?php
// controllers/QuestionController.php
// Contrôleur pour gérer les questions

class QuestionController {
    private $questionModel;
    private $categoryModel;
    
    // Constructeur
    public function __construct() {
        $this->questionModel = new Question();
        $this->categoryModel = new Category();
    }
    
    // Méthode pour afficher toutes les questions
    public function index() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour accéder à cette page.";
            header('Location: index.php');
            exit;
        }
        
        // Récupération des filtres éventuels
        $category_id = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT);
        $difficulty = filter_input(INPUT_GET, 'difficulty', FILTER_VALIDATE_INT);
        
        // Récupération des questions selon les filtres
        if ($category_id) {
            $questions = $this->questionModel->getByCategory($category_id);
        } elseif ($difficulty) {
            $questions = $this->questionModel->getByDifficulty($difficulty);
        } else {
            $questions = $this->questionModel->getAll();
        }
        
        // Récupération des catégories pour le filtre
        $categories = $this->categoryModel->getAll();
        
        require_once 'views/question/index.php';
    }
    
    // Méthode pour afficher le formulaire de création de question
    public function showCreateForm() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour accéder à cette page.";
            header('Location: index.php');
            exit;
        }
        
        // Récupération des catégories
        $categories = $this->categoryModel->getAll();
        
        require_once 'views/question/create.php';
    }
    
    // Méthode pour traiter la création d'une question
    public function create() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour effectuer cette action.";
            header('Location: index.php');
            exit;
        }
        
        // Validation des données
        $texte_question = filter_input(INPUT_POST, 'texte_question', FILTER_SANITIZE_STRING);
        $niveau_difficulte = filter_input(INPUT_POST, 'niveau_difficulte', FILTER_VALIDATE_INT);
        $id_categorie = filter_input(INPUT_POST, 'id_categorie', FILTER_VALIDATE_INT);
        
        $choix_textes = filter_input(INPUT_POST, 'choix_texte', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $choix_correct = filter_input(INPUT_POST, 'choix_correct', FILTER_VALIDATE_INT);
        
        // Validation des champs obligatoires
        if (empty($texte_question) || !$niveau_difficulte || !$id_categorie || empty($choix_textes) || !isset($choix_correct)) {
            $_SESSION['error'] = "Tous les champs sont obligatoires.";
            header('Location: index.php?controller=question&action=showCreateForm');
            exit;
        }
        
        // Vérification du niveau de difficulté
        if ($niveau_difficulte < 1 || $niveau_difficulte > 3) {
            $_SESSION['error'] = "Niveau de difficulté invalide.";
            header('Location: index.php?controller=question&action=showCreateForm');
            exit;
        }
        
        // Préparation des choix
        $choix = [];
        foreach ($choix_textes as $key => $texte) {
            if (!empty($texte)) {
                $choix[] = [
                    'texte' => $texte,
                    'est_correcte' => ($key == $choix_correct)
                ];
            }
        }
        
        // Vérification qu'il y a au moins 2 choix
        if (count($choix) < 2) {
            $_SESSION['error'] = "La question doit avoir au moins 2 choix.";
            header('Location: index.php?controller=question&action=showCreateForm');
            exit;
        }
        
        // Création de la question
        $result = $this->questionModel->create($texte_question, $niveau_difficulte, $id_categorie, $choix);
        
        if ($result) {
            $_SESSION['success'] = "Question créée avec succès.";
            header('Location: index.php?controller=question&action=index');
            exit;
        } else {
            $_SESSION['error'] = "Erreur lors de la création de la question.";
            header('Location: index.php?controller=question&action=showCreateForm');
            exit;
        }
    }
    
    // Méthode pour afficher le formulaire de modification de question
    public function showEditForm() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour accéder à cette page.";
            header('Location: index.php');
            exit;
        }
        
        // Récupération de l'id de la question
        $id_question = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$id_question) {
            $_SESSION['error'] = "ID de question invalide.";
            header('Location: index.php?controller=question&action=index');
            exit;
        }
        
        // Récupération de la question avec ses choix
        $question = $this->questionModel->getById($id_question);
        
        if (!$question) {
            $_SESSION['error'] = "Question non trouvée.";
            header('Location: index.php?controller=question&action=index');
            exit;
        }
        
        // Récupération des catégories
        $categories = $this->categoryModel->getAll();
        
        require_once 'views/question/edit.php';
    }
    
    // Méthode pour traiter la modification d'une question
    public function update() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour effectuer cette action.";
            header('Location: index.php');
            exit;
        }
        
        // Validation des données
        $id_question = filter_input(INPUT_POST, 'id_question', FILTER_VALIDATE_INT);
        $texte_question = filter_input(INPUT_POST, 'texte_question', FILTER_SANITIZE_STRING);
        $niveau_difficulte = filter_input(INPUT_POST, 'niveau_difficulte', FILTER_VALIDATE_INT);
        $id_categorie = filter_input(INPUT_POST, 'id_categorie', FILTER_VALIDATE_INT);
        
        $choix_textes = filter_input(INPUT_POST, 'choix_texte', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $choix_correct = filter_input(INPUT_POST, 'choix_correct', FILTER_VALIDATE_INT);
        
        // Validation des champs obligatoires
        if (!$id_question || empty($texte_question) || !$niveau_difficulte || !$id_categorie || empty($choix_textes) || !isset($choix_correct)) {
            $_SESSION['error'] = "Tous les champs sont obligatoires.";
            header('Location: index.php?controller=question&action=showEditForm&id=' . $id_question);
            exit;
        }
        
        // Vérification du niveau de difficulté
        if ($niveau_difficulte < 1 || $niveau_difficulte > 3) {
            $_SESSION['error'] = "Niveau de difficulté invalide.";
            header('Location: index.php?controller=question&action=showEditForm&id=' . $id_question);
            exit;
        }
        
        // Préparation des choix
        $choix = [];
        foreach ($choix_textes as $key => $texte) {
            if (!empty($texte)) {
                $choix[] = [
                    'texte' => $texte,
                    'est_correcte' => ($key == $choix_correct)
                ];
            }
        }
        
        // Vérification qu'il y a au moins 2 choix
        if (count($choix) < 2) {
            $_SESSION['error'] = "La question doit avoir au moins 2 choix.";
            header('Location: index.php?controller=question&action=showEditForm&id=' . $id_question);
            exit;
        }
        
        // Mise à jour de la question
        $questionResult = $this->questionModel->update($id_question, $texte_question, $niveau_difficulte, $id_categorie);
        
        if ($questionResult) {
            // Mise à jour des choix
            $choixResult = $this->questionModel->updateChoix($id_question, $choix);
            
            if ($choixResult) {
                $_SESSION['success'] = "Question mise à jour avec succès.";
                header('Location: index.php?controller=question&action=index');
                exit;
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour des choix.";
                header('Location: index.php?controller=question&action=showEditForm&id=' . $id_question);
                exit;
            }
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour de la question.";
            header('Location: index.php?controller=question&action=showEditForm&id=' . $id_question);
            exit;
        }
    }
    
    // Méthode pour supprimer une question
    public function delete() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour effectuer cette action.";
            header('Location: index.php');
            exit;
        }
        
        // Récupération de l'id de la question
        $id_question = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$id_question) {
            $_SESSION['error'] = "ID de question invalide.";
            header('Location: index.php?controller=question&action=index');
            exit;
        }
        
        // Suppression de la question
        $result = $this->questionModel->delete($id_question);
        
        if ($result) {
            $_SESSION['success'] = "Question supprimée avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression de la question.";
        }
        
        header('Location: index.php?controller=question&action=index');
        exit;
    }
    
    // Méthode pour vérifier une réponse (utilisée en AJAX)
    public function checkAnswer() {
        // Vérification de la connexion
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Utilisateur non connecté']);
            exit;
        }
        
        // Validation des données
        $id_question = filter_input(INPUT_POST, 'id_question', FILTER_VALIDATE_INT);
        $id_choix = filter_input(INPUT_POST, 'id_choix', FILTER_VALIDATE_INT);
        
        if (!$id_question || !$id_choix) {
            echo json_encode(['error' => 'Données invalides']);
            exit;
        }
        
        // Vérification de la réponse
        $isCorrect = $this->questionModel->checkAnswer($id_question, $id_choix);
        
        echo json_encode(['correct' => $isCorrect]);
        exit;
    }
}