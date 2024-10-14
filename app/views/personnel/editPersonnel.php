<?php
ob_start();  // Démarre la capture du contenu
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Édition du Personnel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Ecole-de-la-Reussite/app/views/personnel/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
<div class="container">
    <h2 class="text-center">Édition du Personnel</h2>
    <a href="index.php?action=listPersonnel" class="btn" title="Retour">
        <i class="fas fa-arrow-left fa-2x"></i>
    </a>
    <form action="" method="POST">
        <!-- Section Informations Personnelles -->
        <fieldset class="mb-4">
            <legend>Informations Personnelles</legend>
            <div class="row">
                <div class="col-6 mb-3">
                    <label for="nom" class="form-label">Nom<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($personnelInfo['nom'] ?? '') ?>" >
                </div>
                <div class="col-6 mb-3">
                    <label for="prenom" class="form-label">Prénom<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($personnelInfo['prenom'] ?? '') ?>" >
                </div>
            </div>
            <div class="row">
                <div class="col-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($personnelInfo['email'] ?? '') ?>" >
                </div>
                <div class="col-6 mb-3">
                    <label for="telephone" class="form-label">Téléphone<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($personnelInfo['telephone'] ?? '') ?>" >
                </div>
            </div>
            <div class="row">
                <div class="col-6 mb-3">
                    <label for="sexe" class="form-label">Sexe<span class="text-danger">*</span></label>
                    <select class="form-select" id="sexe" name="sexe" >
                        <option value="masculin" <?= (isset($personnelInfo['sexe']) && $personnelInfo['sexe'] === 'masculin') ? 'selected' : '' ?>>Masculin</option>
                        <option value="féminin" <?= (isset($personnelInfo['sexe']) && $personnelInfo['sexe'] === 'féminin') ? 'selected' : '' ?>>Féminin</option>
                    </select>
                </div>
            </div>
        </fieldset>
        
        <div class="separator"></div>

        <!-- Section Informations Professionnelles -->
        <fieldset class="mb-4">
            <legend>Informations Professionnelles</legend>
            <div class="row">
                <div class="col-6 mb-3">
                    <label for="role" class="form-label">Poste <span class="text-danger">*</span></label>
                    <select class="form-select" id="role" name="role" >
                        <option value="" disabled selected>-- Sélectionnez un poste --</option>
                        <option value="Directeur" <?= (isset($personnelInfo['role']) && $personnelInfo['role'] === 'Directeur') ? 'selected' : '' ?>>Directeur</option>
                        <option value="Surveillant" <?= (isset($personnelInfo['role']) && $personnelInfo['role'] === 'Surveillant') ? 'selected' : '' ?>>Surveillant</option>
                        <option value="Enseignant" <?= (isset($personnelInfo['role']) && $personnelInfo['role'] === 'Enseignant') ? 'selected' : '' ?>>Enseignant</option>
                        <option value="Comptable" <?= (isset($personnelInfo['role']) && $personnelInfo['role'] === 'Comptable') ? 'selected' : '' ?>>Comptable</option>
                    </select>
                </div>
                <div class="col-6 mb-3">
                    <label for="id_salaire" class="form-label">ID Salaire <span class="text-danger">*</span></label>
                    <select class="form-select" id="id_salaire" name="id_salaire" >
                        <option value="" disabled selected>-- Sélectionnez un Salaire --</option>
                        <option value="1" <?= (isset($personnelInfo['id_salaire']) && $personnelInfo['id_salaire'] == 1) ? 'selected' : '' ?>>Salaire fixe employé</option>
                        <option value="2" <?= (isset($personnelInfo['id_salaire']) && $personnelInfo['id_salaire'] == 2) ? 'selected' : '' ?>>Salaire fixe enseignant</option>
                        <option value="3" <?= (isset($personnelInfo['id_salaire']) && $personnelInfo['id_salaire'] == 3) ? 'selected' : '' ?>>Salaire Professeur (Horaire)</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-6 mb-3">
                    <label for="derniere_connexion" class="form-label">Date de prise de poste<span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control" 
                        id="derniere_connexion" 
                        name="derniere_connexion" 
                        value="<?php echo date('Y-m-d\TH:i'); ?>" 
                        min="<?php echo date('Y-m-d\TH:i'); ?>" 
                        max="<?php echo date('Y-m-d\TH:i'); ?>"
                    >
                </div>

            </div>
        </fieldset>

        <!-- Bouton d'ajout -->
        <div class="text-center">
            <button id="buttonAJ"  type="submit" class="btn">Modifier le personnel</button>
        </div>

    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="/Ecole-de-la-Reussite/app/views/personnel/edit.js"></script>

</body>
</html>
<?php
$content = ob_get_clean();  // Récupère le contenu capturé
require __DIR__ . '/../layout.php'; // Inclure le fichier de mise en page
?>
