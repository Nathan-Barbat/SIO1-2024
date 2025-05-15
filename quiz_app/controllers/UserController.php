<?php
// controllers/UserController.php
// Contrôleur pour gérer les utilisateurs

class UserController {
    private $userModel;
    
    // Constructeur
    public function __construct() {
        $this->userModel = new User();
    }
    
    // Méthode pour afficher le formulaire d'inscription
    public function showRegisterForm() {
        require_once 'views/auth/register.php';
    }
    
    // Méthode pour traiter l'inscription
    public function register() {
        // Validation des données
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
        
        // Validation des champs obligatoires
        if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($confirm_password) || empty($type)) {
            $_SESSION['error'] = "Tous les champs sont obligatoires.";
            header('Location: index.php?controller=user&action=showRegisterForm');
            exit;
        }
        
        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Format d'email invalide.";
            header('Location: index.php?controller=user&action=showRegisterForm');
            exit;
        }
        
        // Validation du mot de passe
        if (strlen($password) < 6) {
            $_SESSION['error'] = "Le mot de passe doit contenir au moins 6 caractères.";
            header('Location: index.php?controller=user&action=showRegisterForm');
            exit;
        }
        
        // Vérification que les mots de passe correspondent
        if ($password !== $confirm_password) {
            $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
            header('Location: index.php?controller=user&action=showRegisterForm');
            exit;
        }
        
        // Vérification du type d'utilisateur
        if ($type !== 'stagiaire' && $type !== 'formateur') {
            $_SESSION['error'] = "Type d'utilisateur invalide.";
            header('Location: index.php?controller=user&action=showRegisterForm');
            exit;
        }
        
        // Inscription de l'utilisateur
        $result = $this->userModel->create($nom, $prenom, $email, $password, $type);
        
        if ($result['success']) {
            $_SESSION['success'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
            header('Location: index.php?controller=user&action=showLoginForm');
            exit;
        } else {
            $_SESSION['error'] = $result['message'];
            header('Location: index.php?controller=user&action=showRegisterForm');
            exit;
        }
    }
    
    // Méthode pour afficher le formulaire de connexion
    public function showLoginForm() {
        require_once 'views/auth/login.php';
    }
    
    // Méthode pour traiter la connexion
    public function login() {
        // Validation des données
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        
        // Validation des champs obligatoires
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Tous les champs sont obligatoires.";
            header('Location: index.php?controller=user&action=showLoginForm');
            exit;
        }
        
        // Connexion de l'utilisateur
        $result = $this->userModel->login($email, $password);
        
        if ($result['success']) {
            // Enregistrement des informations de l'utilisateur en session
            $_SESSION['user_id'] = $result['user']['id_utilisateur'];
            $_SESSION['user_nom'] = $result['user']['nom'];
            $_SESSION['user_prenom'] = $result['user']['prenom'];
            $_SESSION['user_email'] = $result['user']['email'];
            $_SESSION['user_type'] = $result['user']['type_utilisateur'];
            
            // Redirection selon le type d'utilisateur
            if ($result['user']['type_utilisateur'] === 'formateur') {
                header('Location: index.php?controller=dashboard&action=trainer');
            } else {
                header('Location: index.php?controller=dashboard&action=student');
            }
            exit;
        } else {
            $_SESSION['error'] = $result['message'];
            header('Location: index.php?controller=user&action=showLoginForm');
            exit;
        }
    }
    
    // Méthode pour déconnecter l'utilisateur
    public function logout() {
        // Destruction de la session
        session_unset();
        session_destroy();
        
        header('Location: index.php');
        exit;
    }
    
    // Méthode pour afficher le profil utilisateur
    public function profile() {
        // Vérification de la connexion
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=user&action=showLoginForm');
            exit;
        }
        
        $user = $this->userModel->getById($_SESSION['user_id']);
        
        if ($user) {
            require_once 'views/user/profile.php';
        } else {
            $_SESSION['error'] = "Utilisateur non trouvé.";
            header('Location: index.php');
            exit;
        }
    }
    
    // Méthode pour mettre à jour le profil utilisateur
    public function updateProfile() {
        // Vérification de la connexion
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=user&action=showLoginForm');
            exit;
        }
        
        // Validation des données
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        
        // Validation des champs obligatoires
        if (empty($nom) || empty($prenom) || empty($email)) {
            $_SESSION['error'] = "Tous les champs sont obligatoires.";
            header('Location: index.php?controller=user&action=profile');
            exit;
        }
        
        // Préparation des données
        $data = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email
        ];
        
        // Mise à jour du profil
        $result = $this->userModel->update($_SESSION['user_id'], $data);
        
        if ($result) {
            // Mise à jour des informations en session
            $_SESSION['user_nom'] = $nom;
            $_SESSION['user_prenom'] = $prenom;
            $_SESSION['user_email'] = $email;
            
            $_SESSION['success'] = "Profil mis à jour avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour du profil.";
        }
        
        header('Location: index.php?controller=user&action=profile');
        exit;
    }
}