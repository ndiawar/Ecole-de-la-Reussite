<?php
// Démarrer la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// Inclusion du fichier AuthController
require_once(__DIR__ . '/../../controllers/PersonnelController.php');
require_once(__DIR__ . '/../../models/Personnel.php');

// Initialisation du modèle et du contrôleur
$personnelModel = new Personnel();
$personnelController = new PersonnelController();

// Gestion des erreurs
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
$successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
unset($_SESSION['error_message'], $_SESSION['success_message']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Employé</title>
    <link rel="stylesheet" href="../app/views/auth/css/create.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<form action="index.php?action=createPersonnel" method="POST">
    <h2 class="text-center">Ajouter un employé</h2>

    <!-- Afficher un message d'erreur ou de succès s'il y en a -->
    <?php if (!empty($errorMessage)) : ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>
    <?php if (!empty($successMessage)) : ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>

    <div><hr><p>Informations personnelles</p></div>
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez le nom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Entrez l'email" required>
            </div>
            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Entrez le numéro de téléphone" required>
            </div>
            <div class="mb-3">
                <label for="sexe" class="form-label">Sexe <span class="text-danger">*</span></label>
                <select class="form-select" id="sexe" name="sexe" required>
                    <option value="masculin">Masculin</option>
                    <option value="feminin">Féminin</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrez le prénom" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password" name="mot_passe" placeholder="Entrez le mot de passe" required>
            </div>
            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Entrez l'adresse" required>
            </div>
        </div>
    </div>

    <div><hr><p>Informations professionnelles</p></div>
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="role" class="form-label">Poste <span class="text-danger">*</span></label>
                <select class="form-select" id="role" name="role" required>
                    <option value="Directeur">Directeur</option>
                    <option value="Surveillant">Surveillant</option>
                    <option value="Enseignant">Enseignant</option>
                    <option value="Comptable">Comptable</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="statut_compte" class="form-label">Statut du compte <span class="text-danger">*</span></label>
                <select class="form-select" id="statut_compte" name="statut_compte" required>
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="id_salaire" class="form-label">Salaire <span class="text-danger">*</span></label>
                <select class="form-select" id="id_salaire" name="id_salaire" required>
                    <option value="1">Salaire fixe employé</option>
                    <option value="2">Salaire fixe enseignant</option>
                    <option value="3">Salaire Professeur (Horaire)</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="derniere_connexion" class="form-label">Date de prise de poste <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="derniere_connexion" name="derniere_connexion" required>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </div>
</form>

<script src="../app/views/auth/js/create.js"></script>
</body>
</html>