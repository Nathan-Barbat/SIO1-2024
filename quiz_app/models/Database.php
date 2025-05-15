<?php
// models/Database.php
// Classe pour gérer les connexions à la base de données

class Database {
    private static $instance = null;
    private $conn;
    
    // Constructeur privé (pattern Singleton)
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, PDO_OPTIONS);
        } catch (PDOException $e) {
            // Gérer l'erreur de connexion
            if (ENV === 'development') {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            } else {
                die("Erreur de connexion à la base de données. Contactez l'administrateur.");
            }
        }
    }
    
    // Méthode pour obtenir l'instance unique de la classe
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    // Méthode pour obtenir la connexion
    public function getConnection() {
        return $this->conn;
    }
    
    // Méthode pour les requêtes SELECT
    public function select($query, $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->handleError($e, $query);
            return false;
        }
    }
    
    // Méthode pour trouver un seul enregistrement
    public function selectOne($query, $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->handleError($e, $query);
            return false;
        }
    }
    
    // Méthode pour les requêtes INSERT
    public function insert($query, $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            $this->handleError($e, $query);
            return false;
        }
    }
    
    // Méthode pour les requêtes UPDATE
    public function update($query, $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute($params);
            return $result;
        } catch (PDOException $e) {
            $this->handleError($e, $query);
            return false;
        }
    }
    
    // Méthode pour les requêtes DELETE
    public function delete($query, $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute($params);
            return $result;
        } catch (PDOException $e) {
            $this->handleError($e, $query);
            return false;
        }
    }
    
    // Méthode pour compter les résultats
    public function count($query, $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->handleError($e, $query);
            return false;
        }
    }
    
    // Méthode pour démarrer une transaction
    public function beginTransaction() {
        return $this->conn->beginTransaction();
    }
    
    // Méthode pour valider une transaction
    public function commit() {
        return $this->conn->commit();
    }
    
    // Méthode pour annuler une transaction
    public function rollBack() {
        return $this->conn->rollBack();
    }
    
    // Méthode privée pour gérer les erreurs de base de données
    private function handleError($e, $query) {
        if (ENV === 'development') {
            echo "Erreur SQL : " . $e->getMessage() . "<br>";
            echo "Requête : " . $query;
        } else {
            // En production, logger l'erreur au lieu de l'afficher
            error_log("Erreur SQL : " . $e->getMessage() . " - Requête : " . $query);
        }
    }
}