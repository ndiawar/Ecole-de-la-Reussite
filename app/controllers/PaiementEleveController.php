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



    public function afficherTousLesElevesp() {
        // Nombre d'élèves par page
        $limit = 9; 
        // Page actuelle
        $page = $_GET['page'] ?? 1; 
        // Calcul du début
        $start = ($page - 1) * $limit; 
    
        // Récupérer les élèves avec pagination
        $eleves = $this->eleveModel->getElevesWithPagination($start, $limit);
    
        // Pour chaque élève, récupérer le statut du compte
        foreach ($eleves as &$eleve) {
            $eleve['statut_compte'] = $this->eleveModel->getStatutCompte($eleve['id_eleve']);
        }
        
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

     
    public function ajouter() {
        // Vérifier si la requête est une méthode POST (formulaire soumis)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $data = $_POST;
        
            // Debug: afficher les données reçues (utiliser uniquement pour le débogage)
            // echo '<pre>'; print_r($data); echo '</pre>';
    
            // Vérifier que l'ID de l'élève est présent et valide
            if (!isset($data['id_eleve']) || empty($data['id_eleve'])) {
                echo 'Erreur : L\'ID de l\'élève est manquant.';
                return; // Stopper le traitement si l'ID est manquant
            }
    
            // Appeler le modèle pour ajouter le paiement
            $result = $this->paiementModel->ajouterPaiement($data);
    
            // Vérifier si l'ajout du paiement a réussi
            if ($result['success']) {
                // Redirection en cas de succès vers la liste des paiements des élèves
                header('Location: http://localhost/Ecole-de-la-Reussite/public/index.php?action=listeElevesp');
                exit(); // Assurez-vous que le script s'arrête après la redirection
            } else {
                // Si des erreurs sont retournées, les afficher pour le débogage
                echo '<pre>'; print_r($result['errors']); echo '</pre>';
            }
        }
    
        // Inclure la vue si le formulaire n'a pas encore été soumis ou en cas d'erreur
        require '../app/views/paiementEleves/listPaieEleves.php';
    }
    
    // public function ajouter() {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $data = $_POST;
    
    //         // Vérifier que l'ID de l'élève est présent
    //         if (!isset($data['id_eleve']) || empty($data['id_eleve'])) {
    //             echo 'Erreur : L\'ID de l\'élève est manquant.';
    //             return;
    //         }
    
    //         // Appeler le modèle pour ajouter le paiement
    //         $result = $this->paiementModel->ajouterPaiement($data);
    
    //         // Vérifier si l'ajout du paiement a réussi
    //         if ($result['success']) {
    //             // Redirection en cas de succès vers la liste des paiements des élèves
    //             header('Location: http://localhost/Ecole-de-la-Reussite/public/index.php?action=listeElevesp');
    //             exit();
    //         } else {
    //             // Si un message spécifique est retourné (ex : déjà payé), afficher un message temporaire
    //             if (isset($result['message'])) {
    //                 $message = $result['message'];
    //             }
    
    //             // Sinon, afficher les erreurs si l'ajout échoue
    //             if (isset($result['errors'])) {
    //                 $errors = $result['errors'];
    //                 echo '<pre>'; print_r($errors); echo '</pre>';
    //             }
    //         }
    //     }
    
    //     // Inclure la vue si le formulaire n'a pas encore été soumis ou en cas d'erreur
    //     require '../app/views/paiementEleves/listPaieEleves.php';
    // }
    

    
    // Méthode dans le contrôleur pour gérer la liste des élèves et leurs statuts
    
 
// Méthode pour récupérer les mois payés et non payés
public function getMensualites() {
    if (isset($_POST['id_eleve'])) {
        $id_eleve = $_POST['id_eleve'];

        // Récupérer les mois payés de l'élève
        $moisPayes = $this->paiementModel->getMoisPayes($id_eleve);

        // Liste complète des mois de l'année scolaire
        $moisComplet = ['Octobre', 'Novembre', 'Décembre', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet'];

        // Préparer le tableau avec les mois et leur statut
        $mensualites = [];
        foreach ($moisComplet as $mois) {
            $mensualites[] = [
                'mois' => $mois,
                'statut' => in_array($mois, $moisPayes) ? 'payé' : 'non payé'
            ];
        }

        // Retourner les mensualités au format JSON
        echo json_encode($mensualites);
    }
}

    
    
    
 

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
