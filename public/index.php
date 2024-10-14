<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fichiers nécessaires
require '../config/config.php'; // Fichier de configuration
require '../app/controllers/AuthController.php'; // Inclure le contrôleur Auth
require '../app/controllers/PersonnelController.php'; // Inclure le contrôleur Personnel
require '../app/controllers/EleveController.php'; // Inclure le contrôleur Eleve
require_once '../app/models/Personnel.php'; // Inclure le modèle Personnel
require_once '../app/models/EleveModel.php'; // Inclure le modèle Eleve

// Instancier les modèles
$personnelModel = new Personnel();
$eleveModel = new EleveModel();

// Instancier les contrôleurs
$personnelController = new PersonnelController();
$authController = new AuthController($personnelModel);
$eleveController = new EleveController();

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

    case 'register':
        // Inscription d'un nouveau personnel
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $telephone = $_POST['telephone'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password']; // Assurez-vous d'inclure ce champ
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
            error_log("Accès à la liste des employés");
            $personnelController->index(); // Liste des personnels
        }
        break;

    case 'editPersonnel':
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $personnelController->edit($id);
        } else {
            // Gérer l'erreur d'ID invalide
            header('Location: index.php?action=listPersonnel');
            exit;
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

        case 'ajouterEleve':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'eleve_nom' => $_POST['eleve_nom'],
                    'eleve_prenom' => $_POST['eleve_prenom'],
                    'eleve_adresse' => $_POST['eleve_adresse'],
                    'eleve_email' => $_POST['eleve_email'],
                    'eleve_sexe' => $_POST['eleve_sexe'],
                    'eleve_date_naissance' => $_POST['eleve_date_naissance'],
                    'classe_id' => $_POST['classe_id'],
                    'tuteur_nom' => $_POST['tuteur_nom'],
                    'tuteur_prenom' => $_POST['tuteur_prenom'],
                    'tuteur_telephone' => $_POST['tuteur_telephone'],
                    'tuteur_adresse' => $_POST['tuteur_adresse'],
                    'tuteur_email' => $_POST['tuteur_email']
                ];
                
                // Appeler la méthode d'ajout d'élève
                $result = $eleveModel->ajouterEleve($data);
                
                if ($result['success']) {
                    // Rediriger ou afficher un message de succès
                    header("Location: /Ecole-de-la-Reussite/public/index.php?action=Dashboard");
                    exit;
                } else {
                    // Gérer les erreurs
                    $errors = $result['errors'];
                    $classes = $eleveModel->getClasses(); // Fetch classes again to display in the form
                    require '../app/views/eleve/ajoutEleve.php'; // Pass errors to the view
                }
            } else {
                $classes = $eleveModel->getClasses(); // Ensure classes are fetched when showing the form
                require '../app/views/eleve/ajoutEleve.php'; // Ensure this path is correct
            }
            break;
        
        case 'listeEleves':
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                error_log("Accès à la liste des élèves"); // Ajoute un log ici
                $eleveController->afficherTousLesEleves();
            }
            break;
            

    case 'detailsEleve':
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $eleveController->afficherEleveParId($id); // Afficher les détails d'un élève
        } else {
            // Gérer l'erreur d'ID invalide
            header('Location: index.php?action=listeEleves');
            exit;
        }
        break;

        case 'modifierEleve':
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'eleve_nom' => $_POST['eleve_nom'],
                    'eleve_prenom' => $_POST['eleve_prenom'],
                    'eleve_email' => $_POST['eleve_email'],
                    'eleve_sexe' => $_POST['eleve_sexe'],
                    'eleve_adresse' => $_POST['eleve_adresse'],
                    'eleve_date_naissance' => $_POST['eleve_date_naissance'],
                    'tuteur_nom' => $_POST['tuteur_nom'],
                    'tuteur_prenom' => $_POST['tuteur_prenom'],
                    'tuteur_telephone' => $_POST['tuteur_telephone'],
                    'tuteur_email' => $_POST['tuteur_email'],
                    'tuteur_adresse' => $_POST['tuteur_adresse'],
                    'classe_id' => $_POST['classe_id']
                ];
                $eleveController->modifierEleve($id, $data); // Assurez-vous de passer les deux arguments ici
            } else {
                $eleveController->afficherEleveParId($id);
            }
            break;

        case 'archiveEleve':
            // Route pour archiver un élève
            if (isset($_GET['id'])) {
                $eleveController->archiveEleve();
                
            } else {
                echo "ID d'élève manquant.";
            }
            break;
            case 'desarchiveEleve':
                // Route pour désarchiver un élève
                if (isset($_GET['id'])) {
                    $eleveController->desarchiverEleve();
                } else {
                    echo "ID d'élève manquant.";
                }
                break;
            
    default:
        header("Location: index.php?action=login");
        break;
}
