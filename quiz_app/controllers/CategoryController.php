<?php
// controllers/CategoryController.php
// Contrôleur pour gérer les catégories

class CategoryController {
    private $categoryModel;
    
    // Constructeur
    public function __construct() {
        $this->categoryModel = new Category();
    }
    
    // Méthode pour afficher toutes les catégories
    public function index() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour accéder à cette page.";
            header('Location: index.php');
            exit;
        }
        
        // Récupération des catégories avec le nombre de questions
        $categories = $this->categoryModel->getAllWithQuestionCount();
        
        require_once 'views/category/index.php';
    }
    
    // Méthode pour afficher le formulaire de création de catégorie
    public function showCreateForm() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour accéder à cette page.";
            header('Location: index.php');
            exit;
        }
        
        require_once 'views/category/create.php';
    }
    
    // Méthode pour traiter la création d'une catégorie
    public function create() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour effectuer cette action.";
            header('Location: index.php');
            exit;
        }
        
        // Validation des données
        $nom_categorie = filter_input(INPUT_POST, 'nom_categorie', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        
        // Validation des champs obligatoires
        if (empty($nom_categorie)) {
            $_SESSION['error'] = "Le nom de la catégorie est obligatoire.";
            header('Location: index.php?controller=category&action=showCreateForm');
            exit;
        }
        
        // Création de la catégorie
        $result = $this->categoryModel->create($nom_categorie, $description);
        
        if ($result) {
            $_SESSION['success'] = "Catégorie créée avec succès.";
            header('Location: index.php?controller=category&action=index');
            exit;
        } else {
            $_SESSION['error'] = "Erreur lors de la création de la catégorie.";
            header('Location: index.php?controller=category&action=showCreateForm');
            exit;
        }
    }
    
    // Méthode pour afficher le formulaire de modification de catégorie
    public function showEditForm() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour accéder à cette page.";
            header('Location: index.php');
            exit;
        }
        
        // Récupération de l'id de la catégorie
        $id_categorie = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$id_categorie) {
            $_SESSION['error'] = "ID de catégorie invalide.";
            header('Location: index.php?controller=category&action=index');
            exit;
        }
        
        // Récupération de la catégorie
        $category = $this->categoryModel->getById($id_categorie);
        
        if (!$category) {
            $_SESSION['error'] = "Catégorie non trouvée.";
            header('Location: index.php?controller=category&action=index');
            exit;
        }
        
        require_once 'views/category/edit.php';
    }
    
    // Méthode pour traiter la modification d'une catégorie
    public function update() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour effectuer cette action.";
            header('Location: index.php');
            exit;
        }
        
        // Validation des données
        $id_categorie = filter_input(INPUT_POST, 'id_categorie', FILTER_VALIDATE_INT);
        $nom_categorie = filter_input(INPUT_POST, 'nom_categorie', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        
        // Validation des champs obligatoires
        if (!$id_categorie || empty($nom_categorie)) {
            $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis.";
            header('Location: index.php?controller=category&action=showEditForm&id=' . $id_categorie);
            exit;
        }
        
        // Mise à jour de la catégorie
        $result = $this->categoryModel->update($id_categorie, $nom_categorie, $description);
        
        if ($result) {
            $_SESSION['success'] = "Catégorie mise à jour avec succès.";
            header('Location: index.php?controller=category&action=index');
            exit;
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour de la catégorie.";
            header('Location: index.php?controller=category&action=showEditForm&id=' . $id_categorie);
            exit;
        }
    }
    
    // Méthode pour supprimer une catégorie
    public function delete() {
        // Vérification de la connexion et des droits
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'formateur') {
            $_SESSION['error'] = "Vous n'avez pas les droits pour effectuer cette action.";
            header('Location: index.php');
            exit;
        }
        
        // Récupération de l'id de la catégorie
        $id_categorie = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$id_categorie) {
            $_SESSION['error'] = "ID de catégorie invalide.";
            header('Location: index.php?controller=category&action=index');
            exit;
        }
        
        // Suppression de la catégorie
        $result = $this->categoryModel->delete($id_categorie);
        
        if ($result) {
            $_SESSION['success'] = "Catégorie supprimée avec succès.";
        } else {
            $_SESSION['error'] = "Impossible de supprimer cette catégorie car elle est utilisée par des questions.";
        }
        
        header('Location: index.php?controller=category&action=index');
        exit;
    }
}