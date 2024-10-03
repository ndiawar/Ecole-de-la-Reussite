<?php
// Démarrer la session et vérifier si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['personnel_id'])) {
    header('Location: /public/login.php');
    exit;
}

// Debugging: Afficher les informations de session
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Dashboard'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/styleDash.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center">Menu</h4>
    <a href="/dashboard.php" class="active"><i class="fa fa-home"></i> Accueil</a>
    <a href="/students.php"><i class="fa fa-users"></i> Étudiants</a>
    <a href="/teachers.php"><i class="fa fa-chalkboard-teacher"></i> Enseignants</a>
    <a href="/courses.php"><i class="fa fa-book"></i> Cours</a>
</div>

<!-- Header -->
<div class="header">
    <h1><?php echo $title ?? 'Dashboard'; ?></h1>
    <a href="../public/index.php?action=logout" class="btn btn-light">Déconnexion</a>
</div>

<!-- Contenu Principal -->
<div class="content">
    <?php echo $content ?? ''; // Injecte le contenu spécifique à chaque page ?>
</div>

<!-- Footer -->
<!-- <footer>
    <p>&copy; 2024 Votre Application. Tous droits réservés.</p>
</footer> -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
