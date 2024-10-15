<?php
require_once '../config/config.php'; // Inclusion du fichier de configuration pour la connexion à la base de données

class PaiementSalarialModel {
    private $pdo; // Propriété pour stocker l'objet PDO de la base de données

    public function __construct() {
        $db = new Database(); // Instanciation de la classe Database
        $this->pdo = $db->getPDO(); // Récupération de l'objet PDO
    }

    // Ajouter un paiement salarial
// public function ajouterPaiement($data) {
//     // Valider les données
//     $errors = $this->validerDonnees($data); // Appel de la méthode de validation
//     if (!empty($errors)) {
//         return ['success' => false, 'errors' => $errors]; // Retourne les erreurs si validation échoue
//     }

//     $this->pdo->beginTransaction(); // Démarrer une transaction
//     try {
//         // Déterminer les valeurs à insérer
//         $tarif_horaire = ($data['type_salaire'] === 'Fixe') ? null : $data['tarif_horaire'];
//         $nombre_heures = ($data['type_salaire'] === 'Fixe') ? null : $data['nombre_heures'];

//         // Ajouter le paiement
//         $sql = "INSERT INTO paiement_salarial (id_personnel, type_salaire, montant, tarif_horaire, date_paiement, moyen_paiement, nombre_heures, mois, archive) 
//                 VALUES (:id_personnel, :type_salaire, :montant, :tarif_horaire, :date_paiement, :moyen_paiement, :nombre_heures, :mois, 0)";
//         $stmt = $this->pdo->prepare($sql);
//         $stmt->execute([
//             ':id_personnel' => $data['id_personnel'],
//             ':type_salaire' => $data['type_salaire'],
//             ':montant' => $data['montant'],
//             ':tarif_horaire' => $tarif_horaire,
//             ':date_paiement' => $data['date_paiement'],
//             ':moyen_paiement' => $data['moyen_paiement'],
//             ':nombre_heures' => $nombre_heures,
//             ':mois' => $data['mois']
//         ]);

//         $this->pdo->commit(); // Valider la transaction
//         return ['success' => true]; // Retourner le succès

//     } catch (Exception $e) {
//         $this->pdo->rollBack();
//         return ['success' => false, 'errors' => ['Une erreur est survenue : ' . $e->getMessage() . ' ' . $e->getCode()]];
//     }
// }
// Ajouter un paiement salarial
public function ajouterPaiement($data) {
    // Valider les données
    $errors = $this->validerDonnees($data); // Appel de la méthode de validation
    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors]; // Retourne les erreurs si validation échoue
    }

    $this->pdo->beginTransaction(); // Démarrer une transaction
    try {
        // Déterminer les valeurs à insérer
        $tarif_horaire = ($data['type_salaire'] === 'Fixe') ? null : $data['tarif_horaire'];
        $nombre_heures = ($data['type_salaire'] === 'Fixe') ? null : $data['nombre_heures'];

        // Ajouter le paiement
        $sql = "INSERT INTO paiement_salarial (id_personnel, type_salaire, montant, tarif_horaire, date_paiement, moyen_paiement, nombre_heures, mois, archive) 
                VALUES (:id_personnel, :type_salaire, :montant, :tarif_horaire, :date_paiement, :moyen_paiement, :nombre_heures, :mois, 0)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_personnel' => $data['id_personnel'],
            ':type_salaire' => $data['type_salaire'],
            ':montant' => $data['montant'],
            'tarif_horaire' => isset($_POST['tarif_horaire']) && $_POST['tarif_horaire'] !== '' ? $_POST['tarif_horaire'] : null,
            'date_paiement' => $_POST['date_paiement'],
            'moyen_paiement' => $_POST['moyen_paiement'],
            'nombre_heures' => isset($_POST['nombre_heures']) && $_POST['nombre_heures'] !== '' ? $_POST['nombre_heures'] : null,
            'mois' => $_POST['mois'],
            ]);

        $this->pdo->commit(); // Valider la transaction
        return ['success' => true]; // Retourner le succès

    } catch (Exception $e) {
        $this->pdo->rollBack();
        return ['success' => false, 'errors' => ['Une erreur est survenue : ' . $e->getMessage() . ' ' . $e->getCode()]];
    }
}

    // Récupérer tous les paiements
    // public function getPaiements($offset = 0, $limit = 10, $search = '') {
    //     $sql = "SELECT * FROM paiement_salarial WHERE 1"; // Requête pour récupérer tous les paiements
    
    //     if ($search) {
    //         $sql .= " AND (type_salaire LIKE :search OR id_personnel LIKE :search)"; // Ajout d'une condition de recherche
    //     }
    
    //     $sql .= " LIMIT :offset, :limit"; // Limiter les résultats pour la pagination
    //     $stmt = $this->pdo->prepare($sql);
    
    //     if ($search) {
    //         $searchParam = '%' . $search . '%'; // Préparer le paramètre de recherche
    //         $stmt->bindValue(':search', $searchParam, PDO::PARAM_STR);
    //     }
    
    //     $stmt->bindValue(':offset', $offset, PDO::PARAM_INT); // Liaison du paramètre offset
    //     $stmt->bindValue(':limit', $limit, PDO::PARAM_INT); // Liaison du paramètre limit
    //     $stmt->execute(); // Exécuter la requête
    
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourner tous les paiements sous forme de tableau associatif
    // }

    // Récupérer tous les paiements
