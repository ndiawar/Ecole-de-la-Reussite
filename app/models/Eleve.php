<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../config/config.php';

class Eleve {

private $pdo;

public function __construct() {
    $db = new Database();
    $this->pdo = $db->getPDO();
}

// Archiver un élève
public function archive($id) {
    $query = "UPDATE eleve SET statut_compte = 'Inactif' WHERE id_eleve = :id";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

// Récupérer la liste des élèves archivés
public function getArchivedEleves() {
    $query = "SELECT * FROM eleve WHERE statut_compte = 'Inactif'";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute();
    // Retourne tous les élèves archivés
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Restaurer un élève archivé
public function restore($id) {
    $query = "UPDATE eleve SET statut_compte = 'Actif' WHERE id_eleve = :id";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}


// Récupérer un élève par son matricule ou son email
public function findByMatriculeOrEmail($identifiant) {
    $stmt = $this->pdo->prepare("
        SELECT * FROM eleve 
        WHERE matricule = :identifiant OR email = :identifiant
    ");
    $stmt->bindParam(':identifiant', $identifiant);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

// Récupérer les élèves actifs avec pagination
public function getElevesWithPagination($start, $limit) {
    $stmt = $this->pdo->prepare("SELECT * FROM eleve WHERE statut_compte = 'Actif' LIMIT :start, :limit");
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Compter le nombre total d'élèves actifs
public function countEleves() {
    $stmt = $this->pdo->query("SELECT COUNT(*) FROM eleve WHERE statut_compte = 'Actif'");
    return $stmt->fetchColumn();
}

// Récupérer le nom d'un élève par son ID
public function getNomEleve($id) {
    $query = "SELECT nom FROM eleve WHERE id_eleve = :id";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchColumn(); // Retourne le nom de l'élève
}



public function getEleves() {
    $sql = "SELECT * FROM eleve";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getEleveById($id) {
    $sql = "SELECT * FROM eleve WHERE id_eleve = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getInscriptions() {
    $sql = "SELECT i.*, e.nom, e.prenom FROM inscription i JOIN eleve e ON i.id_eleve = e.id_eleve";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// public function getPaiementsByEleveId($eleveId) {
//     $sql = "SELECT * FROM paiement WHERE id_eleve = :id_eleve";
//     $stmt = $this->pdo->prepare($sql);
//     $stmt->execute(['id_eleve' => $eleveId]);
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

}
