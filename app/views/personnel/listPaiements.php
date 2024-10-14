<?php
ob_start();  // Démarre la capture du contenu
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Paiements Récents</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <!-- Ton fichier CSS personnalisé -->
    <link rel="stylesheet" href="/Ecole-de-la-Reussite/app/views/personnel/style.css">
</head>
<body>
<div class="container-fluid px-4">
        <div class="row my-4">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
                <h3>Liste des paiements récent</h3>
                <div class="input-group search-container w-50">
                    <span class="input-group-text bg-transparent border-0">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Rechercher un personnel archivé..."
                        aria-label="Rechercher un personnel">
                </div>
            </div>
        </div>
    <!-- Modal d'ajout de paiement -->
    <div class="modal fade" id="ajoutPaiementModal" tabindex="-1" aria-labelledby="ajoutPaiementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un paiement</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulaire d'ajout -->
                    <form action="index.php?action=ajout_paiement" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="matricule" class="form-label">Matricule</label>
                                <input type="text" class="form-control" id="matricule" name="matricule" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="poste" class="form-label">Telephone</label>
                                <input type="text" class="form-control" id="telephone" name="telephone" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_paiement" class="form-label">Date de paiement</label>
                                <input type="date" class="form-control" id="date_paiement" name="date_paiement" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="montant" class="form-label">Montant</label>
                                <input type="text" class="form-control" id="montant" name="montant" required>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Table des paiements récents -->
    <div class="table-responsive mb-4">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Matricule</th>
                    <th>Telephone</th>
                    <th>Date de paiement</th>
                    <th>Montant</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($paiements as $paiement): ?>
                    <tr>
                        <td><?= htmlspecialchars($paiement['personnel_nom']) ?></td>
                        <td><?= htmlspecialchars($paiement['personnel_email']) ?></td>
                        <td><?= htmlspecialchars($paiement['personnel_matricule']) ?></td>
                        <td><?= htmlspecialchars($paiement['personnel_telephone']) ?></td>
                        <td><?= htmlspecialchars($paiement['date_paiement']) ?></td>
                        <td><?= htmlspecialchars($paiement['montant']) ?></td>
                        <td>
                            <a href="index.php?action=archive_paiement&id=<?= $paiement['id_paiement'] ?>"class="btn">
                                <i class="fas fa-archive" title="Archiver"></i>
                            </a>
                            <a href="index.php?action=voir_paiement&id=<?= $paiement['id_paiement'] ?>"class="btn-info">
                                <i class="fas fa-eye" title="Voir"></i>
                            </a>
                            <a href="index.php?action=ajout_paiement" class="btn btn-success-bordered">
                                <i class="fas fa-plus" title="Ajouter"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
<?php
$content = ob_get_clean();  // Récupère le contenu capturé
require '../app/views/layoutcompt.php';
?>