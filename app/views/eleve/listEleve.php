
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
                <a href="http://localhost/Ecole-de-la-Reussite/public/index.php?action=ajouterEleve" class="btn-add">Ajouter</a>
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


                                <!-- Bouton pour archiver -->
<button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#archiveModal<?= $e['id_eleve'] ?>">
    <i class="fas fa-archive" title="Archiver"></i>
</button>

<!-- Modal de confirmation d'archivage -->
<div class="modal fade" id="archiveModal<?= $e['id_eleve'] ?>" tabindex="-1" aria-labelledby="archiveModalLabel<?= $e['id_eleve'] ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
               
              
            <div class="modal-body">
                Voulez-vous vraiment archiver <?= htmlspecialchars($e['eleve_prenom']) ?> <?= htmlspecialchars($e['eleve_nom']) ?> ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="index.php?action=archiveEleve&id=<?= $e['id_eleve'] ?>" class="btn btn-primary">Archiver</a>
            </div>
          </div>
        </div>
       </div>
                        <!-- Toast pour le message d'archivage réussi -->
                        <?php if (isset($_SESSION['archive_success_message'])): ?>
    <div class="toast show" id="archiveToast" style="position: absolute; top: 20px; right: 20px;" data-bs-autohide="true">
        <div class="toast-header">
            <strong class="me-auto">Succès</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <?= htmlspecialchars($_SESSION['archive_success_message']); ?>
        </div>
    </div>
    <?php unset($_SESSION['archive_success_message']); // Supprime le message après affichage ?>
<?php endif; ?>

                            
 <!-- Bouton pour modifier -->
 <a href="index.php?action=modifierEleve&id=<?= htmlspecialchars($e['id_eleve']) ?>" class="btn" title="Modifier">
    <i class="fas fa-edit"></i>
</a>



                            <!-- Bouton pour afficher la modal -->
                                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#showModal<?= $e['id_eleve'] ?>">
                                    <i class="fas fa-eye" title="Afficher"></i>
                                </button>

                                <!-- Modal pour afficher les détails de l'élève -->
                                <div class="modal fade" id="showModal<?= $e['id_eleve'] ?>" tabindex="-1" aria-labelledby="showModalLabel<?= $e['id_eleve'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        
                                        <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title" >Informations de l'éléve</h5>
                                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                     </div>
                                            <div class="modal-body">
                                                
                                                <div class="card" style="width: 100%; border: none; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                                                    <div class="card-body text-center">
                                                     
                                                        <h5 class="card-title"><?= htmlspecialchars($e['eleve_prenom']) ?> <?= htmlspecialchars($e['eleve_nom']) ?></h5>
                                                        <p class="card-text text-muted">Matricule: <?= htmlspecialchars($e['matricule']) ?></p>
                                                        <p class="card-text text-muted">Date de Naissance: <?= htmlspecialchars($e['date_naissance']) ?></p>
                                                        <p class="card-text text-muted">Adresse: <?= htmlspecialchars($e['eleve_adresse']) ?></p>
                                                        <p class="card-text text-muted">Email: <?= htmlspecialchars($e['eleve_email']) ?></p>
                                                        <p class="card-text text-muted">Classe: <?= htmlspecialchars($e['nom_classe']) ?></p>
                                                        <p class="card-text text-muted">Sexe: <?= htmlspecialchars($e['eleve_sexe']) ?></p>
                                                        <p class="card-text text-muted">Tuteur: <?= htmlspecialchars($e['tuteur_prenom']). ' '.($e['tuteur_nom']) ?></p>
                                                       
                                                        
                                                        <div class="d-flex justify-content-center">
                                                            <a href="tel:<?= htmlspecialchars($e['tuteur_telephone']) ?>" class="btn btn-outline-success me-2">
                                                                <i class="fas fa-phone"></i> <?= htmlspecialchars($e['tuteur_telephone']) ?>
                                                            </a>
                                                            <a href="mailto:<?= htmlspecialchars($e['tuteur_email']) ?>" class="btn btn-outline-success">
                                                                <i class="fas fa-envelope"></i> <?= htmlspecialchars($e['tuteur_email']) ?>
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
