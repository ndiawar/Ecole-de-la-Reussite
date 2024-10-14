<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fichiers nécessaires
require '../config/config.php'; // Fichier de configuration
require '../app/controllers/AuthController.php'; // Inclure le contrôleur Auth
require '../app/controllers/PersonnelController.php'; // Inclure le contrôleur Personnel
require '../app/controllers/PaiementController.php'; // Inclure le contrôleur Paiement



//require_once '../app/models/Personnel.php'; // 


// Instancier le modèle Personel
$personnelModel = new Personnel(); // Assurez-vous que l'instanciation de Personnel est correcte
// Instancier le contrôleur Personnel
$personnelController = new PersonnelController();

// Instancier le contrôleur AuthController avec le modèle Personnel
$authController = new AuthController($personnelModel);
//Instancier le contrôleur  PaiementControlleur
$paiementController= new PaiementController();


// Vérifier l'action passée dans l'URL (ex : ?action=login)
$action = $_GET['action'] ?? 'login'; // Si aucune action, par défaut 'login'

// Gestion du routage
switch ($action) {
    // case 'createPersonnel':
    //     // Gestion de la création d'un nouveau personnel
    //     $personnelController->create();
    //     break;

    case 'login':
        // Connexion d'un personnel
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $matricule = $_POST['matricule'];
            $password = $_POST['password'];
            echo $authController->login($matricule, $password);
        } else {
            require '../app/views/auth/login.php'; // Afficher le formulaire de connexion
        }
        break;
    case 'register':
        // Inscription d'un nouveau personnel
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $telephone = $_POST['telephone'];
            $password = $_POST['password'];
            $sexe = $_POST['sexe'];
            $role = $_POST['role'];
            $statut_compte = $_POST['statut_compte'];
            $id_salaire = $_POST['id_salaire'];
            
            // Initialiser derniere_connexion à NULL ou à la date actuelle
            $derniere_connexion = null; // ou date('Y-m-d H:i:s') pour la date actuelle
    
            // Appeler la méthode register en incluant derniere_connexion
            echo $authController->register($nom, $prenom, $email, $telephone, $password, $sexe, $role, $statut_compte, $id_salaire, $derniere_connexion);
        } else {
            require '../app/views/auth/register.php'; // Afficher le formulaire d'inscription
        }
        break;

    case 'Dashboard':
        require '../app/views/Dashboard.php'; // Inclure la vue du tableau de bord
        break;

    case 'logout':
        // Déconnexion du personnel
        $authController->logout();
        break;

    case 'listPersonnel':
        $personnelController->index(); // Liste des personnels
        break;
       
    case 'editPersonnel':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $personnelController->edit($id); // Modifier un personnel
        }
        break;

    case 'archivePersonnel':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $personnelController->archive($id); // Archiver un personnel
        }
        break;

   

    case 'restorePersonnel':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $personnelController->restore($id); // Restaurer un personnel
        }
        break;

    case 'liste_archives':
        // Récupérer les personnels archivés
        $personnelsArchives = $personnelController->listArchivedPersonnel(); // Assurez-vous que cette méthode existe et fonctionne
        include '..//app/views/personnel/listPersonnelArchived.php';
        break;

      
         // Nouvelle route pour afficher la vue des paiements
    case 'listPaiements':
        $paiementController->index();

        break;

    
        case 'archivePaiement':  // Archiver un paiement
            $id = $_GET['id'] ?? null;
            if ($id) {
                $paiementController->archive($id);
            }
            break;
    
        case 'restorePaiement':  // Restaurer un paiement archivé
            $id = $_GET['id'] ?? null;
            if ($id) {
                $paiementController->restore($id);
            }
            break;
    
        case 'listePaiementsArchives':  // Liste des paiements archivés
            $paiementsArchives = $paiementController->listArchivedPaiements();
            require '../app/views/paiement/listPaiementArchi.php';
            break;
    
    default:
       // header("Location: index.php?action=login");
        break;
    }