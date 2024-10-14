<?php
ob_start();  // Démarre la capture du contenu
?>


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
    <div class="container">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Inscription récente</h3>
            </div>
            <a href="index.php?action= listePaiementsArchives" class="btn btn-secondary">Voir les Archivés</a>

            <!-- Table des paiements récents -->
            <div class="table-responsive mb-4">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Matricule</th>
                            <th>Téléphone</th>
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
                                <!-- Bouton pour archiver -->
                                <button type="button" class="btn" data-bs-toggle="modal"
                                    data-bs-target="#archiveModal<?= $paiement['id_paiement'] ?>">
                                    <i class="fas fa-archive"></i>
                                </button>

                                <!-- Modal pour l'archivage -->
                                <div class="modal fade" id="archiveModal<?= $paiement['id_paiement'] ?>" tabindex="-1"
                                    aria-labelledby="archiveModalLabel<?= $paiement['id_personnel'] ?>"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="archiveModalLabel<?= $paiement['id_paiement'] ?>">Confirmer
                                                    l'archivage</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Voulez-vous vraiment archiver
                                                <?= htmlspecialchars($paiement['personnel_nom']) ?> (Matricule :
                                                <?= htmlspecialchars($paiement['personnel_matricule']) ?>) ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Annuler</button>
                                                <a href="index.php?action=archiverEleve&id=<?= $paiement['id_paiement'] ?>"
                                                    class="btn btn-primary">Archiver</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bouton pour voir les détails -->
                                <button type="button" class="btn" data-bs-toggle="modal"
                                    data-bs-target="#detailsModal<?= $paiement['id_paiement'] ?>">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <!-- Modal pour les détails -->
                                <div class="modal fade" id="detailsModal<?= $paiement['id_paiement'] ?>" tabindex="-1"
                                    aria-labelledby="detailsModalLabel<?= $paiement['id_personnel'] ?>"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="detailsModalLabel<?= $paiement['id_paiement'] ?>">Détails de
                                                    <?= htmlspecialchars($paiement['personnel_nom']) ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Téléphone :</strong>
                                                    <?= htmlspecialchars($paiement['personnel_telephone']) ?></p>
                                                <p><strong>Matricule :</strong>
                                                    <?= htmlspecialchars($paiement['personnel_matricule']) ?></p>
                                                <p><strong>Email :</strong>
                                                    <?= htmlspecialchars($paiement['personnel_email']) ?></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bouton pour le paiement -->
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                    data-bs-target="#addModal"
                                    data-nom="<?= htmlspecialchars($paiement['personnel_nom']) ?>"
                                    data-prenom="<?= htmlspecialchars($paiement['personnel_prenom']) ?>"
                                    data-matricule="<?= htmlspecialchars($paiement['personnel_matricule']) ?>"
                                    data-email="<?= htmlspecialchars($paiement['personnel_email']) ?>"
                                    data-telephone="<?= htmlspecialchars($paiement['personnel_telephone']) ?>">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <nav aria-label="Pagination">
                    <ul class="pagination">
                        <li class="page-item <?= $currentPage == 1 ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="?action=listEleves&page=<?= max(1, $currentPage - 1) ?>">Précédent</a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $currentPage == $i ? 'active' : '' ?>">
                            <a class="page-link" href="?action=listEleves&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>

                        <li class="page-item <?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="?action=listEleves&page=<?= min($totalPages, $currentPage + 1) ?>">Suivant</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Modal d'ajout de paiement -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Paiement de l'employé</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php include 'formPaiements.php'; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/bootstrap-icons.min.js"></script>
    <script src="../app/views/personnel/scriptpaiement.js">  </script>
</body>

</html>

<?php
$content = ob_get_clean();  // Récupère le contenu capturé
require '../app/views/layoutcompt.php'; // Inclure le fichier de mise en page
?>