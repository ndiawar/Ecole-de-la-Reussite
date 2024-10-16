<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin de Salaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Ecole-de-la-Reussite/app/views/paiementEmployer/style.css">
</head>
<body class="bg-light">
    <div class="container my-5 p-4 bg-white shadow-sm">
        <header class=" h-25 headerBilan d-flex justify-content-between align-items-center pb-3 border-bottom">
            <h1 class="fs-4">Bulletin de salaire | École de la Réussite</h1>
            <img src="./images/logo.png" alt="Logo Ecole de la Réussite" class="w-25">
        </header>

        <div class="row my-4">
            <div class="col-md-4 mb-3">
                <div class="p-3 bg-light border rounded">
                    <h5 class="text-success">Ecole de la Réussite</h5>
                    <p>Adresse : Sacrée cœur<br>Contact : 77 777 77 77</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="p-3 bg-light border rounded">
                    <h5 class="text-success">Employé</h5>
                    <p>Nom: Diop<br>Prénom: Bamba<br>Fonction: Professeur</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="p-3 bg-light border rounded">
                    <h5 class="text-success">Reçu de paiement</h5>
                    <p>Numéro Reçu: 00001<br>Date de paiement : 01/08/2024</p>
                </div>
            </div>
        </div>

        <p>Le bulletin de salaire de l'École de la Réussite est un document détaillé fourni aux employés, récapitulant les éléments de leur rémunération pour la période de paie spécifiée.</p>

        <table class="table mt-4">
            <thead>
                <tr>
                    <th scope="col">Mensualité</th>
                    <th scope="col">Montant</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Nombre d’heures</td>
                    <td>00000.00 FCFA</td>
                </tr>
                <tr>
                    <td>Salaire brut</td>
                    <td>00000.00 FCFA</td>
                </tr>
                <tr>
                    <td>Prime de performance</td>
                    <td>00000.00 FCFA</td>
                </tr>
                <tr class="fw-bold">
                    <td>TOTAL SALAIRE BRUT</td>
                    <td>00000.00 FCFA</td>
                </tr>
                <tr class="text-danger">
                    <td>Déduction (impôts, sociales)</td>
                    <td>- 00000.00 FCFA</td>
                </tr>
                <tr class="fw-bold">
                    <td>SALAIRE NET</td>
                    <td>00000.00 FCFA</td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-5">
            <button class="btn btn-success">Télécharger</button>
            <img src="./images/signature.png" alt="Signature" style="height: 100px;">
        </div>
    </div>
</body>
</html>
