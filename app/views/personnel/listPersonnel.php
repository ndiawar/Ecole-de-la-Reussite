<?php
ob_start();  // Démarre la capture du contenu
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste du Personnel</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- Ton fichier CSS personnalisé -->
    <link rel="stylesheet" href="/Ecole-de-la-Reussite/app/views/personnel/style.css">
</head>
<body>

<div class="container-fluid px-4"> <!-- Utilisation de container-fluid pour occuper toute la largeur -->
    <!-- Bouton Ajouter qui déclenche le modal -->
<div class="row my-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <button class="btn-add" data-toggle="modal" data-target="#ajoutPersonnelModal">Ajouter</button>
        <div class="input-group search-container w-50">
            <span class="input-group-text bg-transparent border-0">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" class="form-control" placeholder="Rechercher un personnel..." aria-label="Rechercher un personnel">
        </div>
    </div>
</div>

<!-- Modal Large -->
<div class="modal fade" id="ajoutPersonnelModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un employé</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulaire d'ajout -->
                <form action="index.php?action=register" method="POST">                    
                    <!-- Afficher un message d'erreur s'il y en a -->
                    <?php if (!empty($errorMessage)) : ?>
                        <div class="error-message" style="color: red;"><?= htmlspecialchars($errorMessage) ?></div>
                    <?php endif; ?>

                    <div><hr><p>Informations personnelles</p></div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nom" placeholder="Entrez le nom" name="nom" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" placeholder="Entrez l'email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="telephone" placeholder="Entrez le numéro de téléphone" name="telephone" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="sexe" class="form-label">Sexe <span class="text-danger">*</span></label>
                                <select class="form-select" id="sexe" name="sexe" required>
                                    <option value="masculin">Masculin</option>
                                    <option value="feminin">Féminin</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="prenom" placeholder="Entrez le prénom" name="prenom" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" placeholder="Entrez le mot de passe" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="adresse" placeholder="Entrez l'adresse" name="adresse" required>
                            </div>
                        </div>
                    </div>

                    <!-- Informations professionnelles -->
                    <div><hr><p>Informations professionnelles</p></div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Poste <span class="text-danger">*</span></label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="Directeur">Directeur</option>
                                    <option value="Surveillant">Surveillant</option>
                                    <option value="Enseignant">Enseignant</option>
                                    <option value="Comptable">Comptable</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="statut_compte" class="form-label">Statut du compte <span class="text-danger">*</span></label>
                                <select class="form-select" id="statut_compte" name="statut_compte" required>
                                    <option value="actif">Actif</option>
                                    <option value="inactif">Inactif</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_salaire" class="form-label">ID Salaire <span class="text-danger">*</span></label>
                                <select class="form-select" id="id_salaire" name="id_salaire" required>
                                    <option value="1">Salaire fixe employé</option>
                                    <option value="2">Salaire fixe enseignant</option>
                                    <option value="3">Salaire Professeur(Horaire)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="derniere_connexion" class="form-label">Date de prise de poste <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="derniere_connexion" name="derniere_connexion" required>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton d'ajout -->
                    <div class="text-center">
                        <button id="register-button" type="submit" class="ajout">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <?php if (!empty($personnels)): ?>
        <div class="table-responsive"> <!-- Ajout de table-responsive pour la réactivité -->
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
                    <?php foreach ($personnels as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['prenom']) ?></td>
                            <td><?= htmlspecialchars($p['nom']) ?></td>
                            <td><?= htmlspecialchars($p['email']) ?></td>
                            <td><?= htmlspecialchars($p['telephone']) ?></td>
                            <td><?= htmlspecialchars($p['role']) ?></td>
                            <td><?= htmlspecialchars($p['matricule']) ?></td>
                            <td>
                                <!-- Bouton pour archiver -->
                                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#archiveModal<?= $p['id_personnel'] ?>">
                                    <i class="fas fa-archive" title="Archiver"></i>
                                </button>

                                <!-- Modal de confirmation d'archivage -->
                                <div class="modal fade" id="archiveModal<?= $p['id_personnel'] ?>" tabindex="-1" aria-labelledby="archiveModalLabel<?= $p['id_personnel'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmer l'archivage</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Voulez-vous vraiment archiver <?= htmlspecialchars($p['prenom']) ?> <?= htmlspecialchars($p['nom']) ?> ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <a href="index.php?action=archivePersonnel&id=<?= $p['id_personnel'] ?>" class="btn btn-primary">Archiver</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Toast pour le message d'archivage réussi -->
                                    <div class="toast" id="archiveToast" style="position: absolute; top: 20px; right: 20px;" data-bs-autohide="true">
                                        <div class="toast-header">
                                            <strong class="me-auto">Succès</strong>
                                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                        </div>
                                        <div class="toast-body">
                                            <?= isset($_SESSION['archive_success_message']) ? htmlspecialchars($_SESSION['archive_success_message']) : ''; ?>
                                        </div>
                                    </div>

                                </div>


                                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editModal<?= $p['id_personnel'] ?>">
                                    <i class="fas fa-edit" title="Modifier"></i>
                                </button>
                                <div class="modal fade" id="editModal<?= $p['id_personnel'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $p['id_personnel'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                            
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Modifier les informations</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Formulaire de modification du personnel ici</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="button" class="btn btn-warning">Enregistrer</button>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#showModal<?= $p['id_personnel'] ?>">
                                    <i class="fas fa-eye" title="Afficher"></i>
                                </button>


                                
                                <div class="modal fade" id="showModal<?= $p['id_personnel'] ?>" tabindex="-1" aria-labelledby="showModalLabel<?= $p['id_personnel'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Détails du personnel</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Prénom: <?= htmlspecialchars($p['prenom']) ?></p>
                                                <p>Nom: <?= htmlspecialchars($p['nom']) ?></p>
                                                <p>Email: <?= htmlspecialchars($p['email']) ?></p>
                                                <p>Téléphone: <?= htmlspecialchars($p['telephone']) ?></p>
                                                <p>Poste: <?= htmlspecialchars($p['role']) ?></p>
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

        <nav aria-label="Pagination" class="d-flex justify-content-center">
            <ul class="pagination">
                <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?action=listPersonnel&page=<?= max(1, $page - 1) ?>">Précédent</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?action=listPersonnel&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($page == $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?action=listPersonnel&page=<?= min($totalPages, $page + 1) ?>">Suivant</a>
                </li>
            </ul>
        </nav>
    <?php else: ?>
        <p>Aucun personnel trouvé.</p>
    <?php endif; ?>



     <!-- Toast pour afficher le message de succès après la connexion -->
     <div class="toast-container position-fixed top-0 end-0 p-3"> <!-- Positionné en haut à droite -->
        <div id="successToast" class="toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="toast-header">
                <strong class="me-auto">Succès</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Personnel, ajouté avec succés !
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Lien vers Bootstrap JS et jQuery pour que le modal fonctionne -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="/Ecole-de-la-Reussite/app/views/personnel/script.js"></script>
</body>
</html>
<?php
$content = ob_get_clean();  // Récupère le contenu capturé
require __DIR__ . '/../layout.php'; // Inclure le fichier de mise en page
?>

<?php if (isset($_SESSION['success_message'])) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successToast = new bootstrap.Toast(document.getElementById('successToast'));
            successToast.show();  // Affiche le toast immédiatement après le chargement de la page
        });
    </script>
    <?php unset($_SESSION['success_message']); // Supprime le message après affichage ?>
<?php endif; ?>

<?php if (isset($_SESSION['archive_success_message'])) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var archiveToast = new bootstrap.Toast(document.getElementById('archiveToast'));
            archiveToast.show();  // Affiche le toast immédiatement après le chargement de la page
        });
    </script>
    <?php unset($_SESSION['archive_success_message']); // Supprime le message après affichage ?>
<?php endif; ?>
