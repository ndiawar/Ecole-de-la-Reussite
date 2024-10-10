<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des élèves</title>
    <!-- Inclure Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclure le fichier CSS externe -->
    <link rel="stylesheet" href="/path/to/your/css/styleEleve.css">
</head>
<body>
    <div class="container">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Inscription récente</h3>
                <button class="btn btn-ajouter" data-bs-toggle="modal" data-bs-target="#addModal">Ajouter</button>
            </div>

            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Niveau</th>
                        <th>Tuteur</th>
                        <th>Téléphone</th>
                        <th>Matricule</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eleves as $eleve): ?>
                    <tr>
                        <td><?= htmlspecialchars($eleve['nom']) . " " . htmlspecialchars($eleve['prenom']) ?></td>
                        <td><?= htmlspecialchars($eleve['niveau']) ?></td>
                        <td><?= htmlspecialchars($eleve['tuteur_nom']) . " " . htmlspecialchars($eleve['tuteur_prenom']) ?></td>
                        <td><?= htmlspecialchars($eleve['telephone']) ?></td>
                        <td><?= htmlspecialchars($eleve['matricule']) ?></td>
                        <td>
                            <!-- Bouton pour archiver (boîte) -->
                            <button class="btn btn-outline-info btn-sm btn-action" data-bs-toggle="modal" data-bs-target="#archiveModal-<?= $eleve['id'] ?>">
                                <i class="bi bi-archive"></i>
                            </button>

                            <!-- Bouton pour voir les détails (œil) -->
                            <button class="btn btn-outline-info btn-sm btn-action" data-bs-toggle="modal" data-bs-target="#viewModal-<?= $eleve['id'] ?>">
                                <i class="bi bi-eye"></i>
                            </button>

                            <!-- Bouton pour ajouter (plus) -->
                            <button class="btn btn-outline-success btn-sm btn-action" data-bs-toggle="modal" data-bs-target="#addModal">
                                <i class="bi bi-plus"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal d'archivage -->
                    <div class="modal fade" id="archiveModal-<?= $eleve['id'] ?>" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="archiveModalLabel">Archiver Élève</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Voulez-vous vraiment archiver cet élève ?
                                    <p><?= htmlspecialchars($eleve['nom']) . " " . htmlspecialchars($eleve['prenom']) ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <a href="archiveEleve.php?id=<?= $eleve['id'] ?>" class="btn btn-danger">Archiver</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal pour voir les détails -->
                    <div class="modal fade" id="viewModal-<?= $eleve['id'] ?>" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewModalLabel">Détails de l'élève</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Nom :</strong> <?= htmlspecialchars($eleve['nom']) . " " . htmlspecialchars($eleve['prenom']) ?></p>
                                    <p><strong>Niveau :</strong> <?= htmlspecialchars($eleve['niveau']) ?></p>
                                    <p><strong>Téléphone :</strong> <?= htmlspecialchars($eleve['telephone']) ?></p>
                                    <p><strong>Tuteur :</strong> <?= htmlspecialchars($eleve['tuteur_nom']) . " " . htmlspecialchars($eleve['tuteur_prenom']) ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Pagination">
                <ul class="pagination">
                    <li class="page-item disabled">
                        <a class="page-link">Précédent</a>
                    </li>
                    <li class="page-item active">
                        <a class="page-link">1</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">Suivant</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Modal d'ajout d'élève -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Ajouter un élève</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="addEleve.php" method="POST">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>
                        <div class="mb-3">
                            <label for="niveau" class="form-label">Niveau</label>
                            <input type="text" class="form-control" id="niveau" name="niveau" required>
                        </div>
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" required>
                        </div>
                        <div class="mb-3">
                            <label for="tuteur" class="form-label">Tuteur</label>
                            <input type="text" class="form-control" id="tuteur" name="tuteur" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclure Bootstrap JS et icônes -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/bootstrap-icons.min.js"></script>
</body>
</html>
