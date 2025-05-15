<?php
// models/User.php
// Modèle pour la gestion des utilisateurs

class User {
    private $db;
    private $table = 'Utilisateur';
    
    // Constructeur
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Méthode pour créer un nouvel utilisateur
    public function create($nom, $prenom, $email, $password, $type) {
        // Vérifie si l'email existe déjà
        if ($this->emailExists($email)) {
            return ['success' => false, 'message' => 'Cet email est déjà utilisé.'];
        }
        
        // Hash du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Préparation de la requête
        $query = "INSERT INTO " . $this->table . " (nom, prenom, email, mot_de_passe, type_utilisateur) 
                 VALUES (:nom, :prenom, :email, :mot_de_passe, :type_utilisateur)";
        
        // Paramètres
        $params = [
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':mot_de_passe' => $hashedPassword,
            ':type_utilisateur' => $type
        ];
        
        // Exécution de la requête
        $id = $this->db->insert($query, $params);
        
        if ($id) {
            return ['success' => true, 'user_id' => $id, 'message' => 'Utilisateur créé avec succès.'];
        }
        
        return ['success' => false, 'message' => 'Erreur lors de la création de l\'utilisateur.'];
    }
    
    // Méthode pour vérifier les identifiants de connexion
    public function login($email, $password) {
        // Recherche de l'utilisateur par email
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $params = [':email' => $email];
        
        $user = $this->db->selectOne($query, $params);
        
        // Vérifie si l'utilisateur existe et si le mot de passe est correct
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Supprime le mot de passe du tableau avant de le retourner
            unset($user['mot_de_passe']);
            return ['success' => true, 'user' => $user];
        }
        
        return ['success' => false, 'message' => 'Email ou mot de passe incorrect.'];
    }
    
    // Méthode pour vérifier si un email existe déjà
    public function emailExists($email) {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE email = :email";
        $params = [':email' => $email];
        
        $count = $this->db->count($query, $params);
        
        return $count > 0;
    }
    
    // Méthode pour obtenir un utilisateur par son ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_utilisateur = :id";
        $params = [':id' => $id];
        
        $user = $this->db->selectOne($query, $params);
        
        if ($user) {
            // Supprime le mot de passe du tableau avant de le retourner
            unset($user['mot_de_passe']);
            return $user;
        }
        
        return null;
    }
    
    // Méthode pour obtenir tous les formateurs
    public function getAllTrainers() {
        $query = "SELECT id_utilisateur, nom, prenom, email FROM " . $this->table . " 
                 WHERE type_utilisateur = 'formateur'";
        
        return $this->db->select($query);
    }
    
    // Méthode pour obtenir tous les stagiaires
    public function getAllStudents() {
        $query = "SELECT id_utilisateur, nom, prenom, email FROM " . $this->table . " 
                 WHERE type_utilisateur = 'stagiaire'";
        
        return $this->db->select($query);
    }
    
    // Méthode pour mettre à jour un utilisateur
    public function update($id, $data) {
        // Construction de la requête dynamique
        $query = "UPDATE " . $this->table . " SET ";
        $params = [];
        
        foreach ($data as $key => $value) {
            if ($key != 'id_utilisateur') {
                $query .= $key . " = :" . $key . ", ";
                $params[':' . $key] = $value;
            }
        }
        
        // Supprime la dernière virgule et espace
        $query = rtrim($query, ", ");
        
        // Ajoute la condition WHERE
        $query .= " WHERE id_utilisateur = :id";
        $params[':id'] = $id;
        
        // Exécution de la requête
        return $this->db->update($query, $params);
    }
    
    // Méthode pour vérifier si un utilisateur est formateur
    public function isTrainer($id) {
        $query = "SELECT type_utilisateur FROM " . $this->table . " WHERE id_utilisateur = :id";
        $params = [':id' => $id];
        
        $user = $this->db->selectOne($query, $params);
        
        return ($user && $user['type_utilisateur'] == 'formateur');
    }
}