<?php
// models/Question.php
// Modèle pour la gestion des questions

class Question {
    private $db;
    private $table = 'Question';
    
    // Constructeur
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Méthode pour créer une nouvelle question avec ses choix
    public function create($texte_question, $niveau_difficulte, $id_categorie, $choix) {
        // Début de la transaction
        $this->db->beginTransaction();
        
        try {
            // Insérer la question
            $queryQuestion = "INSERT INTO " . $this->table . " (texte_question, niveau_difficulte, id_categorie) 
                            VALUES (:texte_question, :niveau_difficulte, :id_categorie)";
            
            $paramsQuestion = [
                ':texte_question' => $texte_question,
                ':niveau_difficulte' => $niveau_difficulte,
                ':id_categorie' => $id_categorie
            ];
            
            $id_question = $this->db->insert($queryQuestion, $paramsQuestion);
            
            if (!$id_question) {
                throw new Exception("Erreur lors de la création de la question");
            }
            
            // Insérer les choix
            foreach ($choix as $choix_option) {
                $queryChoix = "INSERT INTO Choix (texte_choix, est_correcte, id_question) 
                              VALUES (:texte_choix, :est_correcte, :id_question)";
                
                $paramsChoix = [
                    ':texte_choix' => $choix_option['texte'],
                    ':est_correcte' => $choix_option['est_correcte'] ? 1 : 0,
                    ':id_question' => $id_question
                ];
                
                $result = $this->db->insert($queryChoix, $paramsChoix);
                
                if (!$result) {
                    throw new Exception("Erreur lors de la création des choix");
                }
            }
            
            // Valider la transaction
            $this->db->commit();
            return $id_question;
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->db->rollBack();
            return false;
        }
    }
    
    // Méthode pour obtenir une question par son ID avec ses choix
    public function getById($id_question) {
        // Récupérer la question
        $queryQuestion = "SELECT q.*, c.nom_categorie 
                         FROM " . $this->table . " q
                         JOIN Categorie c ON q.id_categorie = c.id_categorie
                         WHERE q.id_question = :id_question";
        
        $paramsQuestion = [':id_question' => $id_question];
        
        $question = $this->db->selectOne($queryQuestion, $paramsQuestion);
        
        if (!$question) {
            return null;
        }
        
        // Récupérer les choix associés à la question
        $queryChoix = "SELECT * FROM Choix WHERE id_question = :id_question";
        $paramsChoix = [':id_question' => $id_question];
        
        $choix = $this->db->select($queryChoix, $paramsChoix);
        
        // Ajouter les choix à la question
        $question['choix'] = $choix;
        
        return $question;
    }
    
    // Méthode pour mettre à jour une question
    public function update($id_question, $texte_question, $niveau_difficulte, $id_categorie) {
        $query = "UPDATE " . $this->table . " 
                 SET texte_question = :texte_question, 
                     niveau_difficulte = :niveau_difficulte, 
                     id_categorie = :id_categorie 
                 WHERE id_question = :id_question";
        
        $params = [
            ':id_question' => $id_question,
            ':texte_question' => $texte_question,
            ':niveau_difficulte' => $niveau_difficulte,
            ':id_categorie' => $id_categorie
        ];
        
        return $this->db->update($query, $params);
    }
    
    // Méthode pour mettre à jour les choix d'une question
    public function updateChoix($id_question, $choix) {
        // Début de la transaction
        $this->db->beginTransaction();
        
        try {
            // Supprimer les choix existants
            $queryDeleteChoix = "DELETE FROM Choix WHERE id_question = :id_question";
            $paramsDeleteChoix = [':id_question' => $id_question];
            
            $result = $this->db->delete($queryDeleteChoix, $paramsDeleteChoix);
            
            if ($result === false) {
                throw new Exception("Erreur lors de la suppression des choix existants");
            }
            
            // Insérer les nouveaux choix
            foreach ($choix as $choix_option) {
                $queryChoix = "INSERT INTO Choix (texte_choix, est_correcte, id_question) 
                              VALUES (:texte_choix, :est_correcte, :id_question)";
                
                $paramsChoix = [
                    ':texte_choix' => $choix_option['texte'],
                    ':est_correcte' => $choix_option['est_correcte'] ? 1 : 0,
                    ':id_question' => $id_question
                ];
                
                $result = $this->db->insert($queryChoix, $paramsChoix);
                
                if (!$result) {
                    throw new Exception("Erreur lors de la création des nouveaux choix");
                }
            }
            
            // Valider la transaction
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->db->rollBack();
            return false;
        }
    }
    
    // Méthode pour supprimer une question et ses choix
    public function delete($id_question) {
        // Les choix seront supprimés automatiquement grâce à la contrainte ON DELETE CASCADE
        $query = "DELETE FROM " . $this->table . " WHERE id_question = :id_question";
        $params = [':id_question' => $id_question];
        
        return $this->db->delete($query, $params);
    }
    
    // Méthode pour obtenir toutes les questions avec leur catégorie
    public function getAll() {
        $query = "SELECT q.*, c.nom_categorie 
                 FROM " . $this->table . " q
                 JOIN Categorie c ON q.id_categorie = c.id_categorie
                 ORDER BY q.id_question DESC";
        
        return $this->db->select($query);
    }
    
    // Méthode pour obtenir les questions par catégorie
    public function getByCategory($id_categorie) {
        $query = "SELECT * FROM " . $this->table . " 
                 WHERE id_categorie = :id_categorie
                 ORDER BY id_question DESC";
        
        $params = [':id_categorie' => $id_categorie];
        
        return $this->db->select($query, $params);
    }
    
    // Méthode pour obtenir les questions par niveau de difficulté
    public function getByDifficulty($niveau) {
        $query = "SELECT q.*, c.nom_categorie 
                 FROM " . $this->table . " q
                 JOIN Categorie c ON q.id_categorie = c.id_categorie
                 WHERE q.niveau_difficulte = :niveau
                 ORDER BY q.id_question DESC";
        
        $params = [':niveau' => $niveau];
        
        return $this->db->select($query, $params);
    }
    
    // Méthode pour vérifier si une réponse est correcte
    public function checkAnswer($id_question, $id_choix) {
        $query = "SELECT est_correcte FROM Choix 
                 WHERE id_question = :id_question AND id_choix = :id_choix";
        
        $params = [
            ':id_question' => $id_question,
            ':id_choix' => $id_choix
        ];
        
        $result = $this->db->selectOne($query, $params);
        
        return $result && $result['est_correcte'] == 1;
    }
}