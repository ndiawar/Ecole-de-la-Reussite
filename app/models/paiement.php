<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Paiement {
    
    public $id_eleve;
    public $nom;
    public $prenom;
    public $matricule;
    public $adresse;
    public $email;
    public $telephone;
    public $date_naissance;
    public $niveau;
    public $enseignant_id;
    public $tuteur_id;
    public $classe_id;
    
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer un reçu
    public function creerRecu($id_inscription, $montant_paye, $moyen_paiement) {
        $date_recu = date('Y-m-d');
        $sql = "INSERT INTO recu (date_recu, montant_paye, moyen_paiement, id_inscription) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdsi", $date_recu, $montant_paye, $moyen_paiement, $id_inscription);

        return $stmt->execute() ? $this->conn->insert_id : false;
    }

    // Récupérer les reçus d'un élève
    public function getRecusParEleve($id_eleve) {
        $sql = "SELECT * FROM recu r JOIN inscription i ON r.id_inscription = i.id_inscription WHERE i.id_eleve = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_eleve);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Récupérer les détails d'un reçu
    public function getDetailsRecu($id_recu) {
        $sql = "SELECT * FROM recu WHERE id_recu = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_recu);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Mettre à jour un reçu
    public function mettreAJourRecu($id_recu, $nouveau_montant, $nouveau_moyen) {
        $sql = "UPDATE recu SET montant_paye = ?, moyen_paiement = ? WHERE id_recu = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("dsi", $nouveau_montant, $nouveau_moyen, $id_recu);
        $stmt->execute();
    }

    // Annuler un reçu
    public function annulerRecu($id_recu) {
        $sql = "DELETE FROM recu WHERE id_recu = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_recu);
        $stmt->execute();
    }

    // Créer un bulletin de salaire
    public function creerBulletinSalaire($id_employe, $nombre_heures, $type_salaire) {
        $salaire = $this->getSalaireEmploye($id_employe, $type_salaire);
        $montant_HT = $salaire * $nombre_heures;
        $taux_TVA = 0.20; // Par exemple, 20% de TVA
        $montant_TTC = $montant_HT + ($montant_HT * $taux_TVA);
        $date_bulletin = date('Y-m-d');

        $sql = "INSERT INTO bulletin_de_salaire (date_bulletin, nombre_heures, montant_HT, taux_TVA, montant_TTC, id_salaire) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sidddi", $date_bulletin, $nombre_heures, $montant_HT, $taux_TVA, $montant_TTC, $id_employe);

        return $stmt->execute() ? $this->conn->insert_id : false;
    }

    // Récupérer tous les bulletins de salaire d'un employé
    public function getBulletinsParEmploye($id_employe) {
        $sql = "SELECT * FROM bulletin_de_salaire WHERE id_salaire = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_employe);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Récupérer les détails d'un bulletin de salaire
    public function getDetailsBulletinSalaire($id_bulletin) {
        $sql = "SELECT * FROM bulletin_de_salaire WHERE id_bulletin = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_bulletin);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Mettre à jour un bulletin de salaire
    public function mettreAJourBulletinSalaire($id_bulletin, $nouveau_nombre_heures) {
        $sql = "UPDATE bulletin_de_salaire SET nombre_heures = ? WHERE id_bulletin = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $nouveau_nombre_heures, $id_bulletin);
        $stmt->execute();
    }

    // Annuler un bulletin de salaire
    public function annulerBulletinSalaire($id_bulletin) {
        $sql = "DELETE FROM bulletin_de_salaire WHERE id_bulletin = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_bulletin);
        $stmt->execute();
    }

    // Obtenir des statistiques sur les paiements
    public function getStatistiquesPaiements() {
        $sql = "SELECT COUNT(*) as total_paiements, SUM(montant_paye) as total_montant FROM recu";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    // Récupère tous les élèves de la base de données
    public function getAll() {
        // $db = Database::getInstance();
        $stmt = $conn->query("SELECT e.*, t.nom AS tuteur_nom, t.prenom AS tuteur_prenom
                            FROM eleve e
                            LEFT JOIN tuteur t ON e.Tuteur_id_tuteur = t.id_tuteur");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Recupérer l'ensemnle des paiements
    public function getAllPaiements() {
        $query = "
            SELECT 
                p.id_paiement,
                p.montant,
                p.date_paiement,
                p.moyen_paiement,
                p.type_paiement,
                r.date_recu,
                r.montant_paye,
                r.moyen_paiement AS recu_moyen_paiement,
                i.date_inscription,
                i.annee_scolaire,
                b.nombre_heures,
                b.date_bulletin,
                b.montant_HT,
                b.taux_TVA,
                b.montant_TTC,
                per.nom AS personnel_nom,
                per.prenom AS personnel_prenom,
                per.email AS personnel_email,
                per.matricule AS personnel_matricule,
                per.telephone AS personnel_telephone

            FROM paiement p
            LEFT JOIN recu r ON p.id_recu = r.id_recu
            LEFT JOIN inscription i ON p.id_inscription = i.id_inscription
            LEFT JOIN bulletin_salaire b ON p.id_facture = b.id_facture
            LEFT JOIN personnel per ON p.id_personnel = per.id_personnel
        "; // Requête SQL avec jointures
    
        $result = $this->conn->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC); // Retourne un tableau associatif
    }
    
    

    // Récupérer le salaire de l'employé selon le type de salaire
    private function getSalaireEmploye($id_employe, $type_salaire) {
        // Logique pour récupérer le salaire de l'employé
        return 20; // Valeur fictive pour l'exemple
    }


    public function getNomPersonnel($id_paiement) {
        $this->db->query("SELECT * FROM paiement WHERE id_personnel = :id_personnel");
        $this->db->bind(':id_personnel', $id_personnel);
        return $this->db->single();
    }
    
    
    public function getTuteurById($id_tuteur) {
        $this->db->query("SELECT * FROM tuteur WHERE id_tuteur = :id_tuteur");
        $this->db->bind(':id_tuteur', $id_tuteur);
        return $this->db->single();
    }


     // Récupérer tous les paiements
     public function getAllPaiement() {
        $query = "SELECT * FROM paiement WHERE archive = 0";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Archiver un paiement
    public function archive($id) {
        $query = "UPDATE paiement SET archive = 1 WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

 

    // Restaurer un paiement archivé
    public function restore($id) {
        $query = "UPDATE paiement SET archive = 0 WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

   
    
}
