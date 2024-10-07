<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Élèves</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Liste des Élèves</h2>

    <!-- Table des élèves -->
    <table class="table table-striped table-bordered mt-4">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Niveau</th>
                <th>Tuteur</th>
                <th>Téléphone Tuteur</th>
                <th>Matricule</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eleves as $eleve): ?>
                <tr>
                    <td><?= htmlspecialchars($eleve['nom']) ?></td>
                    <td><?= htmlspecialchars($eleve['prenom']) ?></td>
                    <td><?= htmlspecialchars($eleve['niveau']) ?></td>
                    <td><?= htmlspecialchars($eleve['tuteur']) ?></td>
                    <td><?= htmlspecialchars($eleve['telephone_tuteur']) ?></td>
                    <td><?= htmlspecialchars($eleve['matricule']) ?></td>
                    <td>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#editModal<?= $eleve['matricule'] ?>">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#archiveModal<?= $eleve['matricule'] ?>">
                            <i class="fas fa-archive"></i>
                        </button>
                        <button class="btn btn-info" data-toggle="modal" data-target="#viewModal<?= $eleve['matricule'] ?>">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                
                <!-- Modals pour modifier, archiver et voir -->
                <div class="modal fade" id="editModal<?= $eleve['matricule'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Modifier Élève</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Formulaire de modification (à compléter) -->
                                <p>Formulaire de modification de l'élève ici.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="archiveModal<?= $eleve['matricule'] ?>" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="archiveModalLabel">Archiver Élève</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Voulez-vous vraiment archiver cet élève ?</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="viewModal<?= $eleve['matricule'] ?>" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewModalLabel">Détails de l'Élève</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Détails de l'élève ici.</p>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Scripts JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
