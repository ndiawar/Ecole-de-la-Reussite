<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fichiers nécessaires
require '../config/config.php'; // Fichier de configuration
require '../app/controllers/AuthController.php'; // Inclure le contrôleur Auth
require '../app/controllers/PersonnelController.php'; // Inclure le contrôleur Personnel
require '../app/controllers/PaiementsController.php'; // Inclure le contrôleur Paiement
require '../app/controllers/EleveController.php'; // Inclure le contrôleur Eleve

require_once '../app/models/Personnel.php'; 
require_once '../app/models/Paiement.php';

// Instanciation des classes avec la connexion à la base de données
$db = (new Database())->getPDO(); // Connexion à la base de données
$personnelModel = new Personnel($db); 
$paiementModel = new Paiement($db);
$eleveController = new EleveController();
// $recuController = new RecuController($db);
$personnelController = new PersonnelController($personnelModel);
$paiementsController = new PaiementsController($paiementModel);
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
            $derniere_connexion = null; // ou date('Y-m-d H:i:s') pour la date actuelle

            echo $authController->register($nom, $prenom, $email, $telephone, $password, $sexe, $role, $statut_compte, $id_salaire, $derniere_connexion);
        } else {
            require '../app/views/auth/register.php'; // Afficher le formulaire d'inscription
        }
        break;

    case 'dashboard':
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

    case 'recu':
        // Afficher le reçu avec les informations de l'élève
        $nom = $_GET['nom'] ?? 'Nom Inconnu';
        $prenom = $_GET['prenom'] ?? 'Prénom Inconnu';
        $matricule = $_GET['matricule'] ?? '00000';
        $classe = $_GET['classe'] ?? 'Classe Inconnue';
        $tuteur = $_GET['tuteur'] ?? 'Tuteur Inconnu';
        $adresse = $_GET['adresse'] ?? 'Adresse Inconnue';
        $telephone = $_GET['telephone'] ?? 'Téléphone Inconnu';
        $date_paiement = $_GET['date_paiement'] ?? date('Y-m-d');
        $mode_paiement = $_GET['mode_paiement'] ?? 'Espèces';
        $numero_recu = $_GET['numero_recu'] ?? '00001';
        
        // Charger la vue du reçu
        require '../app/views/eleve/recu.php'; 
        break;

     
        case 'formulairePaiement':
            // Inclure le fichier de formulaire de paiement
            require '../app/views/eleve/formulaire_paiement.php';
            break;


    case 'listPaiements':
        $paiementsController->index(); // Lister les paiements
        break;

        case 'listEleves':
            $paiementsController->listElèves(); // Lister les élèves avec pagination
            break;


    case 'editPaiement':
        $id = $_GET['id'] ?? null;
        if ($id) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nouveau_nombre_heures = $_POST['nombre_heures'];
                $paiementsController->mettreAJourBulletin($id, $nouveau_nombre_heures);
            } else {
                $paiementsController->mettreAJourPaiement($id_recu, $nouveau_montant, $nouveau_moyen); // Modifier un paiement
            }
        }
        break;

    case 'annulerPaiement':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $paiementsController->annulerPaiement($id_recu); // Annuler un paiement
        }
        break;
        case 'modifierEleve':
            $id = $_GET['id'] ?? 0; // Récupérer l'ID de l'élève à modifier
            $eleveController->edit($id); // Modifier un élève par ID
            break;
    
        case 'detailsEleve':
            $id = $_GET['id'] ?? 0; // Récupérer l'ID de l'élève à afficher
            $eleveController->show($id); // Afficher les détails d'un élève par ID
            break;
    
        case 'archiverEleve':
            $id = $_GET['id'] ?? 0; // Récupérer l'ID de l'élève à archiver
            $eleveController->archive($id); // Archiver un élève par ID
            break;
    
        case 'listeElevesArchivés':
            $eleveController->listArchivedEleves(); // Afficher tous les élèves archivés
            break;
    
        case 'restaurerEleve':
            $id = $_GET['id'] ?? 0; // Récupérer l'ID de l'élève à restaurer
            $eleveController->restore($id); // Restaurer un élève archivé par ID
            break;
    

        // case $requestMethod === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_payment':
        //         // Appelle la méthode createPayment pour créer un nouveau paiement
        //         $paymentController->createPayment();
        //         break;
        
        //     // Vérifie si la méthode de requête est GET et si un ID d'élève est fourni
        // case $requestMethod === 'GET' && isset($_GET['id_eleve']):
        //         // Appelle la méthode showEleveInfo pour afficher les informations d'un élève spécifique
        //         $paymentController->showEleveInfo($_GET['id_eleve']);
        //         break;
        

        // case 'listElevesp':
        //     $eleveController = new EleveController($db);
        //     $eleveController->listElevesp();
        //     break;




        case 'create_payment':
            // Appelle la méthode payer pour créer un nouveau paiement et générer un reçu
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $result = $paiementsController->payer(); // Appelle la méthode payer()
                
                // Vérifiez le résultat pour gérer les erreurs
                if ($result === false) {
                    $error = "Une erreur s'est produite lors de la création du paiement. Veuillez réessayer.";
                    include '../app/views/eleve/formulaire_paiement.php'; // Inclut le formulaire avec un message d'erreur
                } else {
                    header('Location: /Ecole-de-la-Reussite/public/index.php?action=listEleves'); // Redirection vers la page de succès
                    exit();
                }
            } else {
                include '../app/views/eleve/formulaire_paiement.php'; // Afficher le formulaire de paiement si la méthode n'est pas POST
            }
            break;



            case 'getMensualites':
                // Appeler la méthode pour récupérer les mensualités
                $paiementController->getMensualites();
                break;
            
        
        case 'showEleveInfo':
            // Route pour afficher les informations d'un élève (GET avec ID de l'élève)
            if ($requestMethod === 'GET' && isset($_GET['id_eleve'])) {
                $result = $paiementsController->showEleveInfo($_GET['id_eleve']); // Appelle la méthode showEleveInfo
                if ($result === false) {
                    // Gérer le cas où l'élève n'est pas trouvé
                    $error = "Élève non trouvé.";
                    // Afficher une page d'erreur ou rediriger
                }
            }
            break;

    default:
        header("Location: index.php?action=login");
        break;
}
