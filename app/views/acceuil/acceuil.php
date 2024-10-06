<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Inclusion de Chart.js et ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- Intégration de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope&display=swap" rel="stylesheet">
</head>
    <!-- Inclusion du fichier JS séparé -->
    <link rel="stylesheet" href="/Ecole-de-la-Reussite/app/views/acceuil/assets/style.css">
    <script src="/Ecole-de-la-Reussite/app/views/acceuil/assets/graph.js" defer></script>
</head>
<body>

    <!-- Utilisation de la grille Bootstrap (row et col-md-6) -->
    <div class="container">
        <div class="row">
            <!-- Première colonne (col-md-6) pour l'article de blog -->
            <div class="col-md-6">
        
            </div>

            <!-- Deuxième colonne (col-md-6) pour les graphiques -->
            <div class="col-md-6">
                <!-- Container pour Chart.js -->
                <div>
                    <canvas id="chartjs-bar"></canvas>
                </div>

                <!-- Container pour ApexCharts -->
                <!-- <div id="apexcharts-bar" style="height: 350px;"></div> -->
            </div>
        </div>
    </div>

    <!-- Inclusion du JS de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
