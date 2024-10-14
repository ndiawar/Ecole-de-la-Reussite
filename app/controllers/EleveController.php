<?php
require_once(__DIR__ . '/../models/Eleve.php'); // Assurez-vous que le chemin est correct

class EleveController {
    private $eleveModel;

    public function __construct() {
        //session_start(); // Démarrer la session pour gérer les messages d'erreur et de succès
        $this->eleveModel = new Eleve();
    }

    // Récupérer les élèves actifs avec pagination
    public function index() {
        // Nombre d'élèves par page
        $limit = 9; 
        // Page actuelle
        $page = $_GET['page'] ?? 1; 
        // Calcul du début
        $start = ($page - 1) * $limit; 
    
        // Récupérer les élèves actifs avec pagination
        $eleves = $this->eleveModel->getElevesWithPagination($start, $limit);
    
        // Compter le nombre total d'élèves actifs
        $totalEleves = $this->eleveModel->countEleves(); 
        $totalPages = ceil($totalEleves / $limit);
    
        // Charger la vue avec les données paginées
        require '../app/views/eleve/listEleve.php';
    }
    
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom'] ?? '',
                'prenom' => $_POST['prenom'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'date_naissance' => $_POST['date_naissance'] ?? '',
                'niveau' => $_POST['niveau'] ?? '',
                'matricule' => $_POST['matricule'] ?? ''
            ];

            $errors = $this->eleveModel->validate($data);

            if (empty($errors)) {
                $this->eleveModel->update($id, $data);
                $_SESSION['success_message'] = "Élève modifié avec succès.";
                header('Location: index.php?action=listEleve');
                exit();
            } else {
                $_SESSION['error_message'] = implode(", ", $errors);
            }
        }

        $eleveInfo = $this->eleveModel->findById($id);
        if (!$eleveInfo) {
            $_SESSION['error_message'] = "Élève non trouvé.";
            header('Location: index.php?action=listEleve');
            exit();
        }

        require '../app/views/eleve/editEleve.php';
    }
    
    // public function getEleve($id) {
    //     return $this->eleveModel->findById($id); // Remplacez cette ligne par votre logique de récupération
    // }
    
    // // public function show($id) {
    // //     $eleve = $this->eleveModel->find($id);
    //     if ($eleve) {
    //         require '../app/views/eleve/show.php'; // Afficher les détails de l'élève
    //     } else {
    //         $_SESSION['error_message'] = "Élève non trouvé.";
    //         header('Location: index.php?action=listEleve');
    //         exit();
    //     }
    // }
    
    public function archive($id) {
        try {
            // Récupérer le nom de l'élève à partir de l'ID
            $nom_eleve = $this->eleveModel->getNomEleve($id); // Assurez-vous que cette méthode existe dans votre modèle

            // Archiver l'élève en changeant son statut
            $this->eleveModel->archive($id);

            // Message de succès dans la session
            $_SESSION['archive_success_message'] = "Élève archivé avec succès: " . $nom_eleve;

        } catch (Exception $e) {
            // Gérer les erreurs et ajouter un message d'erreur dans la session
            $_SESSION['archive_error_message'] = "Erreur lors de l'archivage de : " . $e->getMessage();
        } finally {
            // Rediriger vers la liste des élèves après l'archivage
            header('Location: index.php?action=listEleve');
            exit();
        }
    }

    public function listArchivedEleves() {
        try {
            // Récupérer la liste des élèves archivés depuis le modèle
            $archivedEleves = $this->eleveModel->getArchivedEleves();

            // Si la liste est vide, on peut ajouter un message
            if (empty($archivedEleves)) {
                $_SESSION['no_archived_message'] = "Aucun élève archivé pour le moment.";
            } else {
                return $archivedEleves;
            }

        } catch (Exception $e) {
            // Gérer les erreurs et ajouter un message d'erreur dans la session
            $_SESSION['archive_error_message'] = "Erreur lors de la récupération des élèves archivés : " . $e->getMessage();
        }
    }

    // Restaurer un élève archivé
    public function restore($id) {
        try {
            $this->eleveModel->restore($id);
            $_SESSION['success_message'] = "Élève restauré avec succès.";
            header('Location: index.php?action=listEleve');
            exit();
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Erreur lors de la restauration: " . $e->getMessage();
            header('Location: index.php?action=listEleve');
            exit();
        }
    }

    


    // public function listElevesp() {
    //     $eleveModel = new Eleve($this->db);
    //     $eleves = $eleveModel->getElevesAvecPaiements();
        
    //     // Inclure la vue de la liste des élèves
    //     include __DIR__ . '../views/eleve/listEleve.php';
    // }
}
