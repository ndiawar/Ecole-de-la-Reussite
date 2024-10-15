<?php
require_once '../config/config.php';

class PaiementEleveModel {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getPDO();
    }

    // Ajouter un paiement
    // public function ajouterPaiement($data) {
    //     // Valider les données
    //     $errors = $this->validerDonnees($data);
    //     if (!empty($errors)) {
    //         return ['success' => false, 'errors' => $errors];
    //     }

    //     $this->pdo->beginTransaction();
    //     try {
    //         // Ajouter le paiement
    //         $sql = "INSERT INTO paiement (montant, date_paiement, moyen_paiement, id_eleve, type_paiement, mois) 
    //                 VALUES (:montant, :date_paiement, :moyen_paiement, :id_eleve, :type_paiement, :mois)";
    //         $stmt = $this->pdo->prepare($sql);
    //         $stmt->execute([
    //             ':montant' => $data['montant'],
    //             ':date_paiement' => $data['date_paiement'],
    //             ':moyen_paiement' => $data['moyen_paiement'],
    //             ':id_eleve' => $data['id_eleve'],
    //             ':type_paiement' => $data['type_paiement'],
    //             ':mois' => $data['mois']

    //         ]);

    //         $this->pdo->commit();
    //         return ['success' => true];
    //     } catch (Exception $e) {
    //         $this->pdo->rollBack();
    //         return ['success' => false, 'errors' => ['Une erreur est survenue : ' . $e->getMessage()]];
    //     }
    // }

    // public function ajouterPaiement($data) {
    //     // Valider les données
    //     $errors = $this->validerDonnees($data);
    //     if (!empty($errors)) {
    //         return ['success' => false, 'errors' => $errors];
    //     }
    
    //     // Démarrer une transaction
    //     $this->pdo->beginTransaction();
    //     try {
    //         // Préparer l'insertion du paiement
    //         $sql = "INSERT INTO paiement (montant, date_paiement, moyen_paiement, id_eleve, type_paiement, mois) 
    //                 VALUES (:montant, :date_paiement, :moyen_paiement, :id_eleve, :type_paiement, :mois)";
    //         $stmt = $this->pdo->prepare($sql);
            
    //         // Exécuter la requête avec des valeurs sécurisées
    //         $stmt->execute([
    //             ':montant' => $data['montant'],
    //             ':date_paiement' => $data['date_paiement'],
    //             ':moyen_paiement' => $data['moyen_paiement'],
    //             ':id_eleve' => $data['id_eleve'],  // Assurez-vous que 'id_eleve' est bien dans $data
    //             ':type_paiement' => $data['type_paiement'],
    //             ':mois' => isset($data['mois']) ? $data['mois'] : null // Mois est optionnel pour certains paiements
    //         ]);
    
    //         // Valider la transaction
    //         $this->pdo->commit();
    //         return ['success' => true];
    //     } catch (Exception $e) {
    //         // Annuler la transaction en cas d'erreur
    //         $this->pdo->rollBack();
    //         error_log("Erreur d'insertion dans la base de données : " . $e->getMessage());
    //         return ['success' => false, 'errors' => ['Une erreur est survenue : ' . $e->getMessage()]];
    //     }
    // }
    
    public function ajouterPaiement($data) {
        // Valider les données
        $errors = $this->validerDonnees($data); // Validation générale des données
    
        // Si des erreurs existent, les retourner
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
    
        // Démarrer une transaction
        $this->pdo->beginTransaction();
        try {
            // Préparer l'insertion dans la table paiement
            $sql = "INSERT INTO paiement (montant, date_paiement, moyen_paiement, id_eleve, type_paiement, mois) 
                    VALUES (:montant, :date_paiement, :moyen_paiement, :id_eleve, :type_paiement, :mois)";
    
            $stmt = $this->pdo->prepare($sql);
    
            // Définir la valeur du mois seulement si le type de paiement est "mensualité"
            $mois = ($data['type_paiement'] === 'mensualite') ? $data['mois_paiement'] : null;
    
            // Exécuter la requête avec des valeurs sécurisées
            $stmt->execute([
                ':montant' => $data['montant'],
                ':date_paiement' => $data['date_paiement'],
                ':moyen_paiement' => $data['moyen_paiement'],
                ':id_eleve' => $data['id_eleve'],
                ':type_paiement' => $data['type_paiement'],
                ':mois' => $mois // Insérer null si ce n'est pas une mensualité
            ]);
    
            // Valider la transaction
            $this->pdo->commit();
            return ['success' => true];
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->pdo->rollBack();
            error_log("Erreur d'insertion dans la base de données : " . $e->getMessage());
            return ['success' => false, 'errors' => ['Une erreur est survenue : ' . $e->getMessage()]];
        }
    }

    // Méthode pour valider les données du formulaire de paiement
