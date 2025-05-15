<?php
// models/Score.php
// Modèle pour la gestion des scores

class Score {
    private $db;
    private $table = 'Score';
    
    // Constructeur
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Méthode pour enregistrer un score
    public function create($points, $temps_total, $id_stagiaire, $id_quiz) {
        $query = "INSERT INTO " . $this->table . " (points, temps_total, id_stagiaire, id_quiz) 
                 VALUES (:points, :temps_total, :id_stagiaire, :id_quiz)";
        
        $params = [
            ':points' => $points,
            ':temps_total' => $temps_total,
            ':id_stagiaire' => $id_stagiaire,
            ':id_quiz' => $id_quiz
        ];
        
        return $this->db->insert($query, $params);
    }
    
    // Méthode pour obtenir tous les scores d'un stagiaire
    public function getByStudent($id_stagiaire) {
        $query = "SELECT s.*, q.titre_quiz, 
                        (SELECT COUNT(*) FROM QuizQuestion WHERE id_quiz = q.id_quiz) as nombre_questions
                 FROM " . $this->table . " s
                 JOIN Quiz q ON s.id_quiz = q.id_quiz
                 WHERE s.id_stagiaire = :id_stagiaire
                 ORDER BY s.date DESC";
        
        $params = [':id_stagiaire' => $id_stagiaire];
        
        return $this->db->select($query, $params);
    }
    
    // Méthode pour obtenir tous les scores pour un quiz
    public function getByQuiz($id_quiz) {
        $query = "SELECT s.*, u.nom, u.prenom, 
                    (SELECT COUNT(*) FROM QuizQuestion WHERE id_quiz = s.id_quiz) as nombre_questions
             FROM " . $this->table . " s
             JOIN Utilisateur u ON s.id_stagiaire = u.id_utilisateur
             WHERE s.id_quiz = :id_quiz
             ORDER BY s.points DESC, s.temps_total ASC";
    
         $params = [':id_quiz' => $id_quiz];
    
    return $this->db->select($query, $params);
}
    
    // Méthode pour obtenir le meilleur score d'un stagiaire sur un quiz
    public function getBestScore($id_stagiaire, $id_quiz) {
        $query = "SELECT * FROM " . $this->table . " 
                 WHERE id_stagiaire = :id_stagiaire AND id_quiz = :id_quiz
                 ORDER BY points DESC, temps_total ASC
                 LIMIT 1";
        
        $params = [
            ':id_stagiaire' => $id_stagiaire,
            ':id_quiz' => $id_quiz
        ];
        
        return $this->db->selectOne($query, $params);
    }
    
    // Méthode pour obtenir les statistiques d'un stagiaire
    public function getStudentStats($id_stagiaire) {
        // Nombre total de quiz joués
        $queryQuizCount = "SELECT COUNT(DISTINCT id_quiz) as quiz_count 
                          FROM " . $this->table . " 
                          WHERE id_stagiaire = :id_stagiaire";
        
        // Score moyen
        $queryAvgScore = "SELECT AVG(points) as avg_score 
                         FROM " . $this->table . " 
                         WHERE id_stagiaire = :id_stagiaire";
        
        // Meilleur score
        $queryBestScore = "SELECT MAX(points) as best_score 
                          FROM " . $this->table . " 
                          WHERE id_stagiaire = :id_stagiaire";
        
        $params = [':id_stagiaire' => $id_stagiaire];
        
        $quizCount = $this->db->selectOne($queryQuizCount, $params);
        $avgScore = $this->db->selectOne($queryAvgScore, $params);
        $bestScore = $this->db->selectOne($queryBestScore, $params);
        
        return [
            'quiz_count' => $quizCount ? $quizCount['quiz_count'] : 0,
            'avg_score' => $avgScore ? $avgScore['avg_score'] : 0,
            'best_score' => $bestScore ? $bestScore['best_score'] : 0
        ];
    }
    
    // Méthode pour obtenir les statistiques globales des quiz
    public function getGlobalStats() {
        // Nombre total de quiz joués
        $queryTotalAttempts = "SELECT COUNT(*) as total_attempts FROM " . $this->table;
        
        // Score moyen global
        $queryGlobalAvg = "SELECT AVG(points) as global_avg FROM " . $this->table;
        
        // Quiz le plus joué
        $queryMostPlayed = "SELECT q.id_quiz, q.titre_quiz, COUNT(*) as play_count 
                           FROM " . $this->table . " s
                           JOIN Quiz q ON s.id_quiz = q.id_quiz
                           GROUP BY q.id_quiz, q.titre_quiz
                           ORDER BY play_count DESC
                           LIMIT 1";
        
        $totalAttempts = $this->db->selectOne($queryTotalAttempts);
        $globalAvg = $this->db->selectOne($queryGlobalAvg);
        $mostPlayed = $this->db->selectOne($queryMostPlayed);
        
        return [
            'total_attempts' => $totalAttempts ? $totalAttempts['total_attempts'] : 0,
            'global_avg' => $globalAvg ? $globalAvg['global_avg'] : 0,
            'most_played' => $mostPlayed ? $mostPlayed : null
        ];
    }
    
    // Méthode pour obtenir le classement des stagiaires
    public function getStudentRanking($limit = 10) {
        $query = "SELECT u.id_utilisateur, u.nom, u.prenom, 
                         COUNT(DISTINCT s.id_quiz) as quiz_count, 
                         AVG(s.points) as avg_score,
                         MAX(s.points) as best_score
                  FROM Utilisateur u
                  JOIN " . $this->table . " s ON u.id_utilisateur = s.id_stagiaire
                  WHERE u.type_utilisateur = 'stagiaire'
                  GROUP BY u.id_utilisateur, u.nom, u.prenom
                  ORDER BY avg_score DESC, quiz_count DESC
                  LIMIT :limit";
        
        $params = [':limit' => $limit];
        
        return $this->db->select($query, $params);
    }
}