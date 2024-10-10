<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fichiers nécessaires
require '../config/config.php'; // Fichier de configuration
require '../app/controllers/AuthController.php'; // Inclure le contrôleur Auth
require '../app/controllers/PersonnelController.php'; // Inclure le contrôleur Personnel
require '../app/controllers/PaiementController.php'; // Inclure le contrôleur Pour le Paiement
require_once '../app/models/Personnel.php'; // 


// Instancier le modèle Personel
$personnelModel = new Personnel(); // Assurez-vous que l'instanciation de Personnel est correcte
// Instancier le contrôleur Personnel
$personnelController = new PersonnelController();

$paiementController = new PaiementController();

// Instancier le contrôleur AuthController avec le modèle Personnel
$authController = new AuthController($personnelModel);


// Vérifier l'action passée dans l'URL (ex : ?action=login)
$action = $_GET['action'] ?? 'login'; // Si aucune action, par défaut 'login'





// Gestion du routage
switch ($action) {

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

        case 'initiatePayment':
        case 'payment':
            // Appel de la méthode du contrôleur pour initier un paiement
            $paiementController->initiatePayment();
            break;
    
        case 'success':
            // Logique en cas de succès de la transaction
            require_once '../app/views/success.php';
            break;
    
        case 'cancel':
            // Logique en cas d'annulation de la transaction
            require_once '../app/views/cancel.php';
            break;
    
        case 'error':
            // Logique en cas d'erreur de la transaction
            require_once '../app/views/error.php';
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
            $id_salaire = $_POST['id_salaire'];
            
            // Initialiser derniere_connexion à NULL ou à la date actuelle
            $derniere_connexion = null; // ou date('Y-m-d H:i:s') pour la date actuelle
    
            // Appeler la méthode register en incluant derniere_connexion
            echo $authController->register($nom, $prenom, $email, $telephone, $password, $confirmPassword, $sexe, $role, $id_salaire, $derniere_connexion);
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
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // Debugging: Ajoutez un message pour vérifier que cette ligne est atteinte
                error_log("Accès à la liste des employés");
                $personnelController->index(); // Liste des personnels
            }
            break;
            

        case 'editPersonnel':
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            $personnelController->edit($id);
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

        default:
            header("Location: index.php?action=login");
            break;
    }