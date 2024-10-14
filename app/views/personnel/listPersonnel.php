
<?php
ob_start();  // Démarre la capture du contenu
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste du Personnel</title>

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

<a href="index.php?action=liste_archives" class="btn btn-secondary">Voir les Archivés</a>

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
                    <div id="error-message" style="color: red; display: none;"></div>                  
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
                                    <option value="" disabled selected>-- Sélectionnez un sexe --</option>
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
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" placeholder="Entrez le mot de passe" name="password" required>
                                    <span class="input-group-text">
                                        <i class="fa fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="confirm-password" class="form-label">Confirmez le mot de passe <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm-password" placeholder="Confirmez le mot de passe" required>
                                    <span class="input-group-text">
                                        <i class="fa fa-eye" id="toggleConfirmPassword" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                                <small id="password-error" style="color: red; display: none;">Les mots de passe ne correspondent pas.</small>
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
                                <option value="" disabled selected>-- Sélectionnez un poste --</option>
                                <option value="Directeur">Directeur</option>
                                <option value="Surveillant">Surveillant</option>
                                <option value="Enseignant">Enseignant</option>
                                <option value="Comptable">Comptable</option>
                            </select>
                        </div>


                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="adresse" placeholder="Entrez l'adresse" name="adresse" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_salaire" class="form-label">ID Salaire <span class="text-danger">*</span></label>
                                <select class="form-select" id="id_salaire" name="id_salaire" required>
                                    <option value="" disabled selected>-- Sélectionnez un Salaire --</option>
                                    <option value="1">Salaire fixe employé</option>
                                    <option value="2">Salaire fixe enseignant</option>
                                    <option value="3">Salaire Professeur(Horaire)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="derniere_connexion" class="form-label">Date de prise de poste <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="derniere_connexion" name="derniere_connexion" required 
                                    value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
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
    <div class="table-responsive mb-4"> <!-- Ajout de la classe mb-4 pour un espace en bas -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Matricule</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($personnels as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['prenom']) ?></td>
                        <td><?= htmlspecialchars($p['nom']) ?></td>
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
                                <a href="index.php?action=editPersonnel&id=<?= htmlspecialchars($p['id_personnel']) ?>" class="btn" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Bouton pour afficher la modal -->
                                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#showModal<?= $p['id_personnel'] ?>">
                                    <i class="fas fa-eye" title="Afficher"></i>
                                </button>

                                <!-- Modal pour afficher les détails du personnel -->
                                <div class="modal fade" id="showModal<?= $p['id_personnel'] ?>" tabindex="-1" aria-labelledby="showModalLabel<?= $p['id_personnel'] ?>" aria-hidden="true">
                                    <div class="modal-dialog"> <!-- Utilisation d'un modal large -->
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <!-- Carte d'information du personnel -->
                                                <div class="card" style="width: 100%; border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                                                    <div class="card-body text-center">
                                                        <!-- Avatar (Icône) -->
                                                        <div class="avatar mb-3">
                                                         <img src="https://via.placeholder.com/100" alt="Avatar" class="rounded-circle" style="width: 100px; height: 100px;">
                                                            <span class="badge bg-success position-absolute" style="top: 75px; left: 155px;">ER</span>
                                                        </div>
                                                        <h5 class="card-title"><?= htmlspecialchars($p['prenom']) ?> <?= htmlspecialchars($p['nom']) ?></h5>
                                                        <p class="card-text text-muted"><?= htmlspecialchars($p['role']) ?> à <span class="text-success">École De La Réussite</span></p>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="tel:<?= htmlspecialchars($p['telephone']) ?>" class="btn btn-outline-success me-2">
                                                                <i class="fas fa-phone"></i> <?= htmlspecialchars($p['telephone']) ?>
                                                            </a>
                                                            <a href="mailto:<?= htmlspecialchars($p['email']) ?>" class="btn btn-outline-success">
                                                                <i class="fas fa-envelope"></i> <?= htmlspecialchars($p['email']) ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
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
</div>

<?php else: ?>
    <p>Aucun personnel actif trouvé.</p>
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
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Lien vers Bootstrap JS et jQuery pour que le modal fonctionne -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="/Ecole-de-la-Reussite/app/views/personnel/script.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
<?php
$content = ob_get_clean();  // Récupère le contenu capturé
require __DIR__ . '/../layout.php'; // Inclure le fichier de mise en page
?>