<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Paiement.php';
require_once __DIR__ . '/../../config/config.php';

class paiementsController {
    private $paiementModel;

    
    public function __construct() {
        $database = new Database();
        $db = $database->getPDO();
        $this->paiementModel = new Paiement($db);
    }

    // Récupérer tous les reçus d'un élève
    public function recupererRecusEleve($id_eleve) {
        return $this->paiementModel->getRecusParEleve($id_eleve);
    }

    // Récupérer les détails d'un reçu spécifique
    public function recupererDetailsRecu($id_recu) {
        return $this->paiementModel->getDetailsRecu($id_recu);
    }

    // Mettre à jour un paiement
    public function mettreAJourPaiement($id_recu, $nouveau_montant, $nouveau_moyen) {
        if ($this->validerMontant($nouveau_montant)) {
            $this->paiementModel->mettreAJourRecu($id_recu, $nouveau_montant, $nouveau_moyen);
        } else {
            echo "Montant payé invalide.";
        }
    }

    // Annuler un paiement
    public function annulerPaiement($id_recu) {
        $this->paiementModel->annulerRecu($id_recu);
    }

    // Générer un bulletin de salaire
    public function genererBulletinSalaire($id_employe, $nombre_heures, $type_salaire) {
        $bulletin = $this->paiementModel->creerBulletinSalaire($id_employe, $nombre_heures, $type_salaire);
        return $bulletin;
    }

    // Récupérer tous les bulletins de salaire d'un employé
    public function recupererBulletinsSalaire($id_employe) {
        return $this->paiementModel->getBulletinsParEmploye($id_employe);
    }

    // Récupérer les détails d'un bulletin de salaire spécifique
    public function recupererDetailsBulletin($id_bulletin) {
        return $this->paiementModel->getDetailsBulletinSalaire($id_bulletin);
    }

    // Mettre à jour un bulletin de salaire
    public function mettreAJourBulletin($id_bulletin, $nouveau_nombre_heures) {
        $this->paiementModel->mettreAJourBulletinSalaire($id_bulletin, $nouveau_nombre_heures);
    }

    // Annuler un bulletin de salaire
    public function annulerBulletin($id_bulletin) {
        $this->paiementModel->annulerBulletinSalaire($id_bulletin);
    }

    public function index() {
        $paiements = $this->paiementModel->getAllPaiements(); // Récupérer tous les paiements
        require '../app/views/eleve/listPaiements.php'; // Inclure la vue pour afficher les paiements
    }
    

    
    public function listElèves() {
        $itemsPerPage = 5; // Nombre d'éléments par page
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Récupérer la page actuelle
    
        // Assurez-vous que $currentPage est un nombre positif
        if ($currentPage < 1) {
            $currentPage = 1;
        }
    
        // Obtenir le nombre total d'élèves et le nombre total de pages
        $totalItems = $this->paiementModel->getTotal(); // Méthode pour obtenir le nombre total d'élèves
        $totalPages = ceil($totalItems / $itemsPerPage);
    
        // Limiter le currentPage à la plage valide
        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }
    
        // Récupérer les élèves pour la page actuelle, y compris leur statut d'inscription
        $eleves = $this->paiementModel->getAll($currentPage, $itemsPerPage);
    
        // Inclure la vue et transmettre la liste des élèves
        require_once '../app/views/eleve/listEleve.php'; 
    }
        

    // Obtenir des statistiques sur les paiements
    public function obtenirStatistiques() {
        return $this->paiementModel->getStatistiquesPaiements();
    }

    // Valider le montant
    private function validerMontant($montant) {
        return is_numeric($montant) && $montant > 0;
    }   

    

