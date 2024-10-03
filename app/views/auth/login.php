<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="auth-container">
        <h2>Connexion</h2>
        
        <!-- Afficher les messages d'erreur -->
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        
        <form action="index.php?action=login" method="POST">
            <div class="mb-3">
                <label for="matricule" class="form-label">Matricule</label>
                <input type="text" id="matricule" name="matricule" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        <div class="text-center mt-3">
            <p>Pas encore de compte ? <a href="index.php?action=register">S'inscrire</a></p>
        </div>
    </div>
</div>

</body>
</html>
