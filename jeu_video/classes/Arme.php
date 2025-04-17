<?php
class Arme {
    private $id;
    private $type;
    private $nom;
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function getId() {
        return $this->id;
    }

    public function getType() {
        return $this->type;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getAll() {
        $query = "SELECT * FROM armes";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById($id) {
        $query = "SELECT * FROM armes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $this->id = $row['id'];
            $this->type = $row['type'];
            $this->nom = $row['nom'];
            return true;
        }
        return false;
    }
}
?>