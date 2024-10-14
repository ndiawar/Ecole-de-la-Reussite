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
    <link rel="stylesheet" href="../public/css/styleDash.css">
    <style>
        @media (max-width: 767.98px) {
            .sidebar {
                display: none;
            }
            .sidebar.show {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Affichage des messages de session -->
<?php if (isset($_SESSION['success_message'])) : ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success_message']) ?></div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])) : ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error_message']) ?></div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <!-- Sidebar -->
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar">
            <div class="d-flex flex-column align-items-start px-3 pt-2 text-white min-vh-100">
                <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <img src="../public/images/logo.png" alt="Logo École de la Réussite" class="img-fluid" style="max-width: 120%;">
                </a>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-start" id="menu">
                    <li class="nav-item">
                        <a href="?action=Dashboard" class="nav-link w-100 align-middle <?php echo $currentAction === 'Dashboard' ? 'active' : ''; ?>">
                            <i class="fas fa-tachometer-alt text-white"></i> <span class="ms-2">Dashboard</span>
                        </a>
                    </li>

                    <!-- Liens visibles pour le Directeur -->
                    <?php if ($_SESSION['role'] === 'Directeur'): ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link w-100 align-middle">
                                <i class="fas fa-chart-line text-white"></i> <span class="ms-2">Rapport Financière</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link w-100 align-middle">
                                <i class="fas fa-chalkboard-teacher text-white"></i> <span class="ms-2">Enseignants et Cours</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link w-100 align-middle">
                                <i class="fas fa-file-alt text-white"></i> <span class="ms-2">Examens et Résultats</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link w-100 align-middle">
                                <i class="fas fa-calendar-check text-white"></i> <span class="ms-2">Présences</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#submenuInscription" data-bs-toggle="collapse" class="nav-link w-100 align-middle">
                                <i class="fas fa-users text-white"></i> <span class="ms-2">Gestion des Inscriptions</span>
                            </a>
                            <ul class="collapse nav flex-column ms-2" id="submenuInscription" data-bs-parent="#menu">
                                <li class="w-100">
                                    <a href="?action=listeEleves" class="nav-link px-0 <?php echo $currentAction === 'listeEleves' ? 'active' : ''; ?>">Élèves</a>
                                </li>
                                <li>
                                    <a href="?action=listPersonnel" class="nav-link px-0 <?php echo $currentAction === 'listPersonnel' ? 'active' : ''; ?>">Employés</a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- Liens visibles pour le Comptable -->
                    <?php if ($_SESSION['role'] === 'Comptable'): ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link w-100 align-middle">
                                <i class="fas fa-chart-line text-white"></i> <span class="ms-2">Rapport Financière</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Liens visibles pour l'Enseignant -->
                    <?php if ($_SESSION['role'] === 'Enseignant'): ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link w-100 align-middle">
                                <i class="fas fa-chalkboard-teacher text-white"></i> <span class="ms-2">Enseignants et Cours</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link w-100 align-middle">
                                <i class="fas fa-file-alt text-white"></i> <span class="ms-2">Examens et Résultats</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link w-100 align-middle">
                                <i class="fas fa-calendar-check text-white"></i> <span class="ms-2">Présences</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Liens visibles pour le Surveillant -->
                    <?php if ($_SESSION['role'] === 'Surveillant'): ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link w-100 align-middle">
                                <i class="fas fa-calendar-check text-white"></i> <span class="ms-2">Présences</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#submenuInscription" data-bs-toggle="collapse" class="nav-link w-100 align-middle">
                                <i class="fas fa-users text-white"></i> <span class="ms-2">Gestion des Inscriptions</span>
                            </a>
                            <ul class="collapse nav flex-column ms-2" id="submenuInscription" data-bs-parent="#menu">
                                <li class="w-100">
                                    <a href="?action=listeEleves" class="nav-link px-0 <?php echo $currentAction === 'listeEleves' ? 'active' : ''; ?>">Élèves</a>
                                </li>
                                <li>
                                    <a href="?action=listPersonnel" class="nav-link px-0 <?php echo $currentAction === 'listPersonnel' ? 'active' : ''; ?>">Employés</a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
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

</body>
</html>
