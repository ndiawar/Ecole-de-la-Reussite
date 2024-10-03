<?php

// Inclusion du modèle Personnel
require_once(__DIR__ . '/../models/Personnel.php'); // Assurez-vous que le chemin est correct

class AuthController {
    private $personnelModel;

    public function __construct(Personnel $personnelModel) {
        $this->personnelModel = $personnelModel; // Initialiser le modèle Personnel
    }

    // Connexion d'un personnel
    public function login($identifiant, $password) {
        // Vérifier si l'identifiant est un matricule ou un email
        $personnel = $this->personnelModel->findByMatriculeOrEmail($identifiant);

        // Vérification de l'existence du personnel
        if ($personnel === null) {
            return "Aucun personnel trouvé avec cet identifiant.";
        }

        // Vérifier si le mot de passe est correct
        if (password_verify($password, $personnel['mot_passe'])) {
            // Démarrer la session
            session_start();
            $_SESSION['personnel_id'] = $personnel['id_personnel'];
            $_SESSION['role'] = $personnel['role'];
            // Redirection vers le tableau de bord
            header("Location: /ChatGptSchool/public/index.php?action=Dashboard");
            exit();
        } else {
            // Le mot de passe est incorrect
            return "Mot de passe incorrect.";
        }
    }

    // Inscription d'un nouveau personnel
    public function register($nom, $prenom, $email, $telephone, $matricule, $password, $sexe, $role, $statut_compte, $id_salaire) {
        // Vérification si le personnel existe déjà
        if ($this->personnelModel->findByMatricule($matricule)) {
            return "Matricule déjà pris."; // Message d'erreur si le personnel existe
        }
    
        // Validation des champs
        if (empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($matricule) || empty($password) || empty($sexe) || empty($role) || empty($statut_compte) || empty($id_salaire)) {
            return "Tous les champs doivent être remplis."; // Vérification des champs obligatoires
        }
    
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Adresse email invalide."; // Validation de l'email
        }
    
        if (!preg_match('/^[0-9]{9}$/', $telephone)) {
            return "Le numéro de téléphone doit contenir 9 chiffres."; // Validation du numéro de téléphone
        }
    
        if (strlen($password) < 8) {
            return "Le mot de passe doit contenir au moins 8 caractères."; // Validation de la longueur du mot de passe
        }
    
        // Créer un nouvel personnel
        try {
            $this->personnelModel->create($nom, $prenom, $email, $telephone, $matricule, $password, $sexe, $role, $statut_compte, $id_salaire);
            header("Location: auth/login.php"); // Rediriger vers la page de connexion
            exit(); // Terminer le script
        } catch (Exception $e) {
            return $e->getMessage(); // Retourner le message d'erreur
        }
    }
    

    // Déconnexion du personnel
    public function logout() {
        session_start();
        session_destroy(); // Détruire la session
        header("Location: login.php"); // Rediriger vers la page de connexion
        exit(); // Terminer le script
    }

    // Vérifier si le personnel est authentifié
    public function isAuthenticated() {
        session_start();
        return isset($_SESSION['personnel_id']); // Retourner true si le personnel est authentifié
    }
}
