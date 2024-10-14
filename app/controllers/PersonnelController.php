<?php
require_once '../config/config.php';
require_once(__DIR__ . '/../models/paiement.php'); // Assurez-vous que le chemin est correct

class PersonnelController {
    private $personnelModel;

    public function __construct() {
        //session_start(); // Démarrer la session pour gérer les messages d'erreur et de succès
        $this->personnelModel = new Personnel();
    }
    

    // Récupérer les personnels actifs avec pagination
    public function index() {
        // Nombre de personnels par page
        $limit = 9; 
        // Page actuelle
        $page = $_GET['page'] ?? 1; 
        // Calcul du début
        $start = ($page - 1) * $limit; 
    
        // Récupérer les personnels actifs avec pagination
        $personnelModel = new Personnel();
        $personnels = $personnelModel->getPersonnelWithPagination($start, $limit);
      
        // Compter le nombre total de personnels actifs
        $totalPersonnels = $personnelModel->countPersonnel(); 
        $totalPages = ceil($totalPersonnels / $limit);
    
        // Charger la vue avec les données paginées
        var_dump($totalPages, $totalPersonnels, $personnels);
        require '../app/views/personnel/listPersonnel.php';
    }

    /*public function indexArchived() {
        // Nombre de personnels par page
        $totalPages = $totalPages ?? 0; // Initialiser à 0 si non défini
        $limit = 9; 
        // Page actuelle
        $page = $_GET['page'] ?? 1; 
        // Calcul du début
        $start = ($page - 1) * $limit; 
    
        // Récupérer les personnels archivés avec pagination
        $personnelModel = new Personnel();
        $personnels = $personnelModel->getArchivedPersonnelWithPagination($start, $limit);
    
        // Compter le nombre total de personnels archivés
        $totalPersonnels = $personnelModel->countArchivedPersonnel();
        $totalPages = 0; // Initialiser à 0 par défaut
    
        // Vérifier qu'il y a des personnels pour calculer le nombre de pages
        if ($totalPersonnels > 0) {
            $totalPages = ceil($totalPersonnels / $limit);
        }
    
        // Charger la vue avec les données paginées
        require '../app/views/personnel/listPersonnelArchived.php';
    }
    */

   
    
    
    // Autres méthodes du contrôleur (index, show, edit, archive, restore)...

    // Afficher un personnel par ID
    public function show($id) {
        $personnel = $this->personnelModel::find($id);
        //require '../app/views/personnel/show.php'; // Afficher les détails du personnel
    }

    // Modifier un personnel
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $telephone = $_POST['telephone'];
            $matricule = $_POST['matricule'];
            $sexe = $_POST['sexe'];
            $role = $_POST['role'];
            $statut_compte = $_POST['statut_compte'];
            $id_salaire = $_POST['id_salaire'];

            // Validation des champs
            if (empty($nom) || empty($prenom) || empty($email) || empty($telephone)) {
                $_SESSION['error_message'] = "Veuillez remplir tous les champs.";
                header('Location: index.php?action=editPersonnel&id=' . $id);
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error_message'] = "Adresse email invalide.";
                header('Location: index.php?action=editPersonnel&id=' . $id);
                exit();
            }

            try {
                $this->personnelModel->update($id, $nom, $prenom, $email, $telephone, $matricule, $sexe, $role, $statut_compte, $id_salaire);
                $_SESSION['success_message'] = "Personnel modifié avec succès.";
                header('Location: index.php?action=listPersonnel');
                exit();
            } catch (Exception $e) {
                $_SESSION['error_message'] = "Erreur lors de la modification: " . $e->getMessage();
                header('Location: index.php?action=editPersonnel&id=' . $id);
                exit();
            }
        } else {
            $personnel = $this->personnelModel::find($id);
            require '../app/views/personnel/edit.php'; // Formulaire de modification
        }
    }



}


