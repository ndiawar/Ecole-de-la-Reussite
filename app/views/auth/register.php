<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="auth-container">
        <h2>Inscription</h2>
        <form action="index.php?action=register" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select id="role" name="role" class="form-select">
                    <option value="admin">Admin</option>
                    <option value="teacher">Enseignant</option>
                    <option value="student">Étudiant</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">S'inscrire</button>
        </form>
        <div class="text-center mt-3">
            <p>Déjà un compte ? <a href="index.php?action=login">Se connecter</a></p>
        </div>
    </div>
</div>

</body>
</html>
