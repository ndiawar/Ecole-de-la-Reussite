<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../config/config.php';

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

    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getPDO();
    }

    // Créer un reçu
    public function creerRecu($id_inscription, $montant_paye, $moyen_paiement) {
        $date_recu = date('Y-m-d');
        $sql = "INSERT INTO recu (date_recu, montant_paye, moyen_paiement, id_inscription) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$date_recu, $montant_paye, $moyen_paiement, $id_inscription]);

        return $this->pdo->lastInsertId();
    }

    // Récupérer les reçus d'un élève
    public function getRecusParEleve($id_eleve) {
        $sql = "SELECT * FROM recu r JOIN inscription i ON r.id_inscription = i.id_inscription WHERE i.id_eleve = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_eleve]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les détails d'un reçu
    public function getDetailsRecu($id_recu) {
        $sql = "SELECT * FROM recu WHERE id_recu = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_recu]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour un reçu
    public function mettreAJourRecu($id_recu, $nouveau_montant, $nouveau_moyen) {
        $sql = "UPDATE recu SET montant_paye = ?, moyen_paiement = ? WHERE id_recu = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nouveau_montant, $nouveau_moyen, $id_recu]);
    }

    // Annuler un reçu
    public function annulerRecu($id_recu) {
        $sql = "DELETE FROM recu WHERE id_recu = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_recu]);
    }

    // Créer un bulletin de salaire
    public function creerBulletinSalaire($id_employe, $nombre_heures, $type_salaire) {
        $salaire = $this->getSalaireEmploye($id_employe, $type_salaire);
        $montant_HT = $salaire * $nombre_heures;
        $taux_TVA = 0.20; // Par exemple, 20% de TVA
        $montant_TTC = $montant_HT + ($montant_HT * $taux_TVA);
        $date_bulletin = date('Y-m-d');

        $sql = "INSERT INTO bulletin_de_salaire (date_bulletin, nombre_heures, montant_HT, taux_TVA, montant_TTC, id_salaire) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$date_bulletin, $nombre_heures, $montant_HT, $taux_TVA, $montant_TTC, $id_employe]);

        return $this->pdo->lastInsertId();
    }

    // Récupérer tous les bulletins de salaire d'un employé
    public function getBulletinsParEmploye($id_employe) {
        $sql = "SELECT * FROM bulletin_de_salaire WHERE id_salaire = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_employe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les détails d'un bulletin de salaire
    public function getDetailsBulletinSalaire($id_bulletin) {
        $sql = "SELECT * FROM bulletin_de_salaire WHERE id_bulletin = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_bulletin]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour un bulletin de salaire
    public function mettreAJourBulletinSalaire($id_bulletin, $nouveau_nombre_heures) {
        $sql = "UPDATE bulletin_de_salaire SET nombre_heures = ? WHERE id_bulletin = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nouveau_nombre_heures, $id_bulletin]);
    }

    // Annuler un bulletin de salaire
    public function annulerBulletinSalaire($id_bulletin) {
        $sql = "DELETE FROM bulletin_de_salaire WHERE id_bulletin = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_bulletin]);
    }

    // Obtenir des statistiques sur les paiements
    public function getStatistiquesPaiements() {
        $sql = "SELECT COUNT(*) as total_paiements, SUM(montant_paye) as total_montant FROM recu";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupère tous les élèves de la base de données
    public function getAll() {
        $sql = "SELECT e.*, t.nom AS tuteur_nom, t.prenom AS tuteur_prenom
                FROM eleve e
                LEFT JOIN tuteur t ON e.Tuteur_id_tuteur = t.id_tuteur";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Recupérer l'ensemble des paiements
    public function getAllPaiements() {
        $sql = "SELECT 
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
                    m.montant AS eleve_mensualite_montant,
                    m.mois AS eleve_mensualite_mois,
                    e.prenom AS prenom_eleve,
                    e.nom AS nom_eleve,
                    e.date_naissance AS datenaissance_eleve
                FROM paiement p
                LEFT JOIN recu r ON p.id_recu = r.id_recu
                LEFT JOIN inscription i ON p.id_inscription = i.id_inscription
                LEFT JOIN bulletin_salaire b ON p.id_facture = b.id_facture
                LEFT JOIN personnel per ON p.id_personnel = per.id_personnel
                LEFT JOIN mensualite m ON p.id_mensualite = m.id_mensualite
                LEFT JOIN eleve e ON p.id_eleve = e.id_eleve";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer le salaire de l'employé selon le type de salaire
    private function getSalaireEmploye($id_employe, $type_salaire) {
        // Logique pour récupérer le salaire de l'employé (valeur fictive pour l'exemple)
        return 20; 
    }

    public function getEleveById($id_eleve) {
        $sql = "SELECT * FROM eleve WHERE id_eleve = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_eleve]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTuteurById($id_tuteur) {
        $sql = "SELECT * FROM tuteur WHERE id_tuteur = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_tuteur]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
