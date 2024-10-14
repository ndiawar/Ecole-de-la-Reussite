<?php
require_once '../config/config.php';

class PaiementEleveModel {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getPDO();
    }

    // Ajouter un paiement
    public function ajouterPaiement($data) {
        // Valider les données
        $errors = $this->validerDonnees($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $this->pdo->beginTransaction();
        try {
            // Ajouter le paiement
            $sql = "INSERT INTO paiement (montant, date_paiement, moyen_paiement, id_eleve, type_paiement, mois) 
                    VALUES (:montant, :date_paiement, :moyen_paiement, :id_eleve, :type_paiement, :mois)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':montant' => $data['montant'],
                ':date_paiement' => $data['date_paiement'],
                ':moyen_paiement' => $data['moyen_paiement'],
                ':id_eleve' => $data['id_eleve'],
                ':type_paiement' => $data['type_paiement'],
                ':mois' => $data['mois']

            ]);

            $this->pdo->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ['success' => false, 'errors' => ['Une erreur est survenue : ' . $e->getMessage()]];
        }
    }

    // Valider les données du paiement
    private function validerDonnees($data) {
        $errors = [];
        
        // Validation du montant
        if (!isset($data['montant']) || !is_numeric($data['montant']) || $data['montant'] <= 0) {
            $errors[] = "Le montant doit être un nombre positif.";
        }
        
        // Validation de la date
        if (!isset($data['date_paiement']) || empty($data['date_paiement'])) {
            $errors[] = "La date de paiement est obligatoire.";
        } else {
            $date = DateTime::createFromFormat('Y-m-d', $data['date_paiement']);
            if (!$date || $date->format('Y-m-d') !== $data['date_paiement']) {
                $errors[] = "La date de paiement doit être au format YYYY-MM-DD.";
            }
        }

        // Validation du moyen de paiement
        if (!isset($data['moyen_paiement']) || empty($data['moyen_paiement'])) {
            $errors[] = "Le moyen de paiement est obligatoire.";
        }

        // Validation de l'ID de l'élève
        if (!isset($data['id_eleve']) || empty($data['id_eleve'])) {
            $errors[] = "L'ID de l'élève est obligatoire.";
        } else {
            $sql = "SELECT COUNT(*) FROM eleve WHERE id_eleve = :id_eleve";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_eleve' => $data['id_eleve']]);
            if ($stmt->fetchColumn() == 0) {
                $errors[] = "L'élève sélectionné n'existe pas.";
            }
        }

        // Validation du type de paiement
        if (!isset($data['type_paiement']) || !in_array($data['type_paiement'], ['inscription', 'salaire', 'mensualite'])) {
            $errors[] = "Le type de paiement doit être 'inscription', 'salaire' ou 'mensualité'.";
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
