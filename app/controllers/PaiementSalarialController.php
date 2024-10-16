<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/../models/PaiementSalarialModel.php'); // Inclut le modèle de paiements salariaux
require_once(__DIR__ . '/../models/Personnel.php'); // Assurez-vous que le chemin est correct

class PaiementSalarialController
{
    private $paiementSalarialModel; // Instance du modèle pour accéder aux données
    private $personnelModel;

    public function __construct()
    {
        // Création d'une nouvelle instance du modèle
        $this->paiementSalarialModel = new PaiementSalarialModel();
        $this->personnelModel = new Personnel();

    }

    // public function afficherFormulaireAjoutPaiement() {
    //     // Récupérer la liste des personnels
    //     $personnels = $this->personnelModel->getActifPersonnels(); // Assurez-vous que cette méthode existe
    
    //     // Inclure la vue pour le formulaire
    //     require '../app/views/paiementEmployer/paiement_ajout.php'; // Vérifiez que le chemin est correct
    // }


    public function afficherFormulaireAjoutPaiement() {
        // Récupérer la liste des personnels
        $personnels = $this->personnelModel->getActifPersonnels();
    
        // Si la variable est vide, tu peux décider de ce qui doit se passer
        if (empty($personnels)) {
            // Gérer le cas où il n'y a pas de personnel, par exemple en affichant un message d'erreur
        }
    
        // Inclure la vue pour le formulaire
        require '../app/views/paiementEmployer/paiement_ajout.php';
    }
    
    
    

    // Affiche une liste paginée des paiements
    public function afficherListePaiements($page = 1) {
        $limit = 10; // Nombre de paiements par page
        $offset = ($page - 1) * $limit; // Calcul de l'offset pour la pagination
        
        // Récupère le paramètre de recherche et le mois depuis l'URL
        $search = $_GET['search'] ?? '';
        $mois = $_GET['mois'] ?? ''; // Récupération du mois sélectionné
    
        // Récupère les paiements avec pagination, recherche et filtre par mois
        $paiements = $this->paiementSalarialModel->getPaiements($offset, $limit, $search, $mois);
        // Compte le nombre total de paiements correspondant à la recherche et au filtre par mois
        $totalPaiements = $this->paiementSalarialModel->countPaiements($search, $mois);
        // Calcule le nombre total de pages
        $totalPages = ceil($totalPaiements / $limit);
    
        // Inclut la vue pour afficher la liste des paiements
        require '../app/views/paiementEmployer/paiement_liste.php'; // Vérifiez que le chemin est correct
    }
    
    
    // Gère les erreurs en passant les messages d'erreur à une vue
    private function handleErrors($errors, $view) {
        // Optionnel : Log des erreurs
        // error_log(implode(', ', $errors));

        // Passer les erreurs à la vue
        require '../app/views/' . $view; // Inclut la vue d'erreur
        exit; // Arrête l'exécution du script après l'affichage de l'erreur
    }

