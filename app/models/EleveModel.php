
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
        $matricule = $this->genererMatricule($data['eleve_nom'], $data['eleve_prenom']);


        // Ajouter l'élève
        $sqlEleve = "INSERT INTO eleve (nom, prenom, matricule, adresse, email, sexe, date_naissance, Classe_id_classe, Tuteur_id_tuteur) 
                    VALUES (:nom, :prenom, :matricule, :adresse, :email, :sexe, :date_naissance, :Classe_id_classe, :Tuteur_id_tuteur)";
        $stmtEleve = $this->pdo->prepare($sqlEleve);
        $stmtEleve->execute([
            ':nom' => $data['eleve_nom'],
            ':prenom' => $data['eleve_prenom'],
            ':matricule' => $matricule,
            ':adresse' => $data['eleve_adresse'],
            ':email' => $data['eleve_email'],
            ':sexe' => $data['eleve_sexe'],
            ':date_naissance' => $data['eleve_date_naissance'],
            ':Classe_id_classe' => $data['classe_id'],
            ':Tuteur_id_tuteur' => $idTuteur
        ]);
        
        $this->pdo->commit();
        return ['success' => true];
    } catch (Exception $e) {
        $this->pdo->rollBack();
        return ['success' => false, 'errors' => ['Une erreur est survenue : ' . $e->getMessage()]];
    }
}

