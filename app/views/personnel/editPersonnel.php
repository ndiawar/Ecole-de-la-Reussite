<?php
// Démarrer la session pour gérer les erreurs ou les succès de l'inscription
// session_start();

// Inclusion du fichier AuthController
require_once(__DIR__ . '/../../controllers/AuthController.php');
require_once(__DIR__ . '/../../models/Personnel.php'); // Assurez-vous que le modèle Personnel est inclus

// Initialisation du contrôleur AuthController
$personnelModel = new Personnel(); // Créer une instance de votre modèle Personnel
$authController = new AuthController($personnelModel); // Passer le modèle au contrôleur

// Vérifier si l'ID du personnel a été fourni
$personnelId = $_GET['id'] ?? null;
$personnelData = null;

if ($personnelId) {
    // Récupérer les données du personnel par son ID
    $personnelData = Personnel::find($personnelId); // Utilisez la méthode find
    if (!$personnelData) {
        $errorMessage = "Aucun personnel trouvé avec cet ID.";
    }
} else {
    $errorMessage = "ID du personnel non fourni.";
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $password = $_POST['password'] ?? '';
    $sexe = $_POST['sexe'] ?? '';
    $role = $_POST['role'] ?? '';
    $statut_compte = $_POST['statut_compte'] ?? '';
    $id_salaire = $_POST['id_salaire'] ?? '';
    $derniere_connexion = $_POST['derniere_connexion'] ?? '';
    $adresse = $_POST['adresse'] ?? ''; 
    $date_prise_de_poste = $_POST['date_prise_de_poste'] ?? ''; // Ajout de la date de prise de poste

    // Vérifier que tous les champs sont remplis
    if (empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($password) || empty($sexe) || empty($role) || empty($statut_compte) || empty($id_salaire) || empty($derniere_connexion) || empty($adresse) || empty($date_prise_de_poste)) {
        $errorMessage = "Tous les champs sont obligatoires.";
    } else {
        // Appeler la méthode pour mettre à jour les données du personnel
        $result = $authController->updatePersonnel($personnelId, $nom, $prenom, $email, $telephone, $password, $sexe, $role, $statut_compte, $id_salaire, $derniere_connexion, $adresse, $date_prise_de_poste); // Passer tous les champs

        if ($result === true) {
            echo "Mise à jour réussie.";
        } else {
            $errorMessage = $result; // Afficher le message d'erreur retourné par le contrôleur
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Employé</title>
    <link rel="stylesheet" href="../app/views/auth/css/create.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
     
</head>
<body>
    
<form action="" method="POST">
    <h2 class="text-center">Modifier un employé</h2>

    <?php if (!empty($errorMessage)) : ?>
        <div class="error-message" style="color: red;"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <div><hr><p>Informations personnelles</p></div>

    <div class="row">
        <div class="col-md-6">
            <!-- Champ du nom -->
            <div class="mb-3">
                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($personnelData['nom'] ?? '') ?>" required>
            </div>

            <!-- Champ de l'email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($personnelData['email'] ?? '') ?>" required>
            </div>

            <!-- Champ du téléphone -->
            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($personnelData['telephone'] ?? '') ?>" required>
            </div>

            <!-- Champ du sexe -->
            <div class="mb-3">
                <label for="sexe" class="form-label">Sexe <span class="text-danger">*</span></label>
                <select class="form-select" id="sexe" name="sexe" required>
                    <option value="masculin" <?= ($personnelData['sexe'] === 'masculin') ? 'selected' : '' ?>>Masculin</option>
                    <option value="feminin" <?= ($personnelData['sexe'] === 'feminin') ? 'selected' : '' ?>>Féminin</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Champ du prénom -->
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($personnelData['prenom'] ?? '') ?>" required>
            </div>

            <!-- Champ du mot de passe -->
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <!-- Champ de l'adresse -->
            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="adresse" name="adresse" value="<?= htmlspecialchars($personnelData['adresse'] ?? '') ?>" required>
            </div>   
        </div>
    </div>

    <div><hr><p>Informations professionnelles</p></div>
    <div class="row">
        <div class="col-md-6">
            <!-- Champ du rôle -->
            <div class="mb-3">
                <label for="role" class="form-label">Poste <span class="text-danger">*</span></label>
                <select class="form-select" id="role" name="role" required>
                    <option value="Directeur" <?= ($personnelData['role'] === 'Directeur') ? 'selected' : '' ?>>Directeur</option>
                    <option value="Surveillant" <?= ($personnelData['role'] === 'Surveillant') ? 'selected' : '' ?>>Surveillant</option>
                    <option value="Enseignant" <?= ($personnelData['role'] === 'Enseignant') ? 'selected' : '' ?>>Enseignant</option>
                    <option value="Comptable" <?= ($personnelData['role'] === 'Comptable') ? 'selected' : '' ?>>Comptable</option>
                </select>
            </div>

            <!-- Champ du statut du compte -->
            <div class="mb-3">
                <label for="statut_compte" class="form-label">Statut du compte <span class="text-danger">*</span></label>
                <select class="form-select" id="statut_compte" name="statut_compte" required>
                    <option value="actif" <?= ($personnelData['statut_compte'] === 'actif') ? 'selected' : '' ?>>Actif</option>
                    <option value="inactif" <?= ($personnelData['statut_compte'] === 'inactif') ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
        </div>
        <div class="col md-6">
            <!-- Champ du salaire -->
            <div class="mb-3">
                <label for="id_salaire" class="form-label">Salaire <span class="text-danger">*</span></label>
                <select class="form-select" id="id_salaire" name="id_salaire" required>
                    <option value="1" <?= ($personnelData['id_salaire'] == 1) ? 'selected' : '' ?>>Salaire fixe employé</option>
                    <option value="2" <?= ($personnelData['id_salaire'] == 2) ? 'selected' : '' ?>>Salaire fixe enseignant</option>
                    <option value="3" <?= ($personnelData['id_salaire'] == 3) ? 'selected' : '' ?>>Salaire Professeur (Horaire)</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="date_prise_de_poste" class="form-label">Date de prise de poste <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="date_prise_de_poste" name="date_prise_de_poste" value="<?= htmlspecialchars($personnelData['date_prise_de_poste'] ?? '') ?>" required>
            </div>
        </div>
    </div>

    <div class="text-center">
    <!-- Bouton Mettre à jour avec couleur personnalisée -->
    <a href="index.php?action=listPersonnel"<button type="submit" class="btn" style="background-color: #004D40; color: white;">Mettre à jour</button></a>

    <!-- Bouton Retour avec couleur personnalisée -->
    <a href="index.php?action=listPersonnel" class="btn" style="background-color: #004D40; color: white;">Retour</a>
</div>


</form>

</body>
</html>
