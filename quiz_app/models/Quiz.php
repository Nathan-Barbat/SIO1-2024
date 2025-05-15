<?php
// models/Quiz.php
// Modèle pour la gestion des quiz

class Quiz {
    private $db;
    private $table = 'Quiz';
    
    // Constructeur
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Méthode pour créer un nouveau quiz
    public function create($titre, $description, $id_formateur) {
        $query = "INSERT INTO " . $this->table . " (titre_quiz, description, id_formateur) 
                 VALUES (:titre, :description, :id_formateur)";
        
        $params = [
            ':titre' => $titre,
            ':description' => $description,
            ':id_formateur' => $id_formateur
        ];
        
        return $this->db->insert($query, $params);
    }
    
    // Méthode pour ajouter des questions à un quiz
    public function addQuestions($id_quiz, $questions) {
        $success = true;
    
    // Début de la transaction
        $this->db->beginTransaction();
    
        try {
        // Supprimer les questions existantes (si modification de quiz)
        $this->removeAllQuestions($id_quiz);
        
        // Ajouter les nouvelles questions
        $ordre = 1;
        foreach ($questions as $id_question) {
            // Vérifier que la question existe
            $checkQuery = "SELECT COUNT(*) FROM Question WHERE id_question = :id_question";
            $checkParams = [':id_question' => $id_question];
            $questionExists = $this->db->count($checkQuery, $checkParams);
            
            if ($questionExists > 0) {
                $query = "INSERT INTO QuizQuestion (id_quiz, id_question, ordre) 
                         VALUES (:id_quiz, :id_question, :ordre)";
                
                $params = [
                    ':id_quiz' => $id_quiz,
                    ':id_question' => $id_question,
                    ':ordre' => $ordre++
                ];
                
                $result = $this->db->insert($query, $params);
                if (!$result) {
                    throw new Exception("Erreur lors de l'ajout de la question au quiz");
                }
            } else {
                // Ignorer les questions qui n'existent pas
                continue;
            }
        }
        
        // Valider la transaction
        $this->db->commit();
        return true;
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $this->db->rollBack();
        
        // Pour le débogage (à supprimer en production)
        if (ENV === 'development') {
            error_log("Erreur dans addQuestions: " . $e->getMessage());
        }
        
        return false;
    }
}
    
    // Méthode pour supprimer toutes les questions d'un quiz
    private function removeAllQuestions($id_quiz) {
        $query = "DELETE FROM QuizQuestion WHERE id_quiz = :id_quiz";
        $params = [':id_quiz' => $id_quiz];
        
        return $this->db->delete($query, $params);
    }
    
    // Méthode pour obtenir un quiz par son ID avec ses questions
public function getById($id_quiz) {
    // Récupérer les informations du quiz avec les données du formateur
    $queryQuiz = "SELECT q.*, u.nom, u.prenom 
                 FROM " . $this->table . " q
                 JOIN Utilisateur u ON q.id_formateur = u.id_utilisateur
                 WHERE q.id_quiz = :id_quiz";
    $paramsQuiz = [':id_quiz' => $id_quiz];
    
    $quiz = $this->db->selectOne($queryQuiz, $paramsQuiz);
    
    if (!$quiz) {
        return null;
    }
    
    // Récupérer les questions associées au quiz avec leur ordre
    $queryQuestions = "SELECT q.*, c.nom_categorie, qq.ordre 
                      FROM Question q 
                      JOIN QuizQuestion qq ON q.id_question = qq.id_question 
                      JOIN Categorie c ON q.id_categorie = c.id_categorie
                      WHERE qq.id_quiz = :id_quiz 
                      ORDER BY qq.ordre ASC";
    
    $paramsQuestions = [':id_quiz' => $id_quiz];
    
    $questions = $this->db->select($queryQuestions, $paramsQuestions);
    
    // Pour chaque question, récupérer ses choix
    foreach ($questions as &$question) {
        $queryChoix = "SELECT * FROM Choix WHERE id_question = :id_question";
        $paramsChoix = [':id_question' => $question['id_question']];
        $question['choix'] = $this->db->select($queryChoix, $paramsChoix);
    }
    
    // Ajouter les questions au quiz
    $quiz['questions'] = $questions;
    
    return $quiz;
}
    
    // Méthode pour mettre à jour un quiz
    public function update($id_quiz, $titre, $description) {
        $query = "UPDATE " . $this->table . " 
                 SET titre_quiz = :titre, description = :description 
                 WHERE id_quiz = :id_quiz";
        
        $params = [
            ':id_quiz' => $id_quiz,
            ':titre' => $titre,
            ':description' => $description
        ];
        
        return $this->db->update($query, $params);
    }
    
    // Méthode pour supprimer un quiz
    public function delete($id_quiz) {
        // Début de la transaction
        $this->db->beginTransaction();
        
        try {
            // Supprimer les associations quiz-questions
            $this->removeAllQuestions($id_quiz);
            
            // Supprimer le quiz
            $query = "DELETE FROM " . $this->table . " WHERE id_quiz = :id_quiz";
            $params = [':id_quiz' => $id_quiz];
            
            $result = $this->db->delete($query, $params);
            
            if (!$result) {
                throw new Exception("Erreur lors de la suppression du quiz");
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
    
    // Méthode pour obtenir tous les quiz
    public function getAll() {
        $query = "SELECT q.*, u.nom, u.prenom 
                 FROM " . $this->table . " q
                 JOIN Utilisateur u ON q.id_formateur = u.id_utilisateur
                 ORDER BY q.date_creation DESC";
        
        return $this->db->select($query);
    }
    
    // Méthode pour obtenir les quiz créés par un formateur
    public function getByTrainer($id_formateur) {
        $query = "SELECT * FROM " . $this->table . " 
                 WHERE id_formateur = :id_formateur
                 ORDER BY date_creation DESC";
        
        $params = [':id_formateur' => $id_formateur];
        
        return $this->db->select($query, $params);
    }
    
    // Méthode pour vérifier si un formateur est le propriétaire d'un quiz
    public function isOwner($id_quiz, $id_formateur) {
        $query = "SELECT COUNT(*) FROM " . $this->table . " 
                 WHERE id_quiz = :id_quiz AND id_formateur = :id_formateur";
        
        $params = [
            ':id_quiz' => $id_quiz,
            ':id_formateur' => $id_formateur
        ];
        
        $count = $this->db->count($query, $params);
        
        return $count > 0;
    }
}