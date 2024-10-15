<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fichiers nécessaires
require '../config/config.php'; // Fichier de configuration
require '../app/controllers/AuthController.php'; // Inclure le contrôleur Auth
require '../app/controllers/PersonnelController.php'; // Inclure le contrôleur Personnel
require '../app/controllers/PaiementEleveController.php'; // Inclure le contrôleur Personnel
require '../app/controllers/EleveController.php'; // Inclure le contrôleur Eleve
require_once '../app/models/Personnel.php'; // Inclure le modèle Personnel
require_once '../app/models/EleveModel.php'; // Inclure le modèle Eleve
require_once '../app/models/PaiementElevesModel.php'; // Inclure le modèle Eleve


$authController = new AuthController(new Personnel());


// Instancier les modèles
$personnelModel = new Personnel();
$eleveModel = new EleveModel();
$paiementModel = new PaiementEleveModel();

// Instancier les contrôleurs
$personnelController = new PersonnelController();
$paiementElevesController = new PaiementEleveController();
$eleveController = new EleveController();

// Vérifier l'action passée dans l'URL (ex : ?action=login)
$action = $_GET['action'] ?? 'login'; // Si aucune action, par défaut 'login'

// Gestion des messages de session
if (isset($_SESSION['error_message'])) {
    $errorMessage = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// Gestion du routage
switch ($action) {
    case 'login':
        // Connexion d'un personnel
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $matricule = $_POST['matricule'];
            $password = $_POST['password'];
            $authController->login($matricule, $password);
        } else {
            require '../app/views/auth/login.php'; // Afficher le formulaire de connexion
        }
        break;

    case 'register':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nom = $_POST['nom'];
                $prenom = $_POST['prenom'];
                $email = $_POST['email'];
                $telephone = $_POST['telephone'];
                $password = $_POST['password'];
                $sexe = $_POST['sexe'];
                $role = $_POST['role'];
                $id_salaire = $_POST['id_salaire'];
                $statut_compte = 'actif'; // ou un autre statut selon votre logique
                $derniere_connexion = date('Y-m-d H:i:s'); // ou null
        
                // Appeler la méthode register
                $authController->register($nom, $prenom, $email, $telephone, $password, $sexe, $role, $statut_compte, $id_salaire, $derniere_connexion);
                
                // Optionnel : Redirection ou message de succès
                $_SESSION['success_message'] = "Personnel ajouté avec succès !";
                header('Location: index.php?action=listPersonnel');
                exit;
            }
            require '../app/views/personnel/listPersonnel.php';
            break;
        
        

    case 'Dashboard':
        if ($authController->isAuthenticated()) {
            require '../app/views/Dashboard.php'; // Inclure la vue du tableau de bord
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'listPersonnel':
        if ($authController->isAuthenticated()) {
            $personnelController->index(); // Liste des personnels
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;

    case 'editPersonnel':
        if ($authController->isAuthenticated()) {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id) {
                $personnelController->edit($id);
            } else {
                header('Location: index.php?action=listPersonnel');
                exit;
            }
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;

    case 'archivePersonnel':
        if ($authController->isAuthenticated()) {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $personnelController->archive($id); // Archiver un personnel
            }
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;

    case 'restorePersonnel':
        if ($authController->isAuthenticated()) {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $personnelController->restore($id); // Restaurer un personnel
            }
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;

    case 'ajouterEleve':
        if ($authController->isAuthenticated()) {
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
                    header("Location: /Ecole-de-la-Reussite/public/index.php?action=listeEleve");
                    exit;
                } else {
                    $errors = $result['errors'];
                    $classes = $eleveModel->getClasses();
                    require '../app/views/eleve/ajoutEleve.php'; // Passer les erreurs à la vue
                }
            } else {
                $classes = $eleveModel->getClasses();
                require '../app/views/eleve/ajoutEleve.php'; // Assurez-vous que ce chemin est correct
            }
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;

    case 'listeEleves':
        if ($authController->isAuthenticated()) {
            $eleveController->afficherTousLesEleves();
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;

    case 'detailsEleve':
        if ($authController->isAuthenticated()) {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id) {
                $eleveController->afficherEleveParId($id); // Afficher les détails d'un élève
            } else {
                header('Location: index.php?action=listeEleves');
                exit;
            }
        } else {
            header("Location: index.php?action=login");
            exit();
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
                $eleveController->modifierEleve($data); // Assurez-vous de passer les deux arguments ici
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

    case 'supprimerEleve':
        if ($authController->isAuthenticated()) {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $eleveController->supprimerEleve($id); // Supprimer l'élève
            }
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;

    
    
    
        //Routes Pour action paiements Eleves 

        case 'listeElevesp':
            if ($authController->isAuthenticated()) {
                $eleveController = new EleveController();
                $eleveController->afficherTousLesElevesp(); // Appel à la méthode avec pagination
            } else {
                header("Location: index.php?action=login");
                exit();
            }
            break;


case 'listePaiementsEleves':
    if ($authController->isAuthenticated()) {
        $paiementElevesController = new PaiementEleveController();
        $page = $_GET['page'] ?? 1; // Récupérer le numéro de page
        $limit = 10; // Nombre d'éléments par page
        $searchTerm = $_GET['search'] ?? ''; // Récupérer le terme de recherche
        $paiementElevesController->index($page, $limit, $searchTerm);
    } else {
        header("Location: index.php?action=login");
        exit();
    }
    break;

case 'ajouterPaiement':
    if ($authController->isAuthenticated()) {
        // Instancier le contrôleur des paiements et gérer l'ajout du paiement
        $paiementElevesController = new PaiementEleveController();
        $paiementElevesController->ajouter(); // Gérer l'ajout d'un paiement
    } else {
        // Redirection vers la page de connexion si l'utilisateur n'est pas authentifié
        header("Location: index.php?action=login");
        exit();
    }
    break;
            
    
    case 'modifierPaiementEleves':
        if ($authController->isAuthenticated()) {
            $paiementElevesController = new PaiementEleveController();
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id) {
                $paiementElevesController->modifier($id); // Gérer la modification d'un paiement
            } else {
                header('Location: index.php?action=listePaiements');
                exit;
            }
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;
    
    case 'archiverPaiementEleves':
        if ($authController->isAuthenticated()) {
            $paiementElevesController = new PaiementEleveController();
            $id = $_GET['id'] ?? null;
            if ($id) {
                $paiementElevesController->archiver($id); // Archiver un paiement
            }
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;
    
    case 'listeArchivesEleves':
        if ($authController->isAuthenticated()) {
            $paiementElevesController = new PaiementEleveController();
            $paiementElevesController->archives(); // Afficher les paiements archivés
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;
    
    case 'desarchiverPaiementEleves':
        if ($authController->isAuthenticated()) {
            $paiementElevesController = new PaiementEleveController();
            $id = $_GET['id'] ?? null;
            if ($id) {
                $paiementElevesController->unarchive($id); // Désarchiver un paiement
            }
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;
    

    default:
        header("Location: index.php?action=login");
        break;
}
