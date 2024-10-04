<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Employé</title>
    <link rel="stylesheet" href="create.css">  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<script src="create.js"></script>

<form action="/update_employee.php" method="POST">
  <!-- Titre du formulaire -->
  <h2 class="text-center">Modifier un employé</h2>

  <div><hr><p>Informations personnelles</p></div>

  <div class="row">
    <!-- Informations personnelles -->
    <div class="col-md-6">
      <div class="mb-3">
        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="nom" name="nom" value="Nom existant" required>
      </div>

      <div class="mb-3">
        <label for="date_naissance" class="form-label">Date de Naissance <span class="text-danger">*</span></label>
        <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="1990-01-01" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" class="form-control" id="email" name="email" value="email@example.com" required>
      </div>
    </div>

    <div class="col-md-6">
      <div class="mb-3">
        <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="prenom" name="prenom" value="Prénom existant" required>
      </div>

      <div class="mb-3">
        <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="adresse" name="adresse" value="Adresse existante" required>
      </div>

      <div class="mb-3">
        <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="telephone" name="telephone" value="123456789" required>
      </div>
      
      <div class="mb-3">
        <label for="sexe" class="form-label">Sexe <span class="text-danger">*</span></label>
        <select class="form-select" id="sexe" name="sexe" required>
          <option value="masculin" selected>Masculin</option>
          <option value="feminin">Féminin</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Détails professionnels -->
  <div><hr><p>Informations professionnelles</p></div>
  <div class="row">
    <div class="col-md-6">
      <div class="mb-3">
        <label for="date_embauche" class="form-label">Date de prise de poste <span class="text-danger">*</span></label>
        <input type="date" class="form-control" id="date_embauche" name="date_embauche" value="2020-01-01" required>
      </div>
      
      <div class="mb-3">
        <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
        <select class="form-select" id="statut" name="statut" required>
          <option value="CDI" selected>CDI</option>
          <option value="CDD">CDD</option>
        </select>
      </div>
    </div>
    <div class="mb-3">
        <label for="poste" class="form-label">Poste <span class="text-danger">*</span></label>
        <select class="form-select" id="poste" name="poste" required>
          <option value="Enseignant" selected>Enseignant</option>
          <option value="Surveillant">Surveillant</option>
          <option value="Comptable">Comptable</option>
        </select>
      </div>
  </div>

  <!-- Boutons -->
  <div class="text-center">
    <button type="submit" class="btn btn-primary">Modifier</button>
    <a href="/dashboard.php" class="btn btn-secondary">Retour</a>
  </div>
</form>

</body>
</html>
