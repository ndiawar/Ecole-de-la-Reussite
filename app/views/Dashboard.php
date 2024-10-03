<?php
$title = "Tableau de bord";  // Titre spécifique pour la page
ob_start();  // Démarre la capture du contenu

// Contenu spécifique à cette page
?>
<h2>Bienvenue sur le tableau de bord</h2>
<p>Voici les statistiques actuelles...</p>
<?php
$content = ob_get_clean();  // Récupère le contenu capturé
require 'layout.php';  // Inclut le fichier de mise en page
?>
