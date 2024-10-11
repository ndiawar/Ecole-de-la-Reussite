<?php
require_once '../config/config.php';

class EleveModel
{
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getPDO();
    }


public function ajouterEleve($data) {
    // Valider les données
    $errors = $this->validerDonnees($data);
    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }

    $this->pdo->beginTransaction();
    try {
        // Ajouter le tuteur
        $sqlTuteur = "INSERT INTO tuteur (nom, prenom, telephone, adresse, email) VALUES (:nom, :prenom, :telephone, :adresse, :email)";
        $stmtTuteur = $this->pdo->prepare($sqlTuteur);
        $stmtTuteur->execute([
            ':nom' => $data['tuteur_nom'],
            ':prenom' => $data['tuteur_prenom'],
            ':telephone' => $data['tuteur_telephone'],
            ':adresse' => $data['tuteur_adresse'],
            ':email' => $data['tuteur_email']
        ]);

        $idTuteur = $this->pdo->lastInsertId();

        // Générer un matricule auto
        $matricule = $this->genererMatricule(); // Appelle une fonction pour générer le matricule

        // Ajouter l'élève
        $sqlEleve = "INSERT INTO eleve (nom, prenom, matricule, adresse, email, telephone, date_naissance, Classe_id_classe, Tuteur_id_tuteur) 
                    VALUES (:nom, :prenom, :matricule, :adresse, :email, :telephone, :date_naissance, :classe_id, :tuteur_id)";
        $stmtEleve = $this->pdo->prepare($sqlEleve);
        $stmtEleve->execute([
            ':nom' => $data['eleve_nom'],
            ':prenom' => $data['eleve_prenom'],
            ':matricule' => $matricule,
            ':adresse' => $data['eleve_adresse'],
            ':email' => $data['eleve_email'],
            ':telephone' => $data['eleve_telephone'],
            ':date_naissance' => $data['eleve_date_naissance'],
            ':classe_id' => $data['classe_id'],
            ':tuteur_id' => $idTuteur
        ]);

        $this->pdo->commit();
        return ['success' => true];
    } catch (Exception $e) {
        $this->pdo->rollBack();
        return ['success' => false, 'errors' => ['Une erreur est survenue : ' . $e->getMessage()]];
    }
}

