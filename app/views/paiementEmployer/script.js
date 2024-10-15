document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");

    // Écouteur pour le changement de matière
    document.getElementById('matiere').addEventListener('change', function() {
        const tarifHoraireInput = document.getElementById('tarif_horaire');
        tarifHoraireInput.value = this.value; // Remplit le champ tarif horaire avec la valeur sélectionnée
    });

    // Écouteur pour le changement du type de salaire
    document.getElementById('type_salaire').addEventListener('change', function() {
        const montantInput = document.getElementById('montant');
        const matiereSelect = document.getElementById('matiere');
        const tarifHoraireInput = document.getElementById('tarif_horaire');
        const nombreHeuresInput = document.getElementById('nombre_heures');

        if (this.value === 'Horaire') {
            montantInput.value = ''; // Réinitialiser le montant
            matiereSelect.disabled = false; // Activer la matière
            tarifHoraireInput.disabled = false; // Activer le tarif horaire
            nombreHeuresInput.disabled = false; // Activer le nombre d'heures
        } else if (this.value === 'Fixe') {
            montantInput.value = '200000'; // Définir le montant fixe
            matiereSelect.disabled = true; // Désactiver la matière
            tarifHoraireInput.disabled = true; // Désactiver le tarif horaire
            nombreHeuresInput.disabled = true; // Désactiver le nombre d'heures

            // Réinitialiser tarif horaire et nombre d'heures à 0
            tarifHoraireInput.value = '0';
            nombreHeuresInput.value = '0';
        }
    });

    // Écouteurs pour le tarif horaire et le nombre d'heures
    document.getElementById('nombre_heures').addEventListener('input', calculateMontant);
    document.getElementById('tarif_horaire').addEventListener('input', calculateMontant);

    function calculateMontant() {
        const typeSalaire = document.getElementById('type_salaire').value;
        if (typeSalaire === 'Horaire') {
            const tarifHoraire = parseFloat(document.getElementById('tarif_horaire').value) || 0;
            const nombreHeures = parseFloat(document.getElementById('nombre_heures').value) || 0;
            const montantInput = document.getElementById('montant');

            // Calculer le montant
            montantInput.value = (tarifHoraire * nombreHeures).toFixed(2); // Fixer à 2 décimales
        }
    }

    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Empêche la soumission par défaut
        clearErrors(); // Efface les erreurs précédentes

        let isValid = true; 

        // Récupérer et nettoyer les valeurs des champs
        const idPersonnel = sanitizeInput(document.getElementById("id_personnel").value.trim());
        const typeSalaire = document.getElementById("type_salaire").value;
        const montant = document.getElementById("montant").value.trim();
        const datePaiement = document.getElementById("date_paiement").value;
        const moyenPaiement = document.getElementById("moyen_paiement").value;
        const nombreHeures = document.getElementById("nombre_heures").value.trim();
        const mois = sanitizeInput(document.getElementById("mois").value.trim());

        // Validation des champs obligatoires
        if (!idPersonnel || !typeSalaire || !montant || !datePaiement || !moyenPaiement || !mois) {
            showError(document.getElementById("id_personnel"), "Tous les champs marqués d'un * sont obligatoires.");
            isValid = false;
        }

        // Validation des montants
        if (!/^\d+(\.\d{1,2})?$/.test(montant) || parseFloat(montant) <= 0) {
            showError(document.getElementById("montant"), "Le montant doit être un nombre positif.");
            isValid = false;
        }


        if (nombreHeures && (!/^\d+$/.test(nombreHeures) || parseInt(nombreHeures, 10) < 0)) {
            showError(document.getElementById("nombre_heures"), "Le nombre d'heures doit être un nombre positif.");
            isValid = false;
        }

        // Validation des noms et prénoms
        if (!/^[a-zA-ZÀ-ÿ' ]+$/.test(mois)) {
            showError(document.getElementById("mois"), "Le mois ne doit contenir que des lettres.");
            isValid = false;
        }

        if (isValid) {
            // Soumet le formulaire si tout est valide
            form.submit();
        }
    });

    function showError(inputElement, message) {
        // Affiche le message d'erreur
        const errorMessage = document.getElementById("error-message");
        errorMessage.innerHTML += `<div>${message}</div>`;
        // Ajoute la classe d'erreur
        inputElement.classList.add("input-error");
    }

    function clearErrors() {
        // Retire les messages d'erreur
        const errorMessage = document.getElementById("error-message");
        errorMessage.innerHTML = '';
        // Retire la classe d'erreur de tous les champs
        const inputs = document.querySelectorAll("input, select");
        inputs.forEach(input => {
            input.classList.remove("input-error");
        });
    }

    function sanitizeInput(input) {
        return input.replace(/</g, "&lt;").replace(/>/g, "&gt;");
    }

    document.addEventListener('DOMContentLoaded', function () {
        var toastElList = [].slice.call(document.querySelectorAll('.toast'));
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl);
        });
        toastList.forEach(toast => toast.show()); // Affiche tous les toasts sur la page
    });
});
