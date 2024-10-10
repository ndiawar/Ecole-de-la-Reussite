<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fichiers nécessaires
require '../config/config.php'; // Fichier de configuration
require '../app/controllers/AuthController.php'; // Inclure le contrôleur Auth
require '../app/controllers/PersonnelController.php'; // Inclure le contrôleur Personnel
require '../app/controllers/PaiementController.php'; // Inclure le contrôleur Paiement
require_once '../app/models/Personnel.php'; 
require_once '../app/models/Paiement.php';

// Instanciation des classes avec la connexion à la base de données
$db = (new Database())->getPDO(); // Connexion à la base de données
$personnelModel = new Personnel($db); 
$paiementModel = new Paiement($db);
// $recuController = new RecuController($db);
$personnelController = new PersonnelController($personnelModel);
$paiementController = new PaiementController($paiementModel);
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
            $statut_compte = $_POST['statut_compte'];
            $id_salaire = $_POST['id_salaire'];
            $derniere_connexion = null; // ou date('Y-m-d H:i:s') pour la date actuelle

            echo $authController->register($nom, $prenom, $email, $telephone, $password, $sexe, $role, $statut_compte, $id_salaire, $derniere_connexion);
        } else {
            require '../app/views/auth/register.php'; // Afficher le formulaire d'inscription
        }
        break;

    case 'dashboard':
        require '../app/views/dashboard.php'; // Inclure la vue du tableau de bord
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

    case 'paiement':
        // Gérer les paiements
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_employe = $_POST['id_employe'];
            $montant = $_POST['montant'];
            $mode_paiement = $_POST['mode_paiement'];

            echo $paiementController->enregistrerPaiement($id_employe, $montant, $mode_paiement);
        } else {
            require '../app/views/paiement/form.php'; // Afficher le formulaire de paiement
        }
        break;

    case 'listPaiements':
        $paiementController->index(); // Lister les paiements
        break;

    case 'listEleves':
        $paiementController->listEléves(); // Lister les paiements
        break;


    case 'editPaiement':
        $id = $_GET['id'] ?? null;
        if ($id) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nouveau_nombre_heures = $_POST['nombre_heures'];
                $paiementController->mettreAJourBulletin($id, $nouveau_nombre_heures);
            } else {
                $paiementController->mettreAJourPaiement($id_recu, $nouveau_montant, $nouveau_moyen); // Modifier un paiement
            }
        }
        break;

    case 'annulerPaiement':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $paiementController->annulerPaiement($id_recu); // Annuler un paiement
        }
        break;

    default:
        header("Location: index.php?action=login");
        break;
}
