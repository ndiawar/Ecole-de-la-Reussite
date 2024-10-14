
var addModal = document.getElementById('addModal');
addModal.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget;
    var nom = button.getAttribute('data-nom');
    var prenom = button.getAttribute('data-prenom');
    var matricule = button.getAttribute('data-matricule');
    var email = button.getAttribute('data-email');
    var telephone = button.getAttribute('data-telephone');

    // Remplir le formulaire avec les informations de l'élève
    var modalTitle = addModal.querySelector('.modal-title');
    modalTitle.textContent = 'Paiement de l\'employé ' + prenom + ' ' + nom;

    // Assigner les valeurs au formulaire
    var inputNom = addModal.querySelector('input[name="nom"]');
    var inputPrenom = addModal.querySelector('input[name="prenom"]');
    var inputMatricule = addModal.querySelector('input[name="matricule"]');
    var inputEmail = addModal.querySelector('input[name="email"]');
    var inputTelephone = addModal.querySelector('input[name="telephone"]');

    inputNom.value = nom;
    inputPrenom.value = prenom;
    inputMatricule.value = matricule;
    inputEmail.value = email;
    inputTelephone.value = telephone;
});