// Enregistrer un paiement et générer un reçu
public function payer() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       
        // Récupérer les données du formulaire
        $data = [
            'id_eleve' => $_POST['id_eleve'] ?? null,
            'type_paiement' => $_POST['type_paiement'] ?? null,
            'montant' => isset($_POST['montant']) && is_numeric($_POST['montant']) ? floatval($_POST['montant']) : null,
            'moyen_paiement' => $_POST['moyen_paiement'] ?? null,
            'date_paiement' => $_POST['date_paiement'] ?? null,
            'id_personnel' => $_POST['id_personnel'] ?? null,
            //'mois' => $_POST['mois'] ?? null,
        ];

        // Vérifier les valeurs nécessaires
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                // Gérer le cas où une valeur est manquante
                echo "Erreur : $key est manquant.";
                return; // Sortir pour éviter d'essayer de créer le paiement
            }
        }

        // Appeler la méthode createPayment dans le modèle
        $payment = $this->paiementModel->createPayment($data); 

        if ($payment) {
            // Rediriger vers la liste des élèves
            header('Location: http://localhost/Ecole-de-la-Reussite/public/index.php?action=listEleves');
            exit(); // Terminer le script pour éviter tout comportement inattendu
        } else {
            // En cas d'erreur
            $error = "Erreur lors de la création du paiement.";
            // Inclure le formulaire de paiement pour réessayer
            include '../views/eleve/formPaiement.php'; 
        }
    } else {
        // Si la méthode n'est pas POST, inclure le formulaire de paiement
        include '../views/eleve/formPaiement.php'; 
    }
}

public function getMensualites() {
    if (isset($_POST['id_eleve'])) {
        $idEleve = $_POST['id_eleve'];

        // Appel au modèle pour récupérer les mois payés
        $moisPayes = $this->model->getMensualitesByEleve($idEleve);
        $tousLesMois = $this->model->getTousLesMois();

        // Construction de la réponse JSON
        $mensualites = [];
        foreach ($tousLesMois as $mois) {
            // Vérifie si le mois a été payé
            $statut = 'non payé';
            foreach ($moisPayes as $paiement) {
                if ($paiement['mois'] === $mois) {
                    $statut = 'payé';
                    break;
                }
            }

            // Ajoute le mois et son statut dans le tableau de réponse
            $mensualites[] = [
                'mois' => $mois,
                'statut' => $statut
            ];
        }

        // Envoi de la réponse JSON
        echo json_encode($mensualites);
    } else {
        // Erreur : pas d'id_eleve fourni
        echo json_encode(['error' => 'ID élève non fourni']);
    }
}





// public function payer() {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//         // Récupérer les données du formulaire
//         $data = [
//             'id_eleve' => $_POST['id_eleve'], // ID de l'élève récupéré
//             'type_paiement' => $_POST['type_paiement'],
//             'montant' => $_POST['montant'],
//             'moyen_paiement' => $_POST['moyen_paiement'],
//             'date_paiement' => $_POST['date_paiement'],
//             'mois' => $_POST['mois'],
//         ];

//         // Appeler la méthode createPayment dans le modèle
//         $paymentId = $this->paiementModel->createPayment($data); 

//         if ($paymentId) {
//             // Si le paiement a été créé avec succès, rediriger vers la génération du reçu
//             header("Location: ..app/views/eleve/generate_receipt.php" . $paymentId);
//             exit(); // Terminer le script pour éviter tout comportement inattendu
//         } else {
//             // En cas d'erreur
//             $error = "Erreur lors de la création du paiement.";
//             // Optionnel : inclure le formulaire de paiement pour réessayer
//             include '../views/eleve/formPaiement.php'; // Ou la page où se trouve le formulaire de paiement
//         }
//     } else {
//         // Si la méthode n'est pas POST, inclure le formulaire de paiement
//         include '../views/eleve/formPaiement.php'; // Ou toute autre page pertinente
//     }
// }
    

//     // Méthode pour récupérer les informations d'un élève par son ID
//     public function showEleveInfo($id_eleve) {
//         // Préparez la requête SQL pour obtenir les informations de l'élève
//         $sql = "SELECT * FROM eleves WHERE id = :id_eleve";
//         $stmt = $this->db->prepare($sql); // Préparez la requête
//         $stmt->execute([':id_eleve' => $id_eleve]); // Exécutez la requête avec l'ID de l'élève
//         return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne les informations de l'élève sous forme de tableau associatif
//     }




}