// Exemple de fonction pour générer le matricule
private function genererMatricule() {
    // Logique pour générer un matricule unique
    $prefix = 'ELE'; // Préfixe pour l'élève
    $suffixed = uniqid($prefix); // Ajoute un identifiant unique
    return $suffixed;
}

    
    public function getClasses() {
        $sqlClasse = "SELECT id_classe, nom_classe FROM classe";
        $stmtClasse = $this->pdo->prepare($sqlClasse);
        
        try {
            $stmtClasse->execute();
            return $stmtClasse->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return []; // Retourner un tableau vide en cas d'erreur
        }
    }
    
    // Fonction de validation des données
    public function validerDonnees($data) {
        $errors = [];
    
        // Validation du tuteur
        if (empty($data['tuteur_nom']) || !preg_match("/^[a-zA-ZÀ-ÿ' ]+$/", $data['tuteur_nom'])) {
            $errors[] = "Le nom du tuteur est obligatoire et ne doit contenir que des lettres.";
        }
        if (empty($data['tuteur_prenom']) || !preg_match("/^[a-zA-ZÀ-ÿ' ]+$/", $data['tuteur_prenom'])) {
            $errors[] = "Le prénom du tuteur est obligatoire et ne doit contenir que des lettres.";
        }
        if (empty($data['tuteur_telephone']) || !preg_match("/^[0-9]{9}$/", $data['tuteur_telephone']) || $data['tuteur_telephone'] < 750000000 || $data['tuteur_telephone'] > 789999999) {
            $errors[] = "Le téléphone du tuteur doit être un nombre de 9 chiffres dans la plage 750000000 à 789999999.";
        }
        if (empty($data['tuteur_adresse'])) {
            $errors[] = "L'adresse du tuteur est obligatoire.";
        }
        if (!empty($data['tuteur_email']) && !filter_var($data['tuteur_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'adresse email du tuteur n'est pas valide.";
        }
    
        // Validation de l'élève
        if (empty($data['eleve_nom']) || !preg_match("/^[a-zA-ZÀ-ÿ' ]+$/", $data['eleve_nom'])) {
            $errors[] = "Le nom de l'élève est obligatoire et ne doit contenir que des lettres.";
        }
        if (empty($data['eleve_prenom']) || !preg_match("/^[a-zA-ZÀ-ÿ' ]+$/", $data['eleve_prenom'])) {
            $errors[] = "Le prénom de l'élève est obligatoire et ne doit contenir que des lettres.";
        }

        if (empty($data['eleve_date_naissance'])) {
            $errors[] = "La date de naissance de l'élève est obligatoire.";
        }
        if (empty($data['classe_id'])) {
            $errors[] = "La classe de l'élève est obligatoire.";
        }
        // Valider que la classe existe
        $sqlClasse = "SELECT COUNT(*) FROM classe WHERE id_classe = :classe_id";
        $stmtClasse = $this->pdo->prepare($sqlClasse);
        $stmtClasse->execute([':classe_id' => $data['classe_id']]);
        if ($stmtClasse->fetchColumn() == 0) {
            $errors[] = "La classe sélectionnée n'existe pas.";
        }

        return $errors;
    }
    // Autres méthodes CRUD à implémenter (éditer, supprimer, afficher les élèves)...

    public function getElevesWithPagination($start, $limit)
    {
        $sql = "SELECT e.id_eleve, e.nom AS eleve_nom, e.prenom AS eleve_prenom, e.matricule, 
                       e.adresse AS eleve_adresse, e.email AS eleve_email, e.telephone AS eleve_telephone, 
                       e.date_naissance, c.nom_classe, t.nom AS tuteur_nom, t.prenom AS tuteur_prenom 
                FROM eleve e
                JOIN classe c ON e.Classe_id_classe = c.id_classe
                JOIN tuteur t ON e.Tuteur_id_tuteur = t.id_tuteur
                LIMIT :start, :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countEleves()
    {
        $sql = "SELECT COUNT(*) FROM eleve";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchColumn();
    }
    

    public function afficherEleveParId($id)
    {
        $sql = "SELECT e.id_eleve, e.nom AS eleve_nom, e.prenom AS eleve_prenom, e.matricule, e.adresse AS eleve_adresse, 
                    e.email AS eleve_email, e.telephone AS eleve_telephone, e.date_naissance,
                    c.nom_classe, t.nom AS tuteur_nom, t.prenom AS tuteur_prenom, t.telephone AS tuteur_telephone
                FROM eleve e
                JOIN classe c ON e.Classe_id_classe = c.id_classe
                JOIN tuteur t ON e.Tuteur_id_tuteur = t.id_tuteur
                WHERE e.id_eleve = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function modifierEleve($id, $data)
    {
        $errors = $this->validerDonnees($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
    
        // Démarrer une transaction
        $this->pdo->beginTransaction();
        try {
            // Mettre à jour les informations du tuteur
            $sqlTuteur = "UPDATE tuteur SET nom = :nom, prenom = :prenom, telephone = :telephone, adresse = :adresse, email = :email 
                        WHERE id_tuteur = (SELECT Tuteur_id_tuteur FROM eleve WHERE id_eleve = :id)";
            $stmtTuteur = $this->pdo->prepare($sqlTuteur);
            $stmtTuteur->execute([
                ':nom' => $data['tuteur_nom'],
                ':prenom' => $data['tuteur_prenom'],
                ':telephone' => $data['tuteur_telephone'],
                ':adresse' => $data['tuteur_adresse'],
                ':email' => $data['tuteur_email'],
                ':id' => $id
            ]);
    
            // Mettre à jour les informations de l'élève
            $sqlEleve = "UPDATE eleve SET nom = :nom, prenom = :prenom, adresse = :adresse, 
                        email = :email, telephone = :telephone, date_naissance = :date_naissance, Classe_id_classe = :classe_id 
                        WHERE id_eleve = :id";
            $stmtEleve = $this->pdo->prepare($sqlEleve);
            $stmtEleve->execute([
                ':nom' => $data['eleve_nom'],
                ':prenom' => $data['eleve_prenom'],
                ':adresse' => $data['eleve_adresse'],
                ':email' => $data['eleve_email'],
                ':telephone' => $data['eleve_telephone'],
                ':date_naissance' => $data['eleve_date_naissance'],
                ':classe_id' => $data['classe_id'], // Assurez-vous que cela correspond à votre logique
                ':id' => $id
            ]);
    
            // Confirmer la transaction
            $this->pdo->commit();
            return ['success' => true];
    
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->pdo->rollBack();
            return ['success' => false, 'errors' => ['Une erreur est survenue : ' . $e->getMessage()]];
        }
    }
    
    public function supprimerEleve($id)
    {
        // Vérification initiale pour éviter de démarrer une transaction si l'élève n'a pas de tuteur
        $sqlTuteurId = "SELECT Tuteur_id_tuteur FROM eleve WHERE id_eleve = :id";
        $stmtTuteurId = $this->pdo->prepare($sqlTuteurId);
        $stmtTuteurId->execute([':id' => $id]);
        $idTuteur = $stmtTuteurId->fetchColumn();

        if (!$idTuteur) {
            return ['success' => false, 'errors' => ['L\'élève n\'existe pas ou n\'a pas de tuteur associé.']];
        }

        $this->pdo->beginTransaction();
        try {
            // Supprimer l'élève
            $sqlEleve = "DELETE FROM eleve WHERE id_eleve = :id";
            $stmtEleve = $this->pdo->prepare($sqlEleve);
            $stmtEleve->execute([':id' => $id]);

            // Supprimer le tuteur associé
            $sqlTuteur = "DELETE FROM tuteur WHERE id_tuteur = :tuteur_id";
            $stmtTuteur = $this->pdo->prepare($sqlTuteur);
            $stmtTuteur->execute([':tuteur_id' => $idTuteur]);

            $this->pdo->commit();
            return ['success' => true];

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ['success' => false, 'errors' => ['Une erreur est survenue : ' . $e->getMessage()]];
        }
    }

}
