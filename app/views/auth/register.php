<?php
// Démarrer la session pour gérer les erreurs ou les succès de l'inscription
// session_start();

// Inclusion du fichier AuthController
require_once(__DIR__ . '/../../controllers/AuthController.php');
require_once(__DIR__ . '/../../models/Personnel.php'); // Assurez-vous que le modèle Personnel est inclus

// Initialisation du contrôleur AuthController
$personnelModel = new Personnel(); // Créer une instance de votre modèle Personnel
$authController = new AuthController($personnelModel); // Passer le modèle au contrôleur

// Vérifier si le formulaire a été soumis
$errorMessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $password = $_POST['password'];
    $sexe = $_POST['sexe'];
    $role = $_POST['role'];
    $statut_compte = $_POST['statut_compte'];
    $id_salaire = $_POST['id_salaire'];

    // Appeler la méthode register du contrôleur pour traiter l'inscription
    $result = $authController->register($nom, $prenom, $email, $telephone, $password, $sexe, $role, $statut_compte, $id_salaire, $derniere_connexion);

    // Si le résultat est une chaîne, cela signifie qu'il y a eu une erreur
    if (is_string($result)) {
        $errorMessage = $result; // Afficher l'erreur sur la page
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Employé</title>
    <link rel="stylesheet" href="../app/views/auth/css/create.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Lien vers Bootstrap -->
</head>
<body>
    
<form action="index.php?action=register" method="POST">
  <!-- Titre du formulaire -->
  <h2 class="text-center">Ajouter un employé</h2>

  <!-- Afficher un message d'erreur s'il y en a -->
  <?php if (!empty($errorMessage)) : ?>
                <div class="error-message" style="color: red;"><?= htmlspecialchars($errorMessage) ?></div>
            <?php endif; ?>

  <div><hr><p>Informations personnelles</p></div>

  <div class="row">
    <!-- Informations personnelles -->
    <div class="col-md-6">
      <div class="mb-3">
        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="nom" placeholder="Entrez le nom" name="nom" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" class="form-control" id="email" placeholder="Entrez l'email" name="email" required>
      </div>

      <div class="mb-3">
        <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="telephone" placeholder="Entrez le numéro de téléphone" name="telephone" required>
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
        <input type="text" class="form-control" id="prenom" placeholder="Entrez le prénom" name="prenom" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
        <input type="password" class="form-control" id="password" placeholder="Entrez le mot de passe" name="password" required>
      </div>
      <div class="mb-3">
        <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="adresse" placeholder="Entrez l'adresse" name="adresse" required>
      </div>   
    </div>
  </div>

  <!-- Informations professionnelles -->
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

    </div>

    <div class="col-md-6">
    <div class="mb-3">
        <label for="id_salaire" class="form-label">Salaire <span class="text-danger">*</span></label>
        <select class="form-select" id="id_salaire" name="id_salaire" required>
          <option value="1">Salaire fixe employé</option>
          <option value="2">Salaire fixe enseignant</option>
          <option value="3">Salaire Professeur(Horaire)</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="derniere_connexion" class="form-label">Date de prise de poste <span class="text-danger">*</span></label>
        <input type="date" class="form-control" id="derniere_connexion" name="derniere_connexion" required>
      </div>
    </div>
  </div>

  <!-- Bouton d'ajout -->
  <div class="text-center">
    <button id="register-button" type="submit" class="ajout">Ajouter</button>
  </div>
         <!-- Afficher le message d'erreur ici -->
         <?php
        if (isset($_SESSION['error_message'])) {
            echo '<p style="color: red;">' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']); // Effacer le message après l'affichage
        }
        ?>
</form>

<script src="../app/views/auth/js/create.js"></script>
</body>
</html>
