<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Paiements - École de la Réussite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #E8F5E9; /* Couleur de fond douce */
        }
        .header {
            background-color: #004D40; /* Couleur principale */
            color: white;
            padding: 20px;
            border-radius: 8px;
        }
        .table-header {
            background-color: #004D40; /* Couleur d'arrière-plan des en-têtes */
            color: white;
        }
        .table th, .table td {
            vertical-align: middle; /* Aligne le texte au centre */
        }
        .highlight {
            background-color: #B2DFDB; /* Couleur d'accentuation */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="header text-center">
            <h2>Liste des Paiements</h2>
            <p>Gestion des paiements pour l'année scolaire</p>
        </div>
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-hover table-striped align-middle">
                <thead class="table-header">
                    <tr>
                        <th>Date Paiement</th>
                        <th>Moyen Paiement</th>
                        <th>Type Paiement</th>
                        <!-- Colonnes supplémentaires affichées selon le type de paiement -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($paiements as $paiement): ?>
                    <tr>
                        <td><?= $paiement['date_paiement']; ?></td>
                        <td><?= $paiement['moyen_paiement']; ?></td>
                        <td><?= $paiement['type_paiement']; ?></td>

                        <!-- Affichage pour les inscriptions -->
                        <?php if ($paiement['type_paiement'] === 'inscription'): ?>
                            <td class="highlight">Date Reçu: <?= $paiement['date_recu']; ?></td>
                            <td class="highlight">Montant Payé (Reçu): <?= $paiement['montant_paye']; ?></td>
                            <td class="highlight">Moyen Paiement (Reçu): <?= $paiement['recu_moyen_paiement']; ?></td>
                            <td class="highlight">Date Inscription: <?= $paiement['date_inscription']; ?></td>
                            <td class="highlight">Année Scolaire: <?= $paiement['annee_scolaire']; ?></td>

                        <!-- Affichage pour les paiements de salaire -->
                        <?php elseif ($paiement['type_paiement'] === 'salaire'): ?>
                            <td class="highlight">Date Bulletin: <?= $paiement['date_bulletin']; ?></td>
                            <td class="highlight">Montant HT: <?= $paiement['montant_HT']; ?></td>
                            <td class="highlight">Montant TTC: <?= $paiement['montant_TTC']; ?></td>
                            <td class="highlight">Nom Personnel: <?= $paiement['personnel_nom']; ?></td>
                            <td class="highlight">Prénom Personnel: <?= $paiement['personnel_prenom']; ?></td>
                            <td class="highlight">Email Personnel: <?= $paiement['personnel_email']; ?></td>

                        <!-- Affichage pour les mensualités -->
                        <?php elseif ($paiement['type_paiement'] === 'mensualite'): ?>
                            <td class="highlight">Mois : <?= $paiement['eleve_mensualite_mois']; ?></td>
                            <td class="highlight">Montant Payé (mensualité): <?= $paiement['eleve_mensualite_montant']; ?></td>
                            <td class="highlight">Prénom élève: <?= $paiement['prenom_eleve']; ?></td>
                            <td class="highlight">Nom élève: <?= $paiement['nom_eleve']; ?></td>
                            <td class="highlight">Date de naissance : <?= $paiement['datenaissance_eleve']; ?></td>

                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
