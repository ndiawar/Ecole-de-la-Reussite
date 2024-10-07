<?php

// Inclusion du modèle Personnel
require_once(__DIR__ . '/../models/Personnel.php'); // Assurez-vous que le chemin est correct

class AuthController {
    private $personnelModel;

    public function __construct(Personnel $personnelModel) {
        $this->personnelModel = $personnelModel; // Initialiser le modèle Personnel
    }

    public function login($identifiant, $mot_passe) {
        // Démarrer la session
        session_start();
    
        // Vérifier si les champs sont vides
        if (empty($identifiant) || empty($mot_passe)) {
            // Stocker le message d'erreur dans la session
            $_SESSION['error_message'] = "Veuillez remplir tous les champs.";
            // Rediriger vers login.php
            header("Location: /Ecole-de-la-Reussite/public/index.php?action=login");
            exit();
        }
    
        $personnel = $this->personnelModel->findByMatriculeOrEmail($identifiant);
    
        // Vérification de l'existence du personnel
        if ($personnel === null) {
            // Stocker le message d'erreur dans la session
            $_SESSION['error_message'] = "Aucun personnel trouvé avec cet identifiant.";
            // Rediriger vers login.php
            header("Location: /Ecole-de-la-Reussite/public/index.php?action=login");
            exit();
        }
    
        // Vérifier si le mot de passe est correct
        if (password_verify($mot_passe, $personnel['mot_passe'])) {
            // Démarrer la session pour l'utilisateur
            $_SESSION['personnel_id'] = $personnel['id_personnel'];
            $_SESSION['role'] = $personnel['role'];
            // Ajouter un message de succès à la session
            $_SESSION['success_message'] = "Bienvenue, vous êtes connecté !";
            // Redirection vers le tableau de bord
            header("Location: /Ecole-de-la-Reussite/public/index.php?action=Dashboard");
            exit();
        } else {
            // Le mot de passe est incorrect
            $_SESSION['error_message'] = "Mot de passe incorrect.";
            // Rediriger vers login.php
            header("Location: /Ecole-de-la-Reussite/public/index.php?action=login");
            exit();
        }
    }

    // Inscription d'un nouveau personnel
    public function register($nom, $prenom, $email, $telephone, $password, $sexe, $role, $statut_compte, $id_salaire, $derniere_connexion) {
        // Démarrer la session
        session_start();
        // Validation des champs
        if (empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($password) || empty($sexe) || empty($role) || empty($statut_compte) || empty($id_salaire)) {
            // Stocker le message d'erreur dans la session
            $_SESSION['error_message'] = "Veuillez remplir tous les champs.";
            // Rediriger vers register.php
            header("Location: /Ecole-de-la-Reussite/public/index.php?action=register");
            exit();
        }
    
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Stocker le message d'erreur dans la session
            $_SESSION['error_message'] = "Adresse email invalide.";
            // Rediriger vers register.php
            header("Location: /Ecole-de-la-Reussite/public/index.php?action=register");
            exit();
        }
    
        if (!preg_match('/^[0-9]{9}$/', $telephone)) {
            // Stocker le message d'erreur dans la session
            $_SESSION['error_message'] = "Le numéro de téléphone doit contenir 9 chiffres.";
            // Rediriger vers register.php
            header("Location: /Ecole-de-la-Reussite/public/index.php?action=register");
            exit();
        }
    
        if (strlen($password) < 8) {
            // Stocker le message d'erreur dans la session
            $_SESSION['error_message'] = "Le mot de passe doit contenir au moins 8 caractères.";
            // Rediriger vers register.php
            header("Location: /Ecole-de-la-Reussite/public/index.php?action=register");
            exit();
        }
    
        // Générer un matricule unique
        $matricule = strtoupper(substr($prenom, 0, 2) . substr($nom, 0, 2) . uniqid()); // Ex. : "JODO613b86ef0d9e"
    
        // Vérification si le personnel existe déjà
        if ($this->personnelModel->findByMatricule($matricule)) {
            // Stocker le message d'erreur dans la session
            $_SESSION['error_message'] = "Le compte avec cet Matricule est déjà pris.";
            // Rediriger vers register.php
            header("Location: /Ecole-de-la-Reussite/public/index.php?action=register");
            exit();
        }
    
        // Créer un nouvel personnel
        try {
            $this->personnelModel->create($nom, $prenom, $email, $telephone, $matricule, $password, $sexe, $role, $statut_compte, $id_salaire, $derniere_connexion);
             // Ajouter un message de succès à la session
            $_SESSION['success_message'] = "Personnel, ajouté avec succés !";
             header("Location: /Ecole-de-la-Reussite/public/index.php?action=listPersonnel"); // Rediriger vers la page de connexion
            exit(); // Terminer le script
        } catch (Exception $e) {
            // Stocker le message d'erreur dans la session
            $_SESSION['error_message'] = $e->getMessage(); // Retourner le message d'erreur
            // Rediriger vers register.php
            header("Location: /Ecole-de-la-Reussite/public/index.php?action=register");
            exit();
        }
    }
   


    // Déconnexion du personnel
    public function logout() {
        session_start();
        session_destroy(); // Détruire la session
        header("Location: /Ecole-de-la-Reussite/public/index.php?action=login");
        exit(); // Terminer le script
    }

    // Vérifier si le personnel est authentifié
    public function isAuthenticated() {
        session_start();
        return isset($_SESSION['personnel_id']); // Retourner true si le personnel est authentifié
    }
}