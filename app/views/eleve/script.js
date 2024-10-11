document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");

    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Empêche la soumission par défaut
        clearErrors(); // Efface les erreurs précédentes

        let isValid = true;

        // Récupérer et nettoyer les valeurs des champs
        const tuteurNom = sanitizeInput(document.getElementById("tuteur_nom").value.trim());
        const tuteurPrenom = sanitizeInput(document.getElementById("tuteur_prenom").value.trim());
        const tuteurEmail = document.getElementById("tuteur_email").value.trim();
        const tuteurTelephone = document.getElementById("tuteur_telephone").value.trim();
        const tuteurAdresse = document.getElementById("tuteur_adresse").value.trim();
        const eleveNom = sanitizeInput(document.getElementById("eleve_nom").value.trim());
        const elevePrenom = sanitizeInput(document.getElementById("eleve_prenom").value.trim());
        const eleveDateNaissance = document.getElementById("eleve_date_naissance").value;
        const classeId = document.getElementById("classe_id").value;

        // Validation des champs obligatoires
        if (!tuteurNom || !tuteurPrenom || !tuteurTelephone || !tuteurAdresse ||
            !eleveNom || !elevePrenom || !eleveDateNaissance || !classeId) {
            showError(document.getElementById("tuteur_nom"), "Tous les champs marqués d'un * sont obligatoires.");
            isValid = false;
        }

        // Validation de l'email du tuteur
        if (tuteurEmail && !validateEmail(tuteurEmail)) {
            showError(document.getElementById("tuteur_email"), "L'adresse email du tuteur n'est pas valide.");
            isValid = false;
        }

        // Validation du téléphone du tuteur
        if (!/^[0-9]{9}$/.test(tuteurTelephone) || parseInt(tuteurTelephone, 10) < 750000000 || parseInt(tuteurTelephone, 10) > 789999999) {
            showError(document.getElementById("tuteur_telephone"), "Le téléphone du tuteur doit être un nombre de 9 chiffres dans la plage 750000000 à 789999999.");
            isValid = false;
        }

        // Validation des noms et prénoms
        if (!/^[a-zA-ZÀ-ÿ' ]+$/.test(tuteurNom)) {
            showError(document.getElementById("tuteur_nom"), "Le nom du tuteur ne doit contenir que des lettres.");
            isValid = false;
        }
        if (!/^[a-zA-ZÀ-ÿ' ]+$/.test(tuteurPrenom)) {
            showError(document.getElementById("tuteur_prenom"), "Le prénom du tuteur ne doit contenir que des lettres.");
            isValid = false;
        }
        if (!/^[a-zA-ZÀ-ÿ' ]+$/.test(eleveNom)) {
            showError(document.getElementById("eleve_nom"), "Le nom de l'élève ne doit contenir que des lettres.");
            isValid = false;
        }
        if (!/^[a-zA-ZÀ-ÿ' ]+$/.test(elevePrenom)) {
            showError(document.getElementById("eleve_prenom"), "Le prénom de l'élève ne doit contenir que des lettres.");
            isValid = false;
        }

        if (isValid) {
            // Transforme les entrées en noms propres
            document.getElementById("tuteur_nom").value = toTitleCase(tuteurNom);
            document.getElementById("tuteur_prenom").value = toTitleCase(tuteurPrenom);
            document.getElementById("eleve_nom").value = toTitleCase(eleveNom);
            document.getElementById("eleve_prenom").value = toTitleCase(elevePrenom);
            form.submit(); // Soumet le formulaire si tout est valide
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

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

    function sanitizeInput(input) {
        return input.replace(/</g, "&lt;").replace(/>/g, "&gt;");
    }

    function toTitleCase(str) {
        return str.replace(/\b\w/g, char => char.toUpperCase());
    }
});
