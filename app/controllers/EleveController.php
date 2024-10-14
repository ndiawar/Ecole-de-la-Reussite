<?php 
require_once(__DIR__ . '/../models/EleveModel.php'); // Assurez-vous que le chemin est correct


class EleveController
{
    private $eleveModel;

    public function __construct()
    {
        $this->eleveModel = new EleveModel();
    }

    // public function ajouterEleve($data)
    // {
    //     $result = $this->eleveModel->ajouterEleve($data);

    //     if ($result['success']) {
    //         // Afficher une vue de succès ou rediriger vers la liste des élèves
    //         header("Location: /Ecole-de-la-Reussite/public/index.php?action=Dashboard");
    //         exit;
    //     } else {
    //         // Afficher les erreurs ou rediriger avec les messages d'erreur
    //         header("Location: /Ecole-de-la-Reussite/public/index.php?action=Dashboard&error=" . urlencode(implode(', ', $result['errors'])));
    //         exit;
    //     }
    // }

    public function showForm() {
        // Récupération des classes
        $classes = $this->eleveModel->getClasses();
        var_dump($classes); // Ajoutez ceci pour vérifier les classes récupérées
    
        // Inclure la vue du formulaire avec les classes
        include 'views/formulaire.php';
    }
    

    public function ajouterEleve() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tuteur_nom' => $_POST['tuteur_nom'] ?? '',
                'tuteur_prenom' => $_POST['tuteur_prenom'] ?? '',
                'tuteur_telephone' => $_POST['tuteur_telephone'] ?? '',
                'tuteur_adresse' => $_POST['tuteur_adresse'] ?? '',
                'tuteur_email' => $_POST['tuteur_email'] ?? '',
                'eleve_nom' => $_POST['eleve_nom'] ?? '',
                'eleve_prenom' => $_POST['eleve_prenom'] ?? '',
                'eleve_adresse' => $_POST['eleve_adresse'] ?? '',
                'eleve_email' => $_POST['eleve_email'] ?? '',
                'eleve_sexe' => $_POST['eleve_sexe'] ?? '',
                'eleve_date_naissance' => $_POST['eleve_date_naissance'] ?? '',
                'eleve_niveau' => $_POST['eleve_niveau'] ?? '',
                'classe_id' => $_POST['classe_id'] ?? ''
            ];
    
            $result = $this->eleveModel->ajouterEleve($data);
    
            if ($result['success']) {
                // Rediriger ou afficher un message de succès
                header("Location: /Ecole-de-la-Reussite/public/index.php?action=Dashboard");
                exit;
            } else {
                // Gérer les erreurs
                $errors = $result['errors'];
                $classes = $this->eleveModel->getClasses(); // Fetch classes again to display in the form
                require '../app/views/eleve/ajoutEleve.php'; // Pass errors to the view
            }
        } else {
            $classes = $this->eleveModel->getClasses(); // Ensure classes are fetched when showing the form
            require '../app/views/eleve/ajoutEleve.php'; // Ensure this path is correct
        }
    }
    


    public function afficherTousLesEleves()
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
        require '../app/views/eleve/listEleve.php';
    }
    

    public function afficherEleveParId($id)
    {
        $eleve = $this->eleveModel->afficherEleveParId($id);
        if ($eleve) {
            // Inclure la vue qui affichera les détails de l'élève
            require '../app/views/eleve/editEleve.php';
        } else {
            // Afficher une vue d'erreur ou rediriger
            header('Location: index.php?action=listEleve');
            exit;
        }
    }
    public function modifierEleve($id, $data)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'modifierEleve') {
            // Vérifier si l'ID est valide
            if (!$id || !is_numeric($id)) {
                echo "ID invalide";
                exit;
            }
    
            // Vérifiez que les données de formulaire sont présentes
            if (empty($data)) {
                echo "Données de formulaire manquantes";
                exit;
            }
    
            // Appeler la méthode du modèle pour modifier l'élève
            $result = $this->eleveModel->modifierEleve($id, $data);
    
            if ($result['success']) {
                // Rediriger vers la liste des élèves en cas de succès
                header('Location: index.php?action=listeEleves');
                exit;
            } else {
                // En cas d'erreur, les afficher pour le débogage
                if (isset($result['errors']) && !empty($result['errors'])) {
                    echo "Erreur lors de la mise à jour : " . implode(', ', $result['errors']);
                } else {
                    echo "Une erreur inconnue est survenue lors de la mise à jour.";
                }
                exit;
            }
        } else {
            echo "Méthode non autorisée ou action incorrecte";
            exit;
        }
    }

    public function supprimerEleve($id)
    {
        $result = $this->eleveModel->supprimerEleve($id);

        if ($result['success']) {
            // Rediriger vers la liste des élèves
            header('Location: /eleves/liste.php?message=suppression_reussie');
            exit;
        } else {
            // Rediriger avec un message d'erreur
            header('Location: /eleves/liste.php?message=' . urlencode(implode(', ', $result['errors'])));
            exit;
        }
    }



      public function archiveEleve()
      {
      if (isset($_GET['id'])) {
        $id = $_GET['id'];
        if ($this->eleveModel->archiverEleve($id)) {
            $_SESSION['archive_success_message'] = "L'élève a été archivé avec succès.";
        } else {
            $_SESSION['archive_error_message'] = "Une erreur est survenue lors de l'archivage.";
        }
        header('Location: index.php?action=listeEleves');
    }
}
 // Afficher les élèves archivés
//  public function archiveEleves() {
//     $eleves_archives = $this->model->getArchivedStudents();
    
//     // Vérifier si une session de succès est définie
//     if (isset($_SESSION['desarchive_success_message'])) {
//         // Ne rien faire ici, la variable est déjà dans la session
//     }

//     // Charger la vue et passer les données
//     include_once 'views/eleve/archiveEleve.php';
// }

    //  // Désarchiver un élève
    //  public function desarchiveEleve($id) {
    // if ($this->model->unarchiveStudent($id)) {
    //     $_SESSION['desarchive_success_message'] = "L'élève a été désarchivé avec succès.";
    // } else {
    //     $_SESSION['desarchive_success_message'] = "Échec du désarchivage de l'élève.";
    // }

    // Redirigez vers la liste des élèves archivés
    // header('Location: index.php?action=archiveEleve');
    // exit();
}




