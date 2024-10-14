<?php
ob_start();  // Démarre la capture du contenu
?>


<?php
//ob_start();  // Démarre la capture du contenu
//include '../app/controllers/PersonnelController.php'; // Chemin vers votre fichier de vue
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,  initial-scale=1.0">
    <title>Liste des Personnels Archivés</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Ecole-de-la-Reussite/app/views/personnel/style.css">
</head>

<body>

    <div class="container-fluid px-4">
        <div class="row my-4">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
                <h3>Liste des Personnels Archivés</h3>
                <div class="input-group search-container w-50">
                    <span class="input-group-text bg-transparent border-0">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Rechercher un personnel archivé..."
                        aria-label="Rechercher un personnel">
                </div>
            </div>
        </div>

        <?php if (!empty($personnelsArchives)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Poste</th>
                        <th>Matricule</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($personnelsArchives as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['prenom']) ?></td>
                        <td><?= htmlspecialchars($p['nom']) ?></td>
                        <td><?= htmlspecialchars($p['email']) ?></td>
                        <td><?= htmlspecialchars($p['telephone']) ?></td>
                        <td><?= htmlspecialchars($p['role']) ?></td>
                        <td><?= htmlspecialchars($p['matricule']) ?></td>
                        <td>
                            <button type="button" class="btn" data-bs-toggle="modal"
                                data-bs-target="#desarchiveModal<?= $p['id_personnel'] ?>">
                                <i class="fas fa-undo" title="Désarchiver"></i>
                            </button>

                            <div class="modal fade" id="desarchiveModal<?= $p['id_personnel'] ?>" tabindex="-1"
                                aria-labelledby="desarchiveModalLabel<?= $p['id_personnel'] ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmer le désarchivage</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Voulez-vous vraiment désarchiver <?= htmlspecialchars($p['prenom']) ?>
                                            <?= htmlspecialchars($p['nom']) ?>
                                            (<?= htmlspecialchars($p['matricule']) ?>) ?
                                        </div>
                                        <div class="modal-footer">

                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Annuler</button>
                                            <a href="index.php?action=restorePersonnel&id=<?= $p['id_personnel'] ?>"
                                                class="btn btn-primary">Désarchiver</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn" data-bs-toggle="modal"
                                data-bs-target="#showModal<?= $p['id_personnel'] ?>">
                                <i class="fas fa-eye" title="Afficher"></i>
                            </button>
                            <div class="modal fade" id="showModal<?= $p['id_personnel'] ?>" tabindex="-1"
                                aria-labelledby="showModalLabel<?= $p['id_personnel'] ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Détails du personnel</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Prénom: <?= htmlspecialchars($p['prenom']) ?></p>
                                            <p>Nom: <?= htmlspecialchars($p['nom']) ?></p>
                                            <p>Email: <?= htmlspecialchars($p['email']) ?></p>
                                            <p>Téléphone: <?= htmlspecialchars($p['telephone']) ?></p>
                                            <p>Poste: <?= htmlspecialchars($p['role']) ?></p>
                                            <p>Matricule: <?= htmlspecialchars($p['matricule']) ?></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a href="index.php?action=listPersonnel" class="btn btn-secondary">Retour</a>

        <?php else: ?>
        <div class="alert alert-warning" role="alert">
            Aucun personnel archivé trouvé.
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$content = ob_get_clean();  // Récupère le contenu capturé
require __DIR__ . '/../layout.php'; // Inclure le fichier de mise en page
?>