// Exemple de fonction pour générer le matricule
private function genererMatricule($nom, $prenom) {
    $prefixNom = strtoupper(substr($nom ?? '', 0, 2)); // Utilise une chaîne vide si null
    $prefixPrenom = strtoupper(substr($prenom ?? '', 0, 2)); // Utilise une chaîne vide si null

    // Récupérer les deux derniers chiffres de l'année actuelle
    $anneeActuelle = date('y');

    // Générer trois chiffres uniques (de 000 à 999)
    $chiffresUniques = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

    // Combiner le tout pour créer le matricule
    $matricule = $prefixNom . $prefixPrenom . $anneeActuelle . $chiffresUniques;

    return $matricule;
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
        if (!isset($data['tuteur_nom']) || empty($data['tuteur_nom']) || !preg_match("/^[a-zA-ZÀ-ÿ' ]+$/", $data['tuteur_nom'])) {
            $errors[] = "Le nom du tuteur est obligatoire et ne doit contenir que des lettres.";
        }
        if (!isset($data['tuteur_prenom']) || empty($data['tuteur_prenom']) || !preg_match("/^[a-zA-ZÀ-ÿ' ]+$/", $data['tuteur_prenom'])) {
            $errors[] = "Le prénom du tuteur est obligatoire et ne doit contenir que des lettres.";
        }
        if (!isset($data['tuteur_telephone']) || !preg_match("/^[0-9]{9}$/", $data['tuteur_telephone']) || $data['tuteur_telephone'] < 750000000 || $data['tuteur_telephone'] > 789999999) {
            $errors[] = "Le téléphone du tuteur doit être un nombre de 9 chiffres dans la plage 750000000 à 789999999.";
        }
        if (!isset($data['tuteur_adresse']) || empty($data['tuteur_adresse'])) {
            $errors[] = "L'adresse du tuteur est obligatoire.";
        }
        if (!empty($data['tuteur_email']) && !filter_var($data['tuteur_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'adresse email du tuteur n'est pas valide.";
        }
    
        // Validation de l'élève
        if (!isset($data['eleve_nom']) || empty($data['eleve_nom']) || !preg_match("/^[a-zA-ZÀ-ÿ' ]+$/", $data['eleve_nom'])) {
            $errors[] = "Le nom de l'élève est obligatoire et ne doit contenir que des lettres.";
        }
        if (!isset($data['eleve_prenom']) || empty($data['eleve_prenom']) || !preg_match("/^[a-zA-ZÀ-ÿ' ]+$/", $data['eleve_prenom'])) {
            $errors[] = "Le prénom de l'élève est obligatoire et ne doit contenir que des lettres.";
        }
        if (!isset($data['eleve_date_naissance']) || empty($data['eleve_date_naissance'])) {
            $errors[] = "La date de naissance de l'élève est obligatoire.";
        } else {
            // Validation du format de la date
            $date = DateTime::createFromFormat('Y-m-d', $data['eleve_date_naissance']);
            if (!$date || $date->format('Y-m-d') !== $data['eleve_date_naissance']) {
                $errors[] = "La date de naissance de l'élève doit être au format YYYY-MM-DD.";
            }
        }
        if (!isset($data['classe_id']) || empty($data['classe_id'])) {
            $errors[] = "La classe de l'élève est obligatoire.";
        } else {
            // Valider que la classe existe
            $sqlClasse = "SELECT COUNT(*) FROM classe WHERE id_classe = :classe_id";
            $stmtClasse = $this->pdo->prepare($sqlClasse);
            $stmtClasse->execute([':classe_id' => $data['classe_id']]);
            if ($stmtClasse->fetchColumn() == 0) {
                $errors[] = "La classe sélectionnée n'existe pas.";
            }
        }
    
        return $errors;
    }
    
   public function getElevesWithPagination($start, $limit)
{
    $sql = "SELECT e.id_eleve, e.nom AS eleve_nom, e.prenom AS eleve_prenom, e.matricule, 
                   e.adresse AS eleve_adresse, e.email AS eleve_email, e.sexe AS eleve_sexe, 
                   e.date_naissance, c.nom_classe, t.nom AS tuteur_nom, t.prenom AS tuteur_prenom, t.email AS tuteur_email, t.telephone AS tuteur_telephone 
            FROM eleve e
            JOIN classe c ON e.Classe_id_classe = c.id_classe
            JOIN tuteur t ON e.Tuteur_id_tuteur = t.id_tuteur
            WHERE e.archive = 0
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
        e.email AS eleve_email, e.sexe AS eleve_sexe, e.date_naissance,
        c.nom_classe, t.nom AS tuteur_nom, t.prenom AS tuteur_prenom, t.telephone AS tuteur_telephone, t.adresse AS tuteur_adresse, t.email AS tuteur_email
        FROM eleve e
        JOIN classe c ON e.Classe_id_classe = c.id_classe
        JOIN tuteur t ON e.Tuteur_id_tuteur = t.id_tuteur
         WHERE e.id_eleve = :id";
 
         $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
         return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    /*public function modifierEleve($id, $data)
    {
        // Valider les données de l'élève et du tuteur
        $errors = $this->validerDonnees($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
    
        // Vérifier si 'id_classe' est défini dans le tableau $data
        if (empty($data['id_classe'])) {
            return ['success' => false, 'errors' => ['La classe doit être sélectionnée']];
        }
    
        // Démarrer une transaction
        $this->pdo->beginTransaction();
    
        try {
            // Mise à jour des données de l'élève
            $stmt = $this->pdo->prepare("
                UPDATE eleve 
                SET nom = :eleve_nom, 
                    prenom = :eleve_prenom, 
                    email = :eleve_email, 
                    sexe = :eleve_sexe, 
                    adresse = :eleve_adresse, 
                    date_naissance = :eleve_date_naissance, 
                    Classe_id_classe = :id_classe 
                WHERE id_eleve = :id_eleve
            ");
            $stmt->execute([
                ':eleve_nom' => $data['eleve_nom'],
                ':eleve_prenom' => $data['eleve_prenom'],
                ':eleve_email' => $data['eleve_email'],
                ':eleve_sexe' => $data['eleve_sexe'],
                ':eleve_adresse' => $data['eleve_adresse'],
                ':eleve_date_naissance' => $data['eleve_date_naissance'],
                ':Classe_id_classe' => $data['id_classe'],  // Vérifié que 'id_classe' n'est pas null
                ':id_eleve' => $id
            ]);
    
            // Mise à jour des données du tuteur
            $stmt = $this->pdo->prepare("
                UPDATE tuteur 
                SET nom = :tuteur_nom, 
                    prenom = :tuteur_prenom, 
                    telephone = :tuteur_telephone, 
                    email = :tuteur_email, 
                    adresse = :tuteur_adresse 
                WHERE id_tuteur = :tuteur_id
            ");
            $stmt->execute([
                ':tuteur_nom' => $data['tuteur_nom'],
                ':tuteur_prenom' => $data['tuteur_prenom'],
                ':tuteur_telephone' => $data['tuteur_telephone'],
                ':tuteur_email' => $data['tuteur_email'],
                ':tuteur_adresse' => $data['tuteur_adresse'],
                ':tuteur_id' => $data['Tuteur_id_tuteur']
            ]);
    
            // Valider la transaction
            $this->pdo->commit();
            return ['success' => true];
    
        } catch (Exception $e) {
            // En cas d'erreur, annuler la transaction et afficher un message détaillé
            $this->pdo->rollBack();
            return ['success' => false, 'errors' => ['Erreur lors de la mise à jour : ' . $e->getMessage()]];
        }
    }
    */


public function modifierEleve($data) {
    // Valider les données
    $errors = $this->validerDonnees($data);
    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }

    $this->pdo->beginTransaction();
    try {
        // Vérifier si le tuteur existe déjà
        $sqlTuteur = "SELECT id_tuteur FROM tuteur WHERE id_tuteur = :id_tuteur";
        $stmtTuteur = $this->pdo->prepare($sqlTuteur);
        $stmtTuteur->execute([':id_tuteur' => $data['id_tuteur']]);
        $tuteurExists = $stmtTuteur->fetchColumn();

        if ($tuteurExists) {
            // Mettre à jour le tuteur existant
            $sqlUpdateTuteur = "UPDATE tuteur SET nom = :nom, prenom = :prenom, telephone = :telephone, adresse = :adresse, email = :email WHERE id_tuteur = :id_tuteur";
            $stmtUpdateTuteur = $this->pdo->prepare($sqlUpdateTuteur);
            $stmtUpdateTuteur->execute([
                ':nom' => $data['tuteur_nom'],
                ':prenom' => $data['tuteur_prenom'],
                ':telephone' => $data['tuteur_telephone'],
                ':adresse' => $data['tuteur_adresse'],
                ':email' => $data['tuteur_email'],
                ':id_tuteur' => $data['id_tuteur']
            ]);
        } else {
            // Ajouter le tuteur s'il n'existe pas
            $sqlTuteurInsert = "INSERT INTO tuteur (nom, prenom, telephone, adresse, email) VALUES (:nom, :prenom, :telephone, :adresse, :email)";
            $stmtTuteurInsert = $this->pdo->prepare($sqlTuteurInsert);
            $stmtTuteurInsert->execute([
                ':nom' => $data['tuteur_nom'],
                ':prenom' => $data['tuteur_prenom'],
                ':telephone' => $data['tuteur_telephone'],
                ':adresse' => $data['tuteur_adresse'],
                ':email' => $data['tuteur_email']
            ]);
            $tuteurId = $this->pdo->lastInsertId();
        }

        // Générer un matricule si nécessaire
        if (empty($data['matricule'])) {
            $data['matricule'] = $this->genererMatricule($data['eleve_nom'], $data['eleve_prenom']);
        }

        // Mettre à jour l'élève
        $sqlEleve = "UPDATE eleve SET nom = :nom, prenom = :prenom, matricule = :matricule, adresse = :adresse, email = :email, sexe = :sexe, date_naissance = :date_naissance, Classe_id_classe = :Classe_id_classe, Tuteur_id_tuteur = :Tuteur_id_tuteur WHERE id_eleve = :id_eleve";
        $stmtEleve = $this->pdo->prepare($sqlEleve);
        $stmtEleve->execute([
            ':nom' => $data['eleve_nom'],
            ':prenom' => $data['eleve_prenom'],
            ':matricule' => $data['matricule'], // Utiliser le matricule généré ou passé
            ':adresse' => $data['eleve_adresse'],
            ':email' => $data['eleve_email'],
            ':sexe' => $data['eleve_sexe'],
            ':date_naissance' => $data['eleve_date_naissance'],
            ':Classe_id_classe' => $data['classe_id'],
            ':Tuteur_id_tuteur' => $tuteurExists ? $data['id_tuteur'] : $tuteurId, // Utiliser l'ID du tuteur existant ou nouvellement créé
            ':id_eleve' => $data['id_eleve'] // L'ID de l'élève à modifier
        ]);
        
        $this->pdo->commit();
        return ['success' => true];
    } catch (Exception $e) {
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
     public function archiverEleve($id)
    {
        $sql = "UPDATE eleve SET archive = 1 WHERE id_eleve = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    

 
     // Récupérer les élèves archivés
     public function getArchivedStudents() {
        $query = "SELECT * FROM eleve WHERE archive = 1"; // 1 pour les élèves archivés
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     // Désarchiver un élève
     public function unarchiveStudent($id) {
        $query = "UPDATE eleve SET archive = 0 WHERE id_eleve = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
}
