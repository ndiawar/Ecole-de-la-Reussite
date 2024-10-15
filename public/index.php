<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fichiers nécessaires
require '../config/config.php'; // Fichier de configuration
require '../app/controllers/AuthController.php'; // Inclure le contrôleur Auth
require '../app/controllers/PersonnelController.php'; // Inclure le contrôleur Personnel
require '../app/controllers/EleveController.php'; // Inclure le contrôleur Eleve
require '../app/controllers/PaiementSalarialController.php'; // Inclure le contrôleur Paiement
require_once '../app/models/Personnel.php'; // Inclure le modèle Personnel
require_once '../app/models/EleveModel.php'; // Inclure le modèle Eleve
require_once '../app/models/PaiementSalarialModel.php'; // Inclure le modèle Paiement

$authController = new AuthController(new Personnel());

// Instancier les modèles
$personnelModel = new Personnel();
$eleveModel = new EleveModel();
$paiementSalarialModel = new PaiementSalarialModel();

// Instancier les contrôleurs
$personnelController = new PersonnelController();
$eleveController = new EleveController();
$paiementSalarialController = new PaiementSalarialController();

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
                    header("Location: /Ecole-de-la-Reussite/public/index.php?action=Dashboard");
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
        if ($authController->isAuthenticated()) {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id) {
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
                    $eleveController->modifierEleve($id, $data); // Modifier l'élève
                } else {
                    $eleveController->afficherEleveParId($id);
                }
            } else {
                header('Location: index.php?action=listeEleves');
                exit;
            }
        } else {
            header("Location: index.php?action=login");
            exit();
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

        case 'archiveEleve':
            // Route pour archiver un élève
            if (isset($_GET['id'])) {
                $eleveController->archiveEleve();
                
            } else {
                echo "ID d'élève manquant.";
            }
            break;
        // case 'desarchiveEleve':
        //     // Route pour désarchiver un élève
        //     if (isset($_GET['id'])) {
        //         $eleveController->desarchiverEleve();
        //     } else {
        //         echo "ID d'élève manquant.";
        //     }
        //     break;

 // Routes pour Paiement Salarial
    case 'listPaiements':
        if ($authController->isAuthenticated()) {
            $page = $_GET['page'] ?? 1;
            $paiementSalarialController->afficherListePaiements($page);
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;


    case 'ajouterPaiement':
        if ($authController->isAuthenticated()) {
            $paiementSalarialController->ajouterPaiement();
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;

    case 'afficherPaiementParId':
        if ($authController->isAuthenticated()) {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id) {
                $paiementSalarialController->afficherPaiementParId($id);
            } else {
                header('Location: index.php?action=listPaiements');
                exit;
            }
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;

    case 'modifierPaiement':
        if ($authController->isAuthenticated()) {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($id) {
                $paiementSalarialController->modifierPaiement($id);
            } else {
                header('Location: index.php?action=listPaiements');
                exit;
            }
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;

    case 'archiverPaiement':
        if ($authController->isAuthenticated()) {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $paiementSalarialController->archiverPaiement($id);
            } else {
                header('Location: index.php?action=listPaiements');
                exit;
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