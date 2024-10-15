<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des élèves</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/path/to/your/css/styleEleve.css">
</head>

<body>
    <div class="container-fluid px-4">
        <div class="row my-4">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
                <h3>Liste des paiements employé</h3>
                <div class="input-group search-container w-50">
                    <span class="input-group-text bg-transparent border-0">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Rechercher un personnel archivé..."
                        aria-label="Rechercher un personnel">
                </div>
            </div>
        </div>
        <!-- Affichage des messages de succès ou d'erreur -->
        <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['message']) ?>
            <?php unset($_SESSION['message']); // Suppression du message après affichage ?>
        </div>
        <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); // Suppression du message après affichage ?>
        </div>
        <?php endif; ?>

        <form action="index.php" method="POST">
            <!-- Section Informations Personnelles -->
            <fieldset class="mb-4">
                <legend>Informations Personnelles</legend>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="nom" class="form-label">Nom<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nom" name="nom"
                            value="<?= htmlspecialchars($eleveInfo['nom'] ?? '') ?>" readonly>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="prenom" class="form-label">Prénom<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="prenom" name="prenom"
                            value="<?= htmlspecialchars($eleveInfo['prenom'] ?? '') ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="matricule" class="form-label">Matricule<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="matricule" name="matricule"
                            value="<?= htmlspecialchars($eleveInfo['matricule'] ?? '') ?>" readonly>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= htmlspecialchars($eleveInfo['email'] ?? '') ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="telephone" class="form-label">Téléphone<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="telephone" name="telephone"
                            value="<?= htmlspecialchars($eleveInfo['telephone'] ?? '') ?>" readonly>
                    </div>
                </div>
            </fieldset>

            <div class="separator"></div>

            <!-- Section Informations de Paiement -->
            <fieldset class="mb-4">
                <legend>Informations de Paiement</legend>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="type_paiement" class="form-label">Type de Paiement <span
                                class="text-danger">*</span></label>
                        <div class="form-group">
                            <!-- Champ visuel qui ressemble à un select -->
                            <input type="text" class="form-control" value="Salaire" readonly
                                style="background-color: #f8f9fa; cursor: not-allowed;">

                            <!-- Champ caché pour envoyer la valeur avec le formulaire -->
                            <input type="hidden" id="type_paiement" name="type_paiement" value="Salaire">
                        </div>

                    </div>
                    <div class="col-6 mb-3">
                        <label for="montant" class="form-label">Montant<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="montant" name="montant" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="moyen_paiement" class="form-label">Moyen de Paiement <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="moyen_paiement" name="moyen_paiement" required>
                            <option value="" disabled selected>-- Sélectionnez un moyen de paiement --</option>
                            <option value="Wave">Wave</option>
                            <option value="Orange Money">Orange Money</option>
                            <option value="Carte">Carte</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="date_paiement" class="form-label">Date de Paiement<span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date_paiement" name="date_paiement" required>
                    </div>
                </div>
            </fieldset>

            <!-- Champ caché pour l'ID de l'élève -->
            <input type="hidden" name="id_eleve" value="<?= htmlspecialchars($eleveInfo['id'] ?? '') ?>">

            <!-- Champ caché pour indiquer l'action (création de paiement) -->
            <input type="hidden" name="action" value="create_payment">

            <!-- Bouton de paiement -->
            <div class="text-center">
                <button id="buttonPayer" type="submit" class="btn btn-primary"
                    onclick="return confirm('Êtes-vous sûr de vouloir procéder au paiement ?')">
                    Payer
                </button>
            </div>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Ecole-de-la-Reussite/app/views/personnel/edit.js"></script>

</body>

</html>