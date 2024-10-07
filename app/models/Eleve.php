
<?php
require_once '../config/config.php'; // S'assurer que la configuration est incluse ici

class Eleve {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllEleves() {
        $query = "SELECT nom, prenom, niveau, tuteur, telephone_tuteur, matricule FROM eleve";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