// public function getPaiements($offset = 0, $limit = 10, $search = '') {
//     $sql = "SELECT ps.*, p.nom, p.prenom, p.role FROM paiement_salarial ps 
//             JOIN personnel p ON ps.id_personnel = p.id_personnel WHERE 1"; // Requête avec jointure

//     if ($search) {
//         $sql .= " AND (ps.type_salaire LIKE :search OR ps.id_personnel LIKE :search)"; // Condition de recherche
//     }

//     $sql .= " LIMIT :offset, :limit"; // Limiter les résultats pour la pagination
//     $stmt = $this->pdo->prepare($sql);

//     if ($search) {
//         $searchParam = '%' . $search . '%'; // Préparer le paramètre de recherche
//         $stmt->bindValue(':search', $searchParam, PDO::PARAM_STR);
//     }

//     $stmt->bindValue(':offset', $offset, PDO::PARAM_INT); // Liaison du paramètre offset
//     $stmt->bindValue(':limit', $limit, PDO::PARAM_INT); // Liaison du paramètre limit
//     $stmt->execute(); // Exécuter la requête

//     return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourner tous les paiements avec les infos du personnel
// }

    
//     // Compter le nombre de paiements
//     public function countPaiements($search = '') {
//         $sql = "SELECT COUNT(*) FROM paiement_salarial WHERE 1"; // Requête pour compter les paiements
    
//         if ($search) {
//             $sql .= " AND (type_salaire LIKE :search OR id_personnel LIKE :search)"; // Condition de recherche
//         }
    
//         $stmt = $this->pdo->prepare($sql);
    
//         if ($search) {
//             $searchParam = '%' . $search . '%'; // Préparer le paramètre de recherche
//             $stmt->bindValue(':search', $searchParam, PDO::PARAM_STR);
//         }
    
//         $stmt->execute(); // Exécuter la requête
//         return $stmt->fetchColumn(); // Retourner le nombre de paiements
//     }
    
//     // Récupérer un paiement par ID
//     public function getPaiementById($id) {
//         $sql = "SELECT * FROM paiement_salarial WHERE id_paiement = :id"; // Requête pour récupérer un paiement par ID
//         $stmt = $this->pdo->prepare($sql);
//         $stmt->execute([':id' => $id]); // Exécuter la requête avec l'ID
//         return $stmt->fetch(PDO::FETCH_ASSOC); // Retourner les informations du paiement
//     }

