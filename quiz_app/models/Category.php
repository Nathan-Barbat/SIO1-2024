<?php
// models/Category.php
// Modèle pour la gestion des catégories

class Category {
    private $db;
    private $table = 'Categorie';
    
    // Constructeur
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Méthode pour créer une nouvelle catégorie
    public function create($nom_categorie, $description = null) {
        $query = "INSERT INTO " . $this->table . " (nom_categorie, description) 
                 VALUES (:nom_categorie, :description)";
        
        $params = [
            ':nom_categorie' => $nom_categorie,
            ':description' => $description
        ];
        
        return $this->db->insert($query, $params);
    }
    
    // Méthode pour obtenir une catégorie par son ID
    public function getById($id_categorie) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_categorie = :id_categorie";
        $params = [':id_categorie' => $id_categorie];
        
        return $this->db->selectOne($query, $params);
    }
    
    // Méthode pour mettre à jour une catégorie
    public function update($id_categorie, $nom_categorie, $description = null) {
        $query = "UPDATE " . $this->table . " 
                 SET nom_categorie = :nom_categorie, description = :description 
                 WHERE id_categorie = :id_categorie";
        
        $params = [
            ':id_categorie' => $id_categorie,
            ':nom_categorie' => $nom_categorie,
            ':description' => $description
        ];
        
        return $this->db->update($query, $params);
    }
    
    // Méthode pour supprimer une catégorie
    public function delete($id_categorie) {
        // Vérifier si la catégorie est utilisée par des questions
        if ($this->hasQuestions($id_categorie)) {
            return false;
        }
        
        $query = "DELETE FROM " . $this->table . " WHERE id_categorie = :id_categorie";
        $params = [':id_categorie' => $id_categorie];
        
        return $this->db->delete($query, $params);
    }
    
    // Méthode pour vérifier si une catégorie a des questions
    private function hasQuestions($id_categorie) {
        $query = "SELECT COUNT(*) FROM Question WHERE id_categorie = :id_categorie";
        $params = [':id_categorie' => $id_categorie];
        
        $count = $this->db->count($query, $params);
        
        return $count > 0;
    }
    
    // Méthode pour obtenir toutes les catégories
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nom_categorie ASC";
        
        return $this->db->select($query);
    }
    
    // Méthode pour obtenir les catégories avec le nombre de questions
    public function getAllWithQuestionCount() {
        $query = "SELECT c.*, COUNT(q.id_question) as nombre_questions 
                 FROM " . $this->table . " c
                 LEFT JOIN Question q ON c.id_categorie = q.id_categorie
                 GROUP BY c.id_categorie
                 ORDER BY c.nom_categorie ASC";
        
        return $this->db->select($query);
    }
}