 // Sanitizes input data by converting special characters to HTML entities
private function sanitizeInput($data) {
    return array_map(function($value) {
        return $value !== null ? htmlspecialchars($value) : ''; // Retourne une chaîne vide si la valeur est nulle
    }, $data);
}

// Ajoute un nouveau paiement
// public function ajouterPaiement() {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Vérifie si la requête est de type POST
//         // Sanitize input data
//         $data = $this->sanitizeInput([
//             'id_personnel' => $_POST['id_personnel'] ?? '',
//             'type_salaire' => $_POST['type_salaire'] ?? '',
//             'montant' => $_POST['montant'] ?? '',
//             'tarif_horaire' => isset($_POST['tarif_horaire']) && $_POST['tarif_horaire'] !== '' ? $_POST['tarif_horaire'] : null,
//             'date_paiement' => $_POST['date_paiement'] ?? '',
//             'moyen_paiement' => $_POST['moyen_paiement'] ?? '',
//             'nombre_heures' => isset($_POST['nombre_heures']) && $_POST['nombre_heures'] !== '' ? $_POST['nombre_heures'] : null,
//             // Convertir le mois en date
//             'mois' => !empty($_POST['mois']) ? date('Y-m-d', strtotime('01-' . $_POST['mois'])) : null,
//         ]);

//         // Tente d'ajouter le paiement via le modèle
//         $result = $this->paiementSalarialModel->ajouterPaiement($data);

//         if ($result['success']) {
//             // Redirige vers la liste des paiements en cas de succès
//             header("Location: /Ecole-de-la-Reussite/public/index.php?action=listPaiements");
//             exit; // Arrête l'exécution
//         } else {
//             // Gère les erreurs s'il y en a
//             $this->handleErrors($result['errors'], 'paiementEmployer/paiement_ajout.php');
//         }
//     } else {
//         // Inclut la vue pour afficher le formulaire d'ajout de paiement
//         require '../app/views/paiementEmployer/paiement_ajout.php';
//     }
// }

public function ajouterPaiement() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize input data
        $data = $this->sanitizeInput([
            'id_personnel' => $_POST['id_personnel'] ?? '',
            'type_salaire' => $_POST['type_salaire'] ?? '',
            'montant' => $_POST['montant'] ?? '',
            'tarif_horaire' => isset($_POST['tarif_horaire']) && is_numeric($_POST['tarif_horaire']) ? $_POST['tarif_horaire'] : null,
            'date_paiement' => $_POST['date_paiement'] ?? '',
            'moyen_paiement' => $_POST['moyen_paiement'] ?? '',
            'nombre_heures' => isset($_POST['nombre_heures']) && is_numeric($_POST['nombre_heures']) ? $_POST['nombre_heures'] : null,
            'mois' => !empty($_POST['mois']) ? date('Y-m-d', strtotime('01-' . $_POST['mois'])) : null,
        ]);

        // Tente d'ajouter le paiement via le modèle
        $result = $this->paiementSalarialModel->ajouterPaiement($data);

        if ($result['success']) {
            header("Location: /Ecole-de-la-Reussite/public/index.php?action=listPaiements");
            exit;
        } else {
            $this->handleErrors($result['errors'], 'paiementEmployer/paiement_ajout.php');
        }
    } else {
        require '../app/views/paiementEmployer/paiement_ajout.php';
    }
}


    // Affiche les détails d'un paiement par son ID
    public function afficherPaiementParId($id)
    {
        // Récupère le paiement par ID
        $paiement = $this->paiementSalarialModel->getPaiements($id);
        if ($paiement) {
            // Inclut la vue pour modifier le paiement
            require '../app/views/paiementEmployer/paiement_modifier.php';
        } else {
            // Redirige vers la liste des paiements si le paiement n'existe pas
            header('Location: index.php?action=listPaiements');
            exit;
        }
    }

    // Modifie un paiement existant
    public function modifierPaiement($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Vérifie si la requête est de type POST
            // Sanitize input data
            $data = $this->sanitizeInput([
                'id_personnel' => $_POST['id_personnel'] ?? '',
                'type_salaire' => $_POST['type_salaire'] ?? '',
                'montant' => $_POST['montant'] ?? '',
                'tarif_horaire' => $_POST['tarif_horaire'] ?? null,
                'date_paiement' => $_POST['date_paiement'] ?? '',
                'moyen_paiement' => $_POST['moyen_paiement'] ?? '',
                'nombre_heures' => $_POST['nombre_heures'] ?? null,
                'mois' => $_POST['mois'] ?? null,
            ]);

            // Tente de modifier le paiement via le modèle
            $result = $this->paiementSalarialModel->modifierPaiement($id, $data);

            if ($result['success']) {
                // Redirige vers la liste des paiements en cas de succès
                header('Location: index.php?action=listPaiements');
                exit;
            } else {
                // Gère les erreurs s'il y en a
                $this->handleErrors($result['errors'], 'paiementEmployer/paiement_modifier.php');
            }
        } else {
            // Affiche un message d'erreur si la méthode n'est pas autorisée
            echo "Méthode non autorisée";
            exit;
        }
    }

    // Archive un paiement par son ID
    public function archiverPaiement($id)
    {
        // Tente d'archiver le paiement via le modèle
        $result = $this->paiementSalarialModel->archiverPaiement($id);

        if ($result['success']) {
            // Redirige vers la liste des paiements en cas de succès
            header('Location: index.php?action=listPaiements&message=archivage_reussi');
            exit;
        } else {
            // Redirige avec un message d'erreur
            header('Location: index.php?action=listPaiements&message=' . urlencode(implode(', ', $result['errors'])));
            exit;
        }
    }
}
