<?php
// Démarrer la session et vérifier si l'utilisateur est connecté
// session_start();
// if (!isset($_SESSION['personnel_id'])) {
//     header('Location: /public/login.php');
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Dashboard'; ?></title>
    
    <!-- Lien Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Lien FontAwesome pour les icônes -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Lien vers le fichier CSS externe -->
    <link rel="stylesheet" href="../public/css/styleDash.css"> <!-- Le fichier CSS externe -->

</head>
<body>

<!-- Sidebar -->
<nav class="sidebar">
    <div class="text-center my-4">
        <img src="../public/images/logo.png" alt="Logo École de la Réussite" class="img-fluid" style="max-width: 60%;">
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="?action=Dashboard">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="#">Inscription</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="#">Paiements Élèves</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="#">Paiements Employés</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="#">Bulletin de Salaire</a>
        </li>


    
</nav>

<!-- Header -->
<header class="header">
    <div class="d-flex justify-content-end align-items-center w-100">
        <button class="btn btn-light">
            <i class="fas fa-bell"></i>
        </button>
        <a href="/Ecole-de-la-Reussite/public/index.php?action=logout" class="btn btn-danger" style="background-color: #004D40; border-color: #004D40;">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>
    </div>
</header>

<!-- Contenu Principal -->
<div class="content">
    <?php echo $content ?? ''; // Injecte le contenu spécifique à chaque page ?>
</div>

<!-- Scripts Bootstrap et autres -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="dashboard.js"></script>

</body>
</html>
