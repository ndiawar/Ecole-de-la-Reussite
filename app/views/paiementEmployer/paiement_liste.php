<?php
ob_start();  // Démarre la capture du contenu
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Paiements</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <!-- Ton fichier CSS personnalisé -->
    <link rel="stylesheet" href="/Ecole-de-la-Reussite/app/views/paiementEmployer/style.css">
</head>
<body>

<div class="container-fluid p-5 m-auto">
    <div class="row my-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div class="col-md-6 d-flex justify-content-start pb-5">
                <a href="http://localhost/Ecole-de-la-Reussite/public/index.php?action=ajouterPaiement" class="btn ajout">Ajouter un Paiement</a>
            </div>

            <div class="col-md-6 d-flex justify-content-end">
                <form method="GET" action="index.php?action=listPaiements" class="input-group search-container w-100">
                    <select name="mois" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les mois</option>
                        
                        <?php
                            $moisArray = [
                                '01' => 'Janvier',
                                '02' => 'Février',
                                '03' => 'Mars',
                                '04' => 'Avril',
                                '05' => 'Mai',
                                '06' => 'Juin',
                                '07' => 'Juillet',
                                '08' => 'Août',
                                '09' => 'Septembre',
                                '10' => 'Octobre',
                                '11' => 'Novembre',
                                '12' => 'Décembre',
                            ];

                        foreach ($moisArray as $key => $value): ?>
                            <option value="<?= $key ?>" <?= (isset($_GET['mois']) && $_GET['mois'] == $key) ? 'selected' : '' ?>><?= $value ?></option>
                        <?php endforeach; ?>
                    </select>
                    
                    <input type="text" class="form-control" placeholder="Rechercher un paiement..." aria-label="Rechercher un paiement" name="search" value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-outline-secondary" type="submit">
                        <span class="input-group-text h-55">
                            <i class="fas fa-search"></i>
                        </span>
                    </button>
                </form>
            </div>

            </di>
        </div>
    </div>

    <?php if (!empty($paiements)): ?>
<div class="table-responsive mb-4">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Rôle</th>
                <th>Type</th>
                <th>Montant</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($paiements as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nom']) ?></td>
                    <td><?= htmlspecialchars($p['prenom']) ?></td>
                    <td><?= htmlspecialchars($p['role']) ?></td>
                    <td><?= htmlspecialchars($p['type_salaire']) ?></td>
                    <td><?= htmlspecialchars($p['montant']) ?> Fcfa</td>
                    <td><?= htmlspecialchars($p['date_paiement']) ?></td>
                    <td>
                        <!-- Bouton pour afficher les détails du bulletin de salaire -->
                        <button type="button" class="btn w-100" data-bs-toggle="modal" data-bs-target="#detailsModal<?= $p['id_personnel'] ?>">
                            <i class="fas fa-file w-100" title="Détails du bulletin de salaire"></i>
                        </button>
                        <!-- Modal pour afficher les détails -->
                        <div class="modal fade" id="detailsModal<?= $p['id_personnel'] ?>" tabindex="-1" aria-labelledby="detailsModalLabel<?= $p['id_personnel'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailsModalLabel<?= $p['id_personnel'] ?>"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container my-5 p-4 bg-white shadow-sm">
                                            <header class="h-25 headerBilan d-flex justify-content-between align-items-center  border-bottom">
                                                <h1 class="fs-4 p=ml-5 mb-3">Bulletin de salaire | École de la Réussite</h1>
                                                <img src="./images/logo.png" alt="Logo Ecole de la Réussite" class="w-25">
                                            </header>

                                            <div class="row my-4">
                                                <div class="col-md-4 mb-3">
                                                    <div class="p-3 bg-light border rounded">
                                                        <h5 class="text-success">Ecole de la Réussite</h5>
                                                        <p>Adresse : Sacrée cœur<br>Contact : 77 777 77 77</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="p-3 bg-light border rounded">
                                                        <h5 class="text-success">Employé</h5>
                                                        <p>Nom: <?= htmlspecialchars($p['nom']) ?><br>Prénom: <?= htmlspecialchars($p['prenom']) ?><br>Fonction: <?= htmlspecialchars($p['role']) ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="p-3 bg-light border rounded">
                                                        <h5 class="text-success">Reçu de paiement</h5>
                                                        <p>Numéro Reçu: 00001<br>Date de paiement : <?= htmlspecialchars($p['date_paiement']) ?></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <p>Le bulletin de salaire de l'École de la Réussite est un document détaillé fourni aux employés, récapitulant les éléments de leur rémunération pour la période de paie spécifiée.</p>

                                            <table class="table mt-4">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Mensualité</th>
                                                        <th scope="col">Montant</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Type de Salaire</td>
                                                        <td><?= htmlspecialchars($p['type_salaire']) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nombre d’heures</td>
                                                        <td><?= htmlspecialchars($p['nombre_heures'] ?? '00000.00') ?> FCFA</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tarif Horaires</td>
                                                        <td><?= htmlspecialchars($p['tarif_horaire']) ?></td> 
                                                    </tr>
                                                    <tr class="fw-bold">
                                                        <td>Mensualité</td>
                                                        <td><?= htmlspecialchars($p['mois']) ?></td>
                                                    </tr>
                                                    <tr class="text-danger">
                                                        <td>Moyenne de Paiement</td>
                                                        <td><?= htmlspecialchars($p['moyen_paiement']) ?></td>                                                     
                                                    </tr>
                                                    <tr class="fw-bold">
                                                        <td>SALAIRE NET</td>
                                                        <td><?= htmlspecialchars($p['montant']) ?> FCFA</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <div class="d-flex justify-content-between align-items-center mt-5">
                                            <a href="index.php?action=telecharger_bulletin&id=<?= htmlspecialchars($p['id_personnel']) ?>" class="btn btn-success">Télécharger</a>
                                            <img src="/Ecole-de-la-Reussite/app/views/paiementEmployer/images/signature.png" alt="Signature" style="height: 100px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
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

<!-- Pagination -->
<div class="pagination-container">
    <nav aria-label="Pagination" class="d-flex justify-content-center">
        <ul class="pagination">
            <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?action=listPaiements&page=<?= max(1, $page - 1) ?>&search=<?= urlencode($search) ?>&mois=<?= urlencode($_GET['mois'] ?? '') ?>">Précédent</a>
            </li>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?action=listPaiements&page=<?= $i ?>&search=<?= urlencode($search) ?>&mois=<?= urlencode($_GET['mois'] ?? '') ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= ($page == $totalPages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?action=listPaiements&page=<?= min($totalPages, $page + 1) ?>&search=<?= urlencode($search) ?>&mois=<?= urlencode($_GET['mois'] ?? '') ?>">Suivant</a>
            </li>
        </ul>
    </nav>
</div>

<?php else: ?>
    <p>Aucun paiement trouvé.</p>
<?php endif; ?>


    <!-- Toast pour afficher le message de succès -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="successToast" class="toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="toast-header">
                <strong class="me-auto">Succès</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Paiement ajouté avec succès !
            </div>
        </div>
    </div>
</div>
<script src="/Ecole-de-la-Reussite/app/views/paiementEmployer/script.js"></script>
</body>
</html>
<?php
$content = ob_get_clean();  // Récupère le contenu capturé
require __DIR__ . '/../layout.php'; // Inclure le fichier de mise en page
?>
