<?php
// Inclusion du modèle PaiementEleveModel
require_once(__DIR__ . '/../models/PaiementElevesModel.php'); // Assurez-vous que le chemin est correct
require_once(__DIR__ . '/../models/EleveModel.php'); // Assurez-vous que le chemin est correct

class PaiementEleveController {
    private $paiementModel;
    private $eleveModel;

    public function __construct() {
        $this->paiementModel = new PaiementEleveModel();
        $this->eleveModel = new EleveModel();
    }



    public function afficherTousLesElevesp()
    {
        // Nombre d'élèves par page
        $limit = 9; 
        // Page actuelle
        $page = $_GET['page'] ?? 1; 
        // Calcul du début
        $start = ($page - 1) * $limit; 
    
        // Récupérer les élèves avec pagination
        $eleves = $this->eleveModel->getElevesWithPagination($start, $limit);
        
        // Compter le nombre total d'élèves
        $totalEleves = $this->eleveModel->countEleves(); 
        $totalPages = ceil($totalEleves / $limit);
    
        // Charger la vue avec les données paginées
        require '../app/views/paiementEleves/listPaieEleves.php';
    }


    // Afficher la liste des paiements
    public function index($page = 1, $limit = 10, $searchTerm = '') {
        $start = ($page - 1) * $limit;
        $paiements = $this->paiementModel->getPaiements($start, $limit, $searchTerm);
        $totalPaiements = $this->paiementModel->countPaiements($searchTerm);
        $totalPages = ceil($totalPaiements / $limit);

        // Inclure la vue pour afficher les paiements
        require '../app/views/paiementEleves/listPaieEleves.php'; // Assurez-vous que ce fichier existe
    }

    // Ajouter un paiement
    public function ajouter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST; // Récupérer les données du formulaire
            $result = $this->paiementModel->ajouterPaiement($data);
            if ($result['success']) {
                // Redirection ou message de succès
                header('Location: index.php?action=listePaiementsEleves'); // Rediriger vers la liste des paiements
                exit();
            } else {
                // Gérer les erreurs
                $errors = $result['errors'];
            }
        }

        // Inclure la vue pour le formulaire d'ajout
        require '../app/views/paiementEleves/AjouterPaieEleve.php'; // Assurez-vous que ce fichier existe
    }

    // // Modifier un paiement
    // public function modifier($id) {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $data = $_POST; // Récupérer les données du formulaire
    //         $data['id_paiement'] = $id; // Ajouter l'ID du paiement
    //         $result = $this->paiementModel->modifierPaiement($data);
    //         if ($result['success']) {
    //             // Redirection ou message de succès
    //             header('Location: index.php?action=listePaiementsEleves'); // Rediriger vers la liste des paiements
    //             exit();
    //         } else {
    //             // Gérer les erreurs
    //             $errors = $result['errors'];
    //         }
    //     } else {
    //         // Récupérer les détails du paiement pour le pré-remplir dans le formulaire
    //         $paiement = $this->paiementModel->getPaiements($id); // Vous devrez ajuster cela
    //     }

    //     // Inclure la vue pour le formulaire de modification
    //     require '../app/views/paiementEleves/modifierPaieEleve.php'; // Assurez-vous que ce fichier existe
    // }

    // Archiver un paiement
    public function archiver($id) {
        $this->paiementModel->archiverPaiement($id);
        header('Location: index.php?action=listePaiementsEleves'); // Rediriger vers la liste des paiements
        exit();
    }

    // Afficher les paiements archivés
    public function archives() {
        $paiementsArchivés = $this->paiementModel->getArchivedPaiements();
        require '../app/views/paiementEleves/listPaieEleves.php'; // Assurez-vous que ce fichier existe
    }

    // Désarchiver un paiement
    public function unarchive($id) {
        $this->paiementModel->unarchivePaiement($id);
        header('Location: index.php?action=listePaiementsEleves'); // Rediriger vers la liste des paiements archivés
        exit();
    }
}
?>