private function validerDonnees($data) {
    $errors = [];

    // Validation de l'ID élève
    if (empty($data['id_eleve']) || !is_numeric($data['id_eleve'])) {
        $errors[] = "L'ID de l'élève est requis et doit être valide.";
    } else {
        // Vérifier si l'élève existe dans la base de données
        $sql = "SELECT COUNT(*) FROM eleve WHERE id_eleve = :id_eleve";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_eleve' => $data['id_eleve']]);
        if ($stmt->fetchColumn() == 0) {
            $errors[] = "L'élève sélectionné n'existe pas.";
        }
    }

    // Validation du montant
    if (empty($data['montant']) || !is_numeric($data['montant'])) {
        $errors[] = "Le montant est requis et doit être un nombre.";
    }

    // Validation de la date de paiement
    if (empty($data['date_paiement'])) {
        $errors[] = "La date de paiement est requise.";
    }

    // Validation du moyen de paiement
    if (empty($data['moyen_paiement'])) {
        $errors[] = "Le moyen de paiement est requis.";
    }

    // Validation du type de paiement
    if (empty($data['type_paiement'])) {
        $errors[] = "Le type de paiement est requis.";
    }

    // Validation du mois si le type de paiement est "mensualité"
    if ($data['type_paiement'] == 'mensualite') {
        if (empty($data['mois_paiement'])) {
            $errors[] = "Le mois est requis pour les mensualités.";
        }
    }

    return $errors;
}

    
        
    


    // Afficher les paiements avec pagination et recherche
    public function getPaiements($start, $limit, $searchTerm = '') {
        $sql = "SELECT * FROM paiement WHERE id_paiement IS NOT NULL";
        
        // Ajouter condition de recherche
        if (!empty($searchTerm)) {
            $sql .= " AND (moyen_paiement LIKE :search OR type_paiement LIKE :search)";
        }
        
        $sql .= " LIMIT :start, :limit";
        
        $stmt = $this->pdo->prepare($sql);
        
        if (!empty($searchTerm)) {
            $stmt->bindValue(':search', '%' . $searchTerm . '%', PDO::PARAM_STR);
        }
        
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compter le nombre total de paiements (avec ou sans recherche)
    public function countPaiements($searchTerm = '') {
        $sql = "SELECT COUNT(*) FROM paiement WHERE id_paiement IS NOT NULL";
        
        // Ajouter condition de recherche
        if (!empty($searchTerm)) {
            $sql .= " AND (moyen_paiement LIKE :search OR type_paiement LIKE :search)";
        }
        
        $stmt = $this->pdo->prepare($sql);
        
        if (!empty($searchTerm)) {
            $stmt->bindValue(':search', '%' . $searchTerm . '%', PDO::PARAM_STR);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn();
    }



    // Modifier un paiement
    public function modifierPaiement($data) {
        $errors = $this->validerDonnees($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $this->pdo->beginTransaction();
        try {
            $sql = "UPDATE paiement SET montant = :montant, date_paiement = :date_paiement, moyen_paiement = :moyen_paiement, type_paiement = :type_paiement 
                    WHERE id_paiement = :id_paiement";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':montant' => $data['montant'],
                ':date_paiement' => $data['date_paiement'],
                ':moyen_paiement' => $data['moyen_paiement'],
                ':type_paiement' => $data['type_paiement'],
                ':id_paiement' => $data['id_paiement']
            ]);

            $this->pdo->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ['success' => false, 'errors' => ['Une erreur est survenue : ' . $e->getMessage()]];
        }
    }

    // Archiver un paiement
    public function archiverPaiement($id) {
        $sql = "UPDATE paiement SET archive = 1 WHERE id_paiement = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Récupérer les paiements archivés
    public function getArchivedPaiements() {
        $query = "SELECT * FROM paiement WHERE archive = 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Désarchiver un paiement
    public function unarchivePaiement($id) {
        $query = "UPDATE paiement SET archive = 0 WHERE id_paiement = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
