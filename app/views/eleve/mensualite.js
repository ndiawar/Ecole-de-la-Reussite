document.addEventListener('DOMContentLoaded', function () {
    var mensualiteModal = document.getElementById('mensualiteModal');
    
    mensualiteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Le bouton qui a déclenché le modal
        var idEleve = button.getAttribute('data-id');
        
        // Charger les détails de l'élève
        document.getElementById('modalNom').textContent = button.getAttribute('data-nom');
        document.getElementById('modalPrenom').textContent = button.getAttribute('data-prenom');
        document.getElementById('modalMatricule').textContent = button.getAttribute('data-matricule');
        document.getElementById('modalEmail').textContent = button.getAttribute('data-email');
        document.getElementById('modalTelephone').textContent = button.getAttribute('data-telephone');
        
        // Envoyer la requête AJAX pour récupérer les mensualités
        fetch('path/to/routes.php?action=getMensualites', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id_eleve=' + idEleve
        })
        .then(response => response.json())
        .then(data => {
            var mensualiteTableBody = document.getElementById('mensualiteTableBody');
            mensualiteTableBody.innerHTML = ''; // Vider le tableau avant d'ajouter les nouvelles données
            
            // Liste des mois entre Octobre et Juillet
            var mois = ['Octobre', 'Novembre', 'Décembre', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet'];
            
            // Remplir le tableau des mensualités
            mois.forEach(function(moisNom) {
                var estPaye = data.some(paiement => paiement.mois === moisNom);
                var statut = estPaye ? 'Payé' : 'Non payé';
                
                var row = `<tr>
                            <td>${moisNom}</td>
                            <td>${statut}</td>
                           </tr>`;
                mensualiteTableBody.innerHTML += row;
            });
        })
        .catch(error => console.error('Erreur:', error));
    });
});
