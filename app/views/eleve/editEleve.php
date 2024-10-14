
<?php
ob_start();  // Démarre la capture du contenu
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification de l'Élève</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Ecole-de-la-Reussite/app/views/eleve/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="container my-5">
    <h2 class="text-center mb-4">Modification de l'Élève</h2>
    <div id="error-message" class="text-danger"></div> <!-- Message d'erreur -->
    
    <form action="" method="POST">
        <input type="hidden" name= "id_eleve" value= "<?= htmlspecialchars($eleve['id_eleve'] ?? '') ?>">

        <!-- Informations du Tuteur -->
        <div><hr><p>Informations du Tuteur</p></div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="tuteur_nom" class="form-label">Nom du Tuteur </label>
                    <input type="text" class="form-control" id="tuteur_nom" name="tuteur_nom" value="<?= htmlspecialchars($eleve['tuteur_nom'] ?? '') ?>" >
                    <div class="error-message" style="color: red; visibility: hidden;"></div>
                </div>
                <div class="mb-3">
                    <label for="tuteur_prenom" class="form-label">Prénom du Tuteur </label>
                    <input type="text" class="form-control" id="tuteur_prenom" name="tuteur_prenom" value="<?= htmlspecialchars($eleve['tuteur_prenom'] ?? '') ?>" >
                    <div class="error-message" style="color: red; visibility: hidden;"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="tuteur_telephone" class="form-label">Téléphone du Tuteur </label>
                    <input type="text" class="form-control" id="tuteur_telephone" name="tuteur_telephone" value="<?= htmlspecialchars($eleve['tuteur_telephone'] ?? '') ?>" >
                    <div class="error-message" style="color: red; visibility: hidden;"></div>
                </div>
                <div class="mb-3">
                    <label for="tuteur_email" class="form-label">Email du Tuteur</label>
                    <input type="email" class="form-control" id="tuteur_email" name="tuteur_email" value="<?= htmlspecialchars($eleve['tuteur_email'] ?? '') ?>" >
                    <div class="error-message" style="color: red; visibility: hidden;"></div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="tuteur_adresse" class="form-label">Adresse du Tuteur </label>
                    <input type="text" class="form-control" id="tuteur_adresse" name="tuteur_adresse" value="<?= htmlspecialchars($eleve['tuteur_adresse'] ?? '') ?>" >
                    <div class="error-message" style="color: red; visibility: hidden;"></div>
                </div>
            </div>
        </div>

        <!-- Informations de l'Élève -->
        <div><hr><p>Informations de l'Élève</p></div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="eleve_nom" class="form-label">Nom de l'Élève </label>
                    <input type="text" class="form-control" id="eleve_nom" name="eleve_nom" value="<?= htmlspecialchars($eleve['eleve_nom'] ?? '') ?>" >
                    <div class="error-message" style="color: red; visibility: hidden;"></div>
                </div>
                <div class="mb-3">
                    <label for="eleve_prenom" class="form-label">Prénom de l'Élève </label>
                    <input type="text" class="form-control" id="eleve_prenom" name="eleve_prenom" value="<?= htmlspecialchars($eleve['eleve_prenom'] ?? '') ?>" >
                    <div class="error-message" style="color: red; visibility: hidden;"></div>
                </div>
                <div class="mb-3">
                    <label for="eleve_date_naissance" class="form-label">Date de Naissance </label>
                    <input type="date" class="form-control" id="eleve_date_naissance" name="eleve_date_naissance" value="<?= htmlspecialchars($eleve['date_naissance'] ?? '') ?>" >
                    <div class="error-message" style="color: red; visibility: hidden;"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="eleve_adresse" class="form-label">Adresse de l'Élève </label>
                    <input type="text" class="form-control" id="eleve_adresse" name="eleve_adresse" value="<?= htmlspecialchars($eleve['eleve_adresse'] ?? '') ?>" >
                    <div class="error-message" style="color: red; visibility: hidden;"></div>
                </div>
                <div class="mb-3">
                    <label for="eleve_sexe" class="form-label">Sexe</label>
                    <select class="form-select" id="eleve_sexe" name="eleve_sexe" >
                        <option value="" disabled>-- Sélectionnez le Sexe --</option>
                        <option value="Masculin" <?= (isset($eleve['eleve_sexe']) && $eleve['eleve_sexe'] == 'Masculin' ? 'selected' : '') ?>>Masculin</option>
                        <option value="Feminin" <?= (isset($eleve['eleve_sexe']) && $eleve['eleve_sexe'] == 'Feminin' ? 'selected' : '') ?>>Féminin</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="eleve_email" class="form-label">Email de l'Élève</label>
                    <input type="email" class="form-control" id="eleve_email" name="eleve_email" value="<?= htmlspecialchars($eleve['eleve_email'] ?? '') ?>" >
                    <div class="error-message" style="color: red; visibility: hidden;"></div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                <label for="classe_id" class="form-label">Classe</label>
                    <select class="form-select" id="classe_id" name="classe_id" >
                        <option value="" disabled>-- Sélectionnez une Classe --</option>
                        <option value="1" <?= (isset($eleve['classe_id']) && $eleve['classe_id'] == 1 ? 'selected' : '') ?>>A-Elem (CI)</option>
                        <option value="2" <?= (isset($eleve['classe_id']) && $eleve['classe_id'] == 2 ? 'selected' : '') ?>>B-Elem (CP)</option>
                        <option value="3" <?= (isset($eleve['classe_id']) && $eleve['classe_id'] == 3 ? 'selected' : '') ?>>C-Elem (CE1)</option>
                        <option value="4" <?= (isset($eleve['classe_id']) && $eleve['classe_id'] == 4 ? 'selected' : '') ?>>D-Elem (CE2)</option>
                        <option value="5" <?= (isset($eleve['classe_id']) && $eleve['classe_id'] == 5 ? 'selected' : '') ?>>E-Elem (CM1)</option>
                        <option value="6" <?= (isset($eleve['classe_id']) && $eleve['classe_id'] == 6 ? 'selected' : '') ?>>F-Elem (CM2)</option>
                        <option value="7" <?= (isset($eleve['classe_id']) && $eleve['classe_id'] == 7 ? 'selected' : '') ?>>A-Second (Sixième)</option>
                        <option value="8" <?= (isset($eleve['classe_id']) && $eleve['classe_id'] == 8 ? 'selected' : '') ?>>B-Second (Cinquième)</option>
                        <option value="9" <?= (isset($eleve['classe_id']) && $eleve['classe_id'] == 9 ? 'selected' : '') ?>>C-Second (Quatrième)</option>
                        <option value="10" <?= (isset($eleve['classe_id']) && $eleve['classe_id'] == 10 ? 'selected' : '') ?>>D-Second (Troisième)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="row mt-4">
            <div class="col-md-6">
                <button type="submit" name="modifierEleve" class="btn ajout">Modifier</button>
            </div>
            <div class="col-md-6">
                <a href="http://localhost/Ecole-de-la-Reussite/public/index.php?action=listeEleves" class="btn btn-danger">Annuler</a>
            </div>
        </div>
    </form>
</div>

<!-- JS Scripts -->
<script src="/Ecole-de-la-Reussite/app/views/eleve/script.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

</body>
</html>
<?php
$content = ob_get_clean(); // Récupère le contenu capturé
require __DIR__ . '/../layout.php'; // Inclure le fichier de mise en page
?>
