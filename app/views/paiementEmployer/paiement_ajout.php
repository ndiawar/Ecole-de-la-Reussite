<?php
ob_start();  // Démarre la capture du contenu

// Inclure la classe Database une seule fois
require_once './../config/config.php'; // Correct selon la structure

// Connexion à la base de données
$db = (new Database())->getPDO();
$stmt = $db->query('SELECT id_personnel, nom, prenom FROM personnel');
$personnels = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'un Paiement Salarial</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Ecole-de-la-Reussite/app/views/paiementEmployer/style.css">
</head>
<body>

<div class="container my-5">
    <h2 class="text-center mb-4">Ajout d'un Paiement Salarial</h2>
    <form action="/Ecole-de-la-Reussite/public/index.php?action=ajouterPaiement" method="POST">
        <div id="error-message" style="color: red;"></div>

        <!-- Afficher les erreurs générées côté serveur -->
        <?php if (!empty($errors)): ?>
            <ul class="text-danger">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div><hr><p>Informations du Paiement</p></div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="id_personnel" class="form-label">Personnel</label>
                    <select class="form-select" id="id_personnel" name="id_personnel" >
                        <option value="" disabled selected>-- Sélectionnez un personnel --</option>
                        <?php if (!empty($personnels)): ?>
                            <?php foreach ($personnels as $personnel): ?>
                                <option value="<?= htmlspecialchars($personnel['id_personnel']) ?>">
                                    <?= htmlspecialchars($personnel['nom']) ?> <?= htmlspecialchars($personnel['prenom']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Aucun personnel disponible</option>
                        <?php endif; ?>
                    </select>
                </div>
                

                <!-- Autres champs de formulaire -->
                <div class="mb-3">
                    <label for="type_salaire" class="form-label">Type de Salaire</label>
                    <select class="form-select" id="type_salaire" name="type_salaire">
                        <option value="" disabled selected>-- Sélectionnez un type --</option>
                        <option value="Fixe">Fixe</option>
                        <option value="Horaire">Horaire</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="montant" class="form-label">Montant</label>
                    <input type="number" class="form-control" id="montant" name="montant" placeholder="Entrez le montant">
                </div>
                <div class="mb-3">
                    <label for="matiere" class="form-label">Matière</label>
                    <select class="form-select" id="matiere" name="matiere" >
                        <option value="" disabled selected>-- Sélectionnez une matière --</option>
                        <option value="7000">Mathématiques</option>
                        <option value="7500">Physique</option>
                        <option value="6000">Chimie</option>
                        <option value="5500">Français</option>
                        <option value="4000">Anglais</option>
                        <option value="5000">Histoire-Géographie</option>
                        <option value="6000">Sciences de la Vie et de la Terre</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tarif_horaire" class="form-label">Tarif Horaire</label>
                    <input type="number" class="form-control" id="tarif_horaire" name="tarif_horaire" placeholder="Entrez le tarif horaire" step="0.01" >
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="date_paiement" class="form-label">Date de Paiement</label>
                    <input type="date" class="form-control" id="date_paiement" name="date_paiement" 
                        value="<?= date('Y-m-d') ?>" 
                        min="<?= date('Y-m-01') ?>" 
                        max="<?= date('Y-m-t') ?>" >
                </div>
                <div class="mb-3">
                    <label for="moyen_paiement" class="form-label">Moyen de Paiement</label>
                    <select class="form-select" id="moyen_paiement" name="moyen_paiement">
                        <option value="" disabled selected>-- Sélectionnez un moyen --</option>
                        <option value="Virement">Virement</option>
                        <option value="Chèque">Chèque</option>
                        <option value="Wave">Wave</option>
                        <option value="Orange Money">Orange Money</option>
                        <option value="Espèces">Espèces</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                        <label for="nombre_heures" class="form-label">Nombre d'Heures</label>
                        <input type="number" class="form-control" id="nombre_heures" name="nombre_heures" placeholder="Entrez le nombre d'heures" step="1">
                    </div>
                    <div class="mb-3">
                        <label for="mois" class="form-label">Mois</label>
                        <input type="text" class="form-control" id="mois" name="mois" placeholder="Entrez le mois">
                    </div>
                </div>
            </di>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <button type="submit" class="btn w-100 ajout">Enregistrer</button>
            </div>
            <div class="col-md-6">
                <a href="http://localhost/Ecole-de-la-Reussite/public/index.php?action=listPaiements" class="btn btn-danger w-100">Annuler</a>
            </div>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
<script src="/Ecole-de-la-Reussite/app/views/paiementEmployer/script.js"></script>

</body>
</html>

<?php
$content = ob_get_clean();  // Récupère le contenu capturé
require __DIR__ . '/../layout.php'; // Inclure le fichier de mise en page
?>
