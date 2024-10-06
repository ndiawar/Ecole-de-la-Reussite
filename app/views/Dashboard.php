<?php
$title = "Tableau de bord";  // Titre spécifique pour la page
ob_start();  // Démarre la capture du contenu

// Contenu spécifique à cette page
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2>Bienvenue sur le tableau de bord</h2>
            <p>Voici les statistiques actuelles...</p>
        </div>
    </div>

    <!-- Toast pour afficher le message de succès après la connexion -->
    <div class="toast-container position-fixed top-0 end-0 p-3"> <!-- Positionné en haut à droite -->
        <div id="successToast" class="toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="toast-header">
                <strong class="me-auto">Succès</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Connexion réussie !
            </div>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();  // Récupère le contenu capturé
require 'layout.php';  // Inclut le fichier de mise en page
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