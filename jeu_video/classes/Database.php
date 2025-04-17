<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "jeu_video";
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Erreur de connexion: " . $e->getMessage();
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>