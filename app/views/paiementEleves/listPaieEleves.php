<?php
ob_start();  // Démarre la capture du contenu
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Élèves</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <!-- Ton fichier CSS personnalisé -->
    <link rel="stylesheet" href="/Ecole-de-la-Reussite/app/views/eleve/style.css">
</head>
<body>

<div class="container-fluid p-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Rechercher un élève..." aria-label="Rechercher un élève">
            </div>
        </div>
    </div>

    <?php if (!empty($eleves)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Matricule</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Classe</th>
                        <th>Tuteur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eleves as $e):  ?>
                        <tr>
                            <td><?= htmlspecialchars($e['matricule']) ?></td>
                            <td><?= htmlspecialchars($e['eleve_prenom']) ?></td>
                            <td><?= htmlspecialchars($e['eleve_nom']) ?></td>
                            <td><?= htmlspecialchars($e['nom_classe']) ?></td>
                            <td><?= htmlspecialchars($e['tuteur_prenom'] . ' ' . $e['tuteur_nom']) ?></td>
                            <td>
                            <!-- Bouton pour ouvrir le formulaire de paiement dans un modal -->
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#paymentModal" data-id="<?= $e['id_eleve'] ?>">
                                <i class="fas fa-plus"></i> Ajouter Paiement
                            </button>



                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mensualiteModal" data-id="<?= $e['id_eleve'] ?>">
                                    Mensualité
                                </button>
                                <button type="button" class="btn btn-success">Inscrit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

       <!-- Modal pour le paiement -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Formulaire de Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="index.php?action=ajouterPaiement" method="POST">
                    <!-- Type de paiement -->
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="type_paiement" class="form-label">Type de Paiement <span class="text-danger">*</span></label>
                            <select class="form-select" id="type_paiement" name="type_paiement" required onchange="toggleMoisPaiement()">
                                <option value="" disabled selected>-- Sélectionnez un type de paiement --</option>
                                <option value="inscription">Inscription</option>
                                <option value="mensualite">Mensualité</option>
                            </select>
                        </div>

                        <!-- Moyen de paiement -->
                        <div class="col-6">
                            <label for="moyen_paiement" class="form-label">Moyen de Paiement <span class="text-danger">*</span></label>
                            <select class="form-select" id="moyen_paiement" name="moyen_paiement" required>
                                <option value="" disabled selected>-- Sélectionnez un moyen de paiement --</option>
                                <option value="Espèces">Espèces</option>
                                <option value="Orange_Money">Orange Money</option>
                                <option value="Wave">Wave</option>
                            </select>
                        </div>
                    </div>

                    <!-- Montant et Date de Paiement -->
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="montant" class="form-label">Montant <span class="text-danger">*</span></label>
                            <select class="form-control" id="montant" name="montant" required>
                                <option value="" selected disabled>Choisissez un montant</option>
                                <option value="10000">10 000 (Inscription)</option>
                                <option value="13000">13 000 (Mensualité)</option>
                            </select>
                        </div>

                        <div class="col-6">
                            <label for="date_paiement" class="form-label">Date de paiement <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date_paiement" name="date_paiement" required>
                        </div>
                    </div>

                    <!-- Mois de Paiement (seulement pour les mensualités) -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="mois_paiement" class="form-label">Mois de Paiement <span class="text-danger">*</span></label>
                            <select class="form-select" id="mois_paiement" name="mois_paiement" disabled>
                                <option value="" selected disabled>-- Sélectionnez un mois --</option>
                                <option value="Octobre">Octobre</option>
                                <option value="Novembre">Novembre</option>
                                <option value="Décembre">Décembre</option>
                                <option value="Janvier">Janvier</option>
                                <option value="Février">Février</option>
                                <option value="Mars">Mars</option>
                                <option value="Avril">Avril</option>
                                <option value="Mai">Mai</option>
                                <option value="Juin">Juin</option>
                                <option value="Juillet">Juillet</option>
                            </select>
                        </div>
                    </div>

                    <!-- Champ caché pour l'ID de l'élève -->
                    <input type="hidden" name="id_eleve" id="id_eleve">

                    <button type="submit" class="btn btn-primary">Valider le paiement</button>
                </form>
            </div>
        </div>
    </div>
</div>


        <!-- Modal pour afficher les mensualités -->
        <div class="modal fade" id="mensualiteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails de la Mensualité</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Mois</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody id="mensualiteTableBody">
                                <!-- Détails des mensualités ajoutés par JS -->
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
        // Fonction pour activer/désactiver le champ "mois_paiement" selon le type de paiement
            function toggleMoisPaiement() {
                var typePaiement = document.getElementById("type_paiement").value;
                var moisPaiement = document.getElementById("mois_paiement");

                // Activer le champ mois si "mensualite" est sélectionné, sinon le désactiver
                if (typePaiement === "mensualite") {
                    moisPaiement.disabled = false;
                } else {
                    moisPaiement.disabled = true;
                    moisPaiement.value = ""; // Réinitialiser le mois si non nécessaire
                }
            }

            // Associer l'ID de l'élève à chaque bouton de paiement
            document.addEventListener('DOMContentLoaded', function () {
                var paymentModal = document.getElementById('paymentModal');
                paymentModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var idEleve = button.getAttribute('data-id');
                    var idEleveField = document.getElementById('id_eleve');
                    idEleveField.value = idEleve; // Remplir le champ caché avec l'ID de l'élève
                });
            });




        // Code pour charger les mensualités dans le modal Mensualité
        document.addEventListener('DOMContentLoaded', function () {
            var mensualiteModal = document.getElementById('mensualiteModal');
            mensualiteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var idEleve = button.getAttribute('data-id');

                // Charger l'ID de l'élève dans le champ caché du formulaire de paiement
                document.getElementById('id_eleve').value = idEleve;

                fetch('index.php?action=getMensualites', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id_eleve=' + idEleve
                })
                .then(response => response.json())
                .then(data => {
                    var mensualiteTableBody = document.getElementById('mensualiteTableBody');
                    mensualiteTableBody.innerHTML = '';
                    if (data.length === 0) {
                        mensualiteTableBody.innerHTML = '<tr><td colspan="2">Aucune mensualité trouvée</td></tr>';
                    } else {
                        data.forEach(function(item) {
                            var row = `<tr>
                                        <td>${item.mois}</td>
                                        <td>${item.statut === 'payé' ? 'Payé' : 'Non payé'}</td>
                                    </tr>`;
                            mensualiteTableBody.innerHTML += row;
                        });
                    }
                })
                .catch(error => console.error('Erreur:', error));
            });
        });
        </script>

        <!-- Pagination -->
        <nav aria-label="Pagination">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?action=listeEleves&page=<?= max(1, $page - 1) ?>">Précédent</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?action=listeEleves&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($page == $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?action=listeEleves&page=<?= min($totalPages, $page + 1) ?>">Suivant</a>
                </li>
            </ul>
        </nav>
    <?php else: ?>
        <p>Aucun élève trouvé.</p>
    <?php endif; ?>
</div>

<!-- Toast pour afficher le message de succès -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="successToast" class="toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
        <div class="toast-header">
            <strong class="me-auto">Succès</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Élève ajouté avec succès !
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
?>
