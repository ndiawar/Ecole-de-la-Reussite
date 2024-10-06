<?php
require_once '../config/config.php';

class Personnel {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getPDO();
    }


    public function getActifPersonnels() {
        $query = "SELECT * FROM personnel WHERE statut_compte = 'Actif'";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
   // Récupérer tous les personnels actifs
    public static function all() {
        $db = new Database();
        $pdo = $db->getPDO();
        $stmt = $pdo->prepare("SELECT * FROM personnel WHERE statut_compte = 'Actif'"); // Ajouter la condition pour le statut
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Récupérer un personnel par son ID
    public static function find($id) {
        $db = new Database();
        $pdo = $db->getPDO();
        $stmt = $pdo->prepare("SELECT * FROM personnel WHERE id_personnel = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Ajouter un nouvel personnel
    public function create($nom, $prenom, $email, $telephone, $matricule, $mot_passe, $sexe, $role, $statut_compte, $id_salaire, $derniere_connexion) {
        // Vérification des champs obligatoires
        if (empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($matricule) || empty($mot_passe) || empty($sexe) || empty($role) || empty($statut_compte) || empty($id_salaire)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }
    
        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'adresse email n'est pas valide.");
        }
    
        // Validation du numéro de téléphone (doit avoir 9 chiffres)
        if (!preg_match('/^[0-9]{9}$/', $telephone)) {
            throw new Exception("Le numéro de téléphone doit contenir 9 chiffres.");
        }
    
        $hashedPassword = password_hash($mot_passe, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO personnel (nom, prenom, email, telephone, matricule, mot_passe, sexe, role, statut_compte, id_salaire, derniere_connexion) VALUES (:nom, :prenom, :email, :telephone, :matricule, :mot_passe, :sexe, :role, :statut_compte, :id_salaire, :derniere_connexion)");
    
        return $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':telephone' => $telephone,
            ':matricule' => $matricule,
            ':mot_passe' => $hashedPassword,
            ':sexe' => $sexe,
            ':role' => $role,
            ':statut_compte' => $statut_compte,
            ':id_salaire' => $id_salaire,
            ':derniere_connexion' => $derniere_connexion // Ajout de derniere_connexion
        ]);
    }
    
        // Mise à jour un personnel
    public function update($id, $nom, $prenom, $email, $telephone, $matricule, $sexe, $role, $statut_compte, $id_salaire) {
        // Vérification des champs obligatoires
        if (empty($id) || empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($matricule) || empty($sexe) || empty($role) || empty($statut_compte) || empty($id_salaire)) {
            throw new Exception("Tous les champs sont obligatoires.");
        }
    
        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'adresse email n'est pas valide.");
        }
    
        // Validation du numéro de téléphone (doit avoir 9 chiffres)
        if (!preg_match('/^[0-9]{9}$/', $telephone)) {
            throw new Exception("Le numéro de téléphone doit contenir 9 chiffres.");
        }
    
        $stmt = $this->pdo->prepare("UPDATE personnel SET nom = :nom, prenom = :prenom, email = :email, telephone = :telephone, matricule = :matricule, sexe = :sexe, role = :role, statut_compte = :statut_compte, id_salaire = :id_salaire WHERE id_personnel = :id");
    
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':telephone' => $telephone,
            ':matricule' => $matricule,
            ':sexe' => $sexe,
            ':role' => $role,
            ':statut_compte' => $statut_compte,
            ':id_salaire' => $id_salaire
        ]);
    }
    
    // Supprimer un personnel
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM personnel WHERE id_personnel = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Archiver un personnel
    public function archive($id) {
        $query = "UPDATE personnel SET statut_compte = 'Inactif' WHERE id_personnel = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
    

    // Restaurer un personnel archivé
    public function restore($id) {
        $stmt = $this->pdo->prepare("UPDATE personnel SET statut_compte = 'actif' WHERE id_personnel = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Récupérer un personnel par son matricule
    public function findByMatricule($matricule) {
        $stmt = $this->pdo->prepare("SELECT * FROM personnel WHERE matricule = :matricule");
        $stmt->bindParam(':matricule', $matricule);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    // Récupérer un personnel par son matricule ou son email
    public function findByMatriculeOrEmail($identifiant) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM personnel 
            WHERE matricule = :matricule OR email = :email
        ");
        // Utiliser des paramètres distincts
        $stmt->bindParam(':matricule', $identifiant);
        $stmt->bindParam(':email', $identifiant);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    
      // Récupérer les personnels avec pagination
      public function getPersonnelWithPagination($start, $limit) {
        $sql = "SELECT * FROM personnel LIMIT :start, :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourner tous les personnels paginés
    }

    // Compter le nombre total de personnels
    public function countPersonnel() {
        $sql = "SELECT COUNT(*) as total FROM personnel";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total']; // Retourner le nombre total de personnels
    }
    public function getNomPersonnel($id) {
        $query = "SELECT nom FROM personnel WHERE id_personnel = :id"; // Ajustez la requête selon votre schéma
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchColumn(); // Retourne le nom du personnel
    }
    
}