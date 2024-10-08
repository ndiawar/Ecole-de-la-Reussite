<?php
// Exemple pour définir l'action actuelle
$currentAction = $_GET['action'] ?? 'Dashboard';
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
    <style>
        /* CSS pour gérer la visibilité de la sidebar sur mobile */
        @media (max-width: 767.98px) {
            .sidebar {
                display: none; /* Masque par défaut sur mobile */
            }
            .sidebar.show {
                display: block; /* Affiche lorsque le menu est ouvert */
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <!-- Sidebar -->
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <img src="../public/images/logo.png" alt="Logo École de la Réussite" class="img-fluid" style="max-width: 120%;">
                </a>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                    <li class="nav-item">
                        <a href="?action=Dashboard" class="nav-link px-0 align-middle <?php echo $currentAction === 'Dashboard' ? 'active' : ''; ?>">
                            <i id="icon_menu" class="fas fa-tachometer-alt"></i> <span class="ms-1 d-none d-sm-inline">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link px-0 align-middle">
                            <i id="icon_menu" class="fas fa-chart-line"></i> <span class="ms-1 d-none d-sm-inline">Rapport Financière</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link px-0 align-middle">
                            <i id="icon_menu" class="fas fa-chalkboard-teacher"></i> <span class="ms-1 d-none d-sm-inline">Enseignants et Cours</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link px-0 align-middle">
                            <i id="icon_menu" class="fas fa-file-alt"></i> <span class="ms-1 d-none d-sm-inline">Examens et Résultats</span>
                        </a>
                    </li>
                    <!-- Menu déroulant pour Gestion des Inscriptions -->
                    <li class="nav-item">
                        <a href="#submenuInscription" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                            <i id="icon_menu" class="fas fa-users"></i> <span class="ms-1 d-none d-sm-inline">Gestion des Inscriptions</span>
                        </a>
                        <ul class="collapse nav flex-column ms-1" id="submenuInscription" data-bs-parent="#menu">
                            <li class="w-100">
                                <a href="#" class="nav-link px-0">Élèves</a>
                            </li>
                            <li>
                                <a href="?action=listPersonnel" class="nav-link px-0 <?php echo $currentAction === 'listPersonnel' ? 'active' : ''; ?>">Employés</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link px-0 align-middle">
                            <i id="icon_menu" class="fas fa-calendar-check"></i> <span class="ms-1 d-none d-sm-inline">Présences</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link px-0 align-middle">
                            <i id="icon_menu" class="fas fa-file-invoice"></i> <span class="ms-1 d-none d-sm-inline">Rapports d'activités</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
<!-- Contenu principal -->
<div class="col py-3">
            <!-- Header -->
            <header class="d-flex justify-content-end align-items-center">
                <button class="btn btn-light me-2">
                    <i class="fas fa-bell"></i>
                </button>
                <a href="/Ecole-de-la-Reussite/public/index.php?action=logout" class="btn btn-danger" style="background-color: #004D40; border-color: #004D40;">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </header>

            <!-- Section de contenu -->
            <div class="content">
                <?php echo $content ?? ''; // Injecte le contenu spécifique à chaque page ?>
            </div>
        </div>
    </div>
</div>
<!-- Scripts Bootstrap et autres -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script>
    document.getElementById('menuToggle').addEventListener('click', function() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('show'); // Ajoute ou enlève la classe show
    });
</script>
</body>
</html>
