
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

<div class="container-fluid p-5 m-auto">
    <div class="row my-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div class="col-md-6 d-flex justify-content-between align-items-center">
                <a href="" class="btn-add">Ajouter</a>
            </div>
            <div class="col-md-6 d-flex justify-content-between align-items-center">
                <div class="input-group search-container w-50">
                    <span class="input-group search-container w-50">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Rechercher un eleve..." aria-label="Rechercher un personnel">
                </div>
            </div>
        </div>
    </div>
    

    
    <?php if (!empty($eleves)): ?>
        <div class="table-responsive mb-4">
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
                    <?php foreach ($eleves as $e): ?>
                        <tr>
                            <td><?= htmlspecialchars($e['matricule']) ?></td>
                            <td><?= htmlspecialchars($e['eleve_prenom']) ?></td>
                            <td><?= htmlspecialchars($e['eleve_nom']) ?></td>
                            <td><?= htmlspecialchars($e['nom_classe']) ?></td>
                            <td><?= htmlspecialchars($e['tuteur_prenom'] . ' ' . $e['tuteur_nom']) ?></td>
                            
                            
                            
                            <td>
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </td>
                            <td>
                            <!-- Bouton Mensualité avec modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mensualiteModal">
                                Mensualité
                            </button>

                            
                             <!-- Bouton Inscrit, activé ou désactivé selon le statut -->
                            <button type="button" class="btn btn-success">
                                Inscrit
                            </button>
                             </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
 
            <!-- Modal pour le paiement -->
            <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">Formulaire de Paiement</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="index.php?action=create_payment" method="POST">

                                <!-- Section Informations de Paiement -->
                                <fieldset class="mb-4">
                                    <legend>Informations de Paiement</legend>
                                    <div class="row">
                                    <div class="col-6 mb-3">
                                        <label for="type_paiement" class="form-label">Type de Paiement <span class="text-danger">*</span></label>
                                        <select class="form-select" id="type_paiement" name="type_paiement" required onchange="toggleMoisPaiement()">
                                            <option value="" disabled selected>-- Sélectionnez un type de paiement --</option>
                                            <option value="inscription">Inscription</option>
                                            <option value="mensualite">Mensualité</option>
                                        </select>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="moyen_paiement" class="form-label">Moyen de Paiement <span class="text-danger">*</span></label>
                                        <select class="form-select" id="moyen_paiement" name="moyen_paiement" required>
                                            <option value="" disabled selected>-- Sélectionnez un moyen de paiement --</option>
                                            <option value="Espèces">Espèces</option>
                                            <option value="Orange_Money">Orange_Money</option>
                                            <option value="Wave">Wave</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label for="montant" class="form-label">Montant <span class="text-danger">*</span></label>
                                        <select class="form-control" id="montant" name="montant" required>
                                            <option value="" selected disabled>Choisissez un montant</option>
                                            <option value="10000">10000 (Inscription)</option>
                                            <option value="13000">13000 (Mensualité)</option>
                                        </select>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label for="date_paiement" class="form-label">Date de paiement <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="date_paiement" name="date_paiement" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6 mb-3">
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

                                <script>
                                function toggleMoisPaiement() {
                                    var typePaiement = document.getElementById("type_paiement").value;
                                    var moisPaiement = document.getElementById("mois_paiement");

                                    if (typePaiement === "mensualite") {
                                        moisPaiement.disabled = false; // Activer le champ "Mois de paiement"
                                    } else {
                                        moisPaiement.disabled = true; // Désactiver le champ "Mois de paiement"
                                        moisPaiement.value = ""; // Réinitialiser la sélection
                                    }
                                }
                                </script>

                                     
                                </fieldset>

                                <input type="hidden" name="id_eleve" value="">

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
                <h5 class="modal-title">Détails de la mensualité</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Détails de l'élève -->
                <p><strong>Nom:</strong> <span id="modalNom"></span></p>
                <p><strong>Prénom:</strong> <span id="modalPrenom"></span></p>
                <p><strong>Matricule:</strong> <span id="modalMatricule"></span></p>
                <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                <p><strong>Téléphone:</strong> <span id="modalTelephone"></span></p>

                <!-- Tableau pour afficher les mois et leur statut -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mois</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody id="mensualiteTableBody">
                        <!-- Les mois payés ou non payés seront ajoutés ici par JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Script JavaScript pour charger les mensualités -->
<script>
   document.addEventListener('DOMContentLoaded', function () {
        var mensualiteModal = document.getElementById('mensualiteModal');

        mensualiteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var idEleve = button.getAttribute('data-id');

            // Charger les détails de l'élève
            document.getElementById('modalNom').textContent = button.getAttribute('data-nom');
            document.getElementById('modalPrenom').textContent = button.getAttribute('data-prenom');
            document.getElementById('modalMatricule').textContent = button.getAttribute('data-matricule');
            document.getElementById('modalEmail').textContent = button.getAttribute('data-email');
            document.getElementById('modalTelephone').textContent = button.getAttribute('data-telephone');

            // Envoyer la requête AJAX pour récupérer les mensualités
            fetch('index.php?action=getMensualites', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id_eleve=' + idEleve
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau : ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                var mensualiteTableBody = document.getElementById('mensualiteTableBody');
                mensualiteTableBody.innerHTML = ''; // Vider le tableau avant d'ajouter les nouvelles données
                
                if (data.length === 0) {
                    mensualiteTableBody.innerHTML = '<tr><td colspan="2">Aucune mensualité trouvée</td></tr>';
                    return;
                }

                // Remplir le tableau des mensualités
                data.forEach(function(item) {
                    var row = `<tr>
                                <td>${item.mois}</td>
                                <td>
                                    <button class="btn ${item.statut === 'payé' ? 'btn-success' : 'btn-secondary'}" 
                                            ${item.statut === 'non payé' ? 'disabled' : ''}>
                                        ${item.statut === 'payé' ? 'Payé' : 'Non payé'}
                                    </button>
                                </td>
                              </tr>`;
                    mensualiteTableBody.innerHTML += row;
                });
            })
            .catch(error => console.error('Erreur:', error));
        });
    });
</script>

        <!-- Pagination -->
        <div class="pagination-container">
            <nav aria-label="Pagination" class="d-flex justify-content-center">
                <ul class="pagination">
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
        </div>

    <?php else: ?>
        <p>Aucun élève trouvé.</p>
    <?php endif; ?>

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
</div>
<script scr="src=/Ecole-de-la-Reussite/app/views/eleve/script.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$content = ob_get_clean();  // Récupère le contenu capturé
require __DIR__ . '/../layout.php'; // Inclure le fichier de mise en page
?>
