<?php
require_once(__DIR__ . '/../models/Personnel.php'); // Assurez-vous que le chemin est correct

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
        require '../app/views/personnel/listPersonnel.php';
    }
    
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom'] ?? '',
                'prenom' => $_POST['prenom'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'sexe' => $_POST['sexe'] ?? '',
                'role' => $_POST['role'] ?? '',
                'id_salaire' => $_POST['id_salaire'] ?? '',
                'derniere_connexion' => $_POST['derniere_connexion'] ?? ''
            ];

            $errors = $this->personnelModel->validate($data);

            if (empty($errors)) {
                $this->personnelModel->update($id, $data);
                $_SESSION['success_message'] = "Personnel modifié avec succès.";
                header('Location: index.php?action=listPersonnel');
                exit();
            } else {
                $_SESSION['error_message'] = implode(", ", $errors);
            }
        }

        $personnelInfo = $this->personnelModel->findById($id);
        if (!$personnelInfo) {
            $_SESSION['error_message'] = "Personnel non trouvé.";
            header('Location: index.php?action=listPersonnel');
            exit();
        }

        require '../app/views/personnel/editPersonnel.php';
    }
    
    public function getPersonnel($id) {
        return $this->personnelModel->findById($id); // Remplacez cette ligne par votre logique de récupération
    }
    public function show($id) {
        $personnel = $this->personnelModel->find($id);
        if ($personnel) {
            require '../app/views/personnel/show.php'; // Afficher les détails du personnel
        } else {
            $_SESSION['error_message'] = "Personnel non trouvé.";
            header('Location: index.php?action=listPersonnel');
            exit();
        }
    }
    public function archive($id) {
        try {
            // Récupérer le nom du personnel à partir de l'ID
            $nom_personnel = $this->personnelModel->getNomPersonnel($id); // Assurez-vous que cette méthode existe dans votre modèle

            // Archiver le personnel en changeant son statut
            $this->personnelModel->archive($id);

            // Message de succès dans la session
            $_SESSION['archive_success_message'] = "Personnel archivé avec succès.l'archivage de: ";

        } catch (Exception $e) {
            // Gérer les erreurs et ajouter un message d'erreur dans la session
            $_SESSION['archive_error_message'] = "Erreur lors de l'archivage de : " . $e->getMessage();
        } finally {
            // Rediriger vers la liste du personnel après l'archivage
            header('Location: index.php?action=listPersonnel');
            exit();
        }
    }


    // Restaurer un personnel archivé
    public function restore($id) {
        try {
            $this->personnelModel->restore($id);
            $_SESSION['success_message'] = "Personnel restauré avec succès.";
            header('Location: index.php?action=listPersonnel');
            exit();
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Erreur lors de la restauration: " . $e->getMessage();
            header('Location: index.php?action=listPersonnel');
            exit();
        }
    }
}


