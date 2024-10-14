
<?php
ob_start();  // Démarre la capture du contenu
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription d'un Élève</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Ton fichier CSS personnalisé -->
    <link rel="stylesheet" href="/Ecole-de-la-Reussite/app/views/eleve/style.css">
</head>
<body>

<div class="container my-5">
    <h2 class="text-center mb-4">Inscription d'un Élève</h2>
    <form action="/Ecole-de-la-Reussite/public/index.php?action=ajouterEleve" method="POST">
        <div id="error-message" style="color: red;"></div>
        
        <!-- Afficher les erreurs générées côté serveur -->
        <?php if (!empty($errors)): ?>
            <ul class="text-danger">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div><hr><p>Informations du Tuteur</p></div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="tuteur_nom" class="form-label">Nom <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="tuteur_nom" name="tuteur_nom" placeholder="Entrez le nom du tuteur" >
                </div>
                <div class="mb-3">
                    <label for="tuteur_prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="tuteur_prenom" name="tuteur_prenom" placeholder="Entrez le prénom du tuteur" >
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="tuteur_telephone" class="form-label">Téléphone <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="tuteur_telephone" name="tuteur_telephone" placeholder="Entrez le numéro de téléphone" >
                </div>
                <div class="mb-3">
                    <label for="tuteur_email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="tuteur_email" name="tuteur_email" placeholder="Entrez l'email du tuteur">
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="tuteur_adresse" class="form-label">Adresse<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="tuteur_adresse" name="tuteur_adresse" placeholder="Entrez l'adresse du tuteur" >
                </div>
            </div>
        </div>

        <div><hr><p>Informations de l'Élève</p></div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="eleve_nom" class="form-label">Nom <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="eleve_nom" name="eleve_nom" placeholder="Entrez le nom de l'élève" >
                </div>
                <div class="mb-3">
                    <label for="eleve_prenom" class="form-label">Prénom <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="eleve_prenom" name="eleve_prenom" placeholder="Entrez le prénom de l'élève" >
                </div>
                <div class="mb-3">
                    <label for="eleve_date_naissance" class="form-label">Date de naissance <span class="text-danger">*</span> </label>
                    <input type="date" class="form-control" id="eleve_date_naissance" name="eleve_date_naissance" >
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="eleve_adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="eleve_adresse" name="eleve_adresse" placeholder="Entrez l'adresse de l'élève" >
                </div>
                <div class="mb-3">
                   <label for="eleve_sexe" class="form-label">Sexe <span class="text-danger">*</span></label>
                    <select class="form-select" id="eleve_sexe" name="eleve_sexe" >
                        <option value="" disabled selected>-- Sélectionnez  --</option>
                        <option value="1">Masculin</option>
                        <option value="2">Feminin</option>
                     </select>
                </div>
                <div class="mb-3">
                    <label for="eleve_email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="eleve_email" name="eleve_email" placeholder="Entrez l'email de l'élève">
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="classe_id" class="form-label">Classe <span class="text-danger">*</span> </label>
                    <select class="form-select" id="classe_id" name="classe_id" >
                        <option value="" disabled selected>-- Sélectionnez une Classe --</option>
                        <option value="1">A-Elem (CI)</option>
                        <option value="2">B-Elem (CP)</option>
                        <option value="3">C-Elem (CE1)</option>
                        <option value="4">D-Elem (CE2)</option>
                        <option value="5">E-Elem (CM1)</option>
                        <option value="6">F-Elem (CM2)</option>
                        <option value="7">A-Second (Sixième)</option>
                        <option value="8">B-Second (Cinquième)</option>
                        <option value="9">C-Second (Quatrième)</option>
                        <option value="10">D-Second (Troisième)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <button type="submit" class="btn w-100 ajout">Enregistrer</button>
            </div>
            <div class="col-md-6">
                <a href="http://localhost/Ecole-de-la-Reussite/public/index.php?action=listeEleves" class="btn btn-danger w-100">Annuler</a>
            </div>
        </div>
    </form>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
<!-- fichier JS personnalisé -->
<script src="/Ecole-de-la-Reussite/app/views/eleve/script.js"></script>
</body>
</html>
<?php
$content = ob_get_clean();  // Récupère le contenu capturé
require __DIR__ . '/../layout.php'; // Inclure le fichier de mise en page
?>