public function getPaiements($offset = 0, $limit = 10, $search = '', $mois = '') {
    $sql = "SELECT ps.*, p.nom, p.prenom, p.role FROM paiement_salarial ps 
            JOIN personnel p ON ps.id_personnel = p.id_personnel WHERE 1";

    if ($search) {
        $sql .= " AND (ps.type_salaire LIKE :search OR ps.id_personnel LIKE :search)";
    }

    if ($mois) {
        $sql .= " AND MONTH(ps.date_paiement) = :mois"; // Filtre par mois uniquement si mois est spécifié
    }

    $sql .= " LIMIT :offset, :limit";
    $stmt = $this->pdo->prepare($sql);

    if ($search) {
        $searchParam = '%' . $search . '%';
        $stmt->bindValue(':search', $searchParam, PDO::PARAM_STR);
    }

    if ($mois) {
        $stmt->bindValue(':mois', $mois, PDO::PARAM_INT);
    }

    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function countPaiements($search = '', $mois = '') {
    $sql = "SELECT COUNT(*) FROM paiement_salarial ps 
            JOIN personnel p ON ps.id_personnel = p.id_personnel WHERE 1";

    if ($search) {
        $sql .= " AND (ps.type_salaire LIKE :search OR ps.id_personnel LIKE :search)";
    }

    if ($mois) {
        $sql .= " AND MONTH(ps.date_paiement) = :mois"; // Filtre par mois
    }

    $stmt = $this->pdo->prepare($sql);

    if ($search) {
        $searchParam = '%' . $search . '%';
        $stmt->bindValue(':search', $searchParam, PDO::PARAM_STR);
    }

    if ($mois) {
        $stmt->bindValue(':mois', $mois, PDO::PARAM_INT);
    }

    $stmt->execute();
    return $stmt->fetchColumn();
}

    // Fonction de validation des données
    private function validerDonnees($data) {
        $errors = []; // Tableau pour stocker les erreurs

        // Validation des champs requis
        if (empty($data['id_personnel'])) {
            $errors[] = "L'ID du personnel est requis."; // Erreur si l'ID du personnel est vide
        }
        if (empty($data['type_salaire']) || !in_array($data['type_salaire'], ['Fixe', 'Horaire'])) {
            $errors[] = "Le type de salaire doit être 'Fixe' ou 'Horaire'."; // Validation du type de salaire
        }
        $errors = [];
        if (!is_numeric($data['tarif_horaire']) && !is_null($data['tarif_horaire'])) {
            $errors[] = "Le tarif horaire doit être un nombre.";
        }

        if (empty($data['montant'])) {
            $errors[] = "Le montant est requis."; // Erreur si le montant est vide
        }
        if (empty($data['date_paiement'])) {
            $errors[] = "La date de paiement est requise."; // Erreur si la date de paiement est vide
        }
        if (empty($data['moyen_paiement']) || !in_array($data['moyen_paiement'], ['Virement', 'Chèque', 'Wave', 'Orange Money', 'Espèces'])) {
            $errors[] = "Le moyen de paiement est invalide."; // Validation du moyen de paiement
        }

        return $errors; // Retourner les erreurs éventuelles
    }

    // Modifier un paiement
    public function modifierPaiement($id, $data) {
        $errors = $this->validerDonnees($data); // Validation des données
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors]; // Retourner les erreurs si validation échoue
        }

        $this->pdo->beginTransaction(); // Démarrer une transaction
        try {
            // Mise à jour du paiement
            $sql = "UPDATE paiement_salarial SET 
                    id_personnel = :id_personnel, 
                    type_salaire = :type_salaire, 
                    montant = :montant, 
                    tarif_horaire = :tarif_horaire, 
                    date_paiement = :date_paiement, 
                    moyen_paiement = :moyen_paiement, 
                    nombre_heures = :nombre_heures, 
                    mois = :mois 
                    WHERE id_paiement = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_personnel' => $data['id_personnel'],
                ':type_salaire' => $data['type_salaire'],
                ':montant' => $data['montant'],
                ':tarif_horaire' => $data['tarif_horaire'],
                ':date_paiement' => $data['date_paiement'],
                ':moyen_paiement' => $data['moyen_paiement'],
                ':nombre_heures' => $data['nombre_heures'],
                ':mois' => $data['mois'],
                ':id' => $id // ID du paiement à modifier
            ]);

            $this->pdo->commit(); // Valider la transaction
            return ['success' => true]; // Retourner le succès
        } catch (Exception $e) {
            $this->pdo->rollBack(); // Annuler la transaction en cas d'erreur
            return ['success' => false, 'errors' => ['Une erreur est survenue : ' . $e->getMessage()]];
        }
    }

    // Archiver un paiement
    public function archiverPaiement($id) {
        $this->pdo->beginTransaction(); // Démarrer une transaction
        try {
            $sql = "UPDATE paiement_salarial SET archived = 1 WHERE id_paiement = :id"; // Marquer le paiement comme archivé
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]); // Exécuter la requête

            $this->pdo->commit(); // Valider la transaction
            return ['success' => true]; // Retourner le succès
        } catch (Exception $e) {
            $this->pdo->rollBack(); // Annuler la transaction en cas d'erreur
            return ['success' => false, 'errors' => ['Une erreur est survenue : ' . $e->getMessage()]];
        }
    }
}
