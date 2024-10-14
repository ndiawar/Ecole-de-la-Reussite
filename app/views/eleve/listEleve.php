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
 </head>
<body>
    <div class="container">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Liste des élèves</h3>
                <div class="input-group search-container w-50">
                    <span class="input-group-text bg-transparent border-0">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Rechercher un personnel..." aria-label="Rechercher un personnel">
                </div>
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
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#paymentModal" 
                                        data-nom="<?= htmlspecialchars($eleve['nom']) ?>" 
                                        data-prenom="<?= htmlspecialchars($eleve['prenom']) ?>" 
                                        data-matricule="<?= htmlspecialchars($eleve['matricule']) ?>" 
                                        data-email="<?= htmlspecialchars($eleve['email']) ?>" 
                                        data-telephone="<?= htmlspecialchars($eleve['telephone']) ?>"
                                        data-id="<?= htmlspecialchars($eleve['id_eleve']) ?>">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </td>
                            <td>
                            <!-- Bouton Mensualité avec modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mensualiteModal" 
                                    data-nom="<?= htmlspecialchars($eleve['nom']) ?>" 
                                    data-prenom="<?= htmlspecialchars($eleve['prenom']) ?>" 
                                    data-matricule="<?= htmlspecialchars($eleve['matricule']) ?>" 
                                    data-email="<?= htmlspecialchars($eleve['email']) ?>" 
                                    data-telephone="<?= htmlspecialchars($eleve['telephone']) ?>"
                                    data-id="<?= htmlspecialchars($eleve['id_eleve']) ?>">
                                Mensualité
                            </button>

                            
                             <!-- Bouton Inscrit, activé ou désactivé selon le statut -->
                            <button type="button" class="btn <?= $eleve['statut_inscription'] == 'Actif' ? 'btn-success' : 'btn-secondary' ?>" 
                                    <?= $eleve['statut_inscription'] == 'Inactif' ? 'disabled' : '' ?>>
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
                                <!-- Section Informations Personnelles -->
                                <fieldset class="mb-4">
                                    <legend>Informations Personnelles</legend>
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label for="nom" class="form-label">Nom<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nom" name="nom" value="" readonly>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label for="prenom" class="form-label">Prénom<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="prenom" name="prenom" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label for="matricule" class="form-label">Matricule<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="matricule" name="matricule" value="" readonly>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label for="telephone" class="form-label">Téléphone<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="telephone" name="telephone" value="" readonly>
                                        </div>
                                    </div>
                                </fieldset>
                                
                                <div class="separator"></div>

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
            <nav aria-label="Pagination">
                <ul class="pagination">
                    <li class="page-item <?= $currentPage == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?action=listEleves&page=<?= max(1, $currentPage - 1) ?>">Précédent</a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $currentPage == $i ? 'active' : '' ?>">
                            <a class="page-link" href="?action=listEleves&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?action=listEleves&page=<?= min($totalPages, $currentPage + 1) ?>">Suivant</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var paymentModal = document.getElementById('paymentModal');
            paymentModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var nom = button.getAttribute('data-nom');
                var prenom = button.getAttribute('data-prenom');
                var matricule = button.getAttribute('data-matricule');
                var email = button.getAttribute('data-email');
                var telephone = button.getAttribute('data-telephone');
                var idEleve = button.getAttribute('data-id');

                // Update the modal's content.
                paymentModal.querySelector('#nom').value = nom;
                paymentModal.querySelector('#prenom').value = prenom;
                paymentModal.querySelector('#matricule').value = matricule;
                paymentModal.querySelector('#email').value = email;
                paymentModal.querySelector('#telephone').value = telephone;
                paymentModal.querySelector('input[name="id_eleve"]').value = idEleve;
            });
        });
    </script>
</body>
</html>
<?php
$content = ob_get_clean();  // Récupère le contenu capturé
require __DIR__ . '/../layout.php'; // Inclure le fichier de mise en page
?>
