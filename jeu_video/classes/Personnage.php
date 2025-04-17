<?php
class Personnage {
    private $id;
    private $nom;
    private $type;
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getType() {
        return $this->type;
    }

    // Créer un nouveau personnage
    public function create() {
        $query = "INSERT INTO personnages (nom, type) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->execute([$this->nom, $this->type]);
        $this->id = $this->conn->lastInsertId();
        
        return true;
    }

    // Mettre à jour un personnage
    public function update() {
        $query = "UPDATE personnages SET nom = ?, type = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([$this->nom, $this->type, $this->id]);
    }

    // Récupérer tous les personnages
    public function getAll() {
        $query = "SELECT * FROM personnages";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Récupérer un personnage par ID
    public function getById($id) {
        $query = "SELECT * FROM personnages WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $this->id = $row['id'];
            $this->nom = $row['nom'];
            $this->type = $row['type'];
            return true;
        }
        return false;
    }

    // Ajouter une arme au personnage
    public function ajouterArme($armeId) {
        $query = "INSERT INTO personnage_arme (id_personnage, id_arme) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id, $armeId]);
    }

    // Ajouter un pouvoir au personnage
    public function ajouterPouvoir($pouvoirId) {
        $query = "INSERT INTO personnage_pouvoir (id_personnage, id_pouvoir) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id, $pouvoirId]);
    }

    // Récupérer les armes du personnage
    public function getArmes() {
        $query = "SELECT a.* FROM armes a 
                  JOIN personnage_arme pa ON a.id = pa.id_arme
                  WHERE pa.id_personnage = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
        return $stmt;
    }

    // Récupérer les pouvoirs du personnage
    public function getPouvoirs() {
        $query = "SELECT p.* FROM pouvoir p 
                  JOIN personnage_pouvoir pp ON p.id = pp.id_pouvoir
                  WHERE pp.id_personnage = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$this->id]);
        return $stmt;
    }

    // Supprimer toutes les armes du personnage
    public function supprimerArmes() {
        $query = "DELETE FROM personnage_arme WHERE id_personnage = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id]);
    }

    // Supprimer tous les pouvoirs du personnage
    public function supprimerPouvoirs() {
        $query = "DELETE FROM personnage_pouvoir WHERE id_personnage = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$this->id]);
    }
}
?>