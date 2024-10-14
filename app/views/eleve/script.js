document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");

    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent default submission
        clearErrors(); // Clear previous errors

        let isValid = true;

        // Get and sanitize input values
        const tuteurNom = sanitizeInput(document.getElementById("tuteur_nom").value.trim());
        const tuteurPrenom = sanitizeInput(document.getElementById("tuteur_prenom").value.trim());
        const tuteurEmail = document.getElementById("tuteur_email").value.trim();
        const tuteurTelephone = document.getElementById("tuteur_telephone").value.trim();
        const tuteurAdresse = document.getElementById("tuteur_adresse").value.trim();
        const eleveNom = sanitizeInput(document.getElementById("eleve_nom").value.trim());
        const elevePrenom = sanitizeInput(document.getElementById("eleve_prenom").value.trim());
        const eleveDateNaissance = document.getElementById("eleve_date_naissance").value;
        const classeId = document.getElementById("classe_id").value;

        // Validate required fields
        if (!tuteurNom) {
            showError(document.getElementById("tuteur_nom"), "Le nom du tuteur est obligatoire.");
            isValid = false;
        }
        if (!tuteurPrenom) {
            showError(document.getElementById("tuteur_prenom"), "Le prénom du tuteur est obligatoire.");
            isValid = false;
        }
        if (!tuteurTelephone) {
            showError(document.getElementById("tuteur_telephone"), "Le téléphone du tuteur est obligatoire.");
            isValid = false;
        }
        if (!tuteurAdresse) {
            showError(document.getElementById("tuteur_adresse"), "L'adresse du tuteur est obligatoire.");
            isValid = false;
        }
        if (!eleveNom) {
            showError(document.getElementById("eleve_nom"), "Le nom de l'élève est obligatoire.");
            isValid = false;
        }
        if (!elevePrenom) {
            showError(document.getElementById("eleve_prenom"), "Le prénom de l'élève est obligatoire.");
            isValid = false;
        }
        if (!eleveDateNaissance) {
            showError(document.getElementById("eleve_date_naissance"), "La date de naissance est obligatoire.");
            isValid = false;
        }
        if (!classeId) {
            showError(document.getElementById("classe_id"), "La classe est obligatoire.");
            isValid = false;
        }

        // Validate email
        if (tuteurEmail && !validateEmail(tuteurEmail)) {
            showError(document.getElementById("tuteur_email"), "L'adresse email du tuteur n'est pas valide.");
            isValid = false;
        }

        // Validate phone
        if (!/^[0-9]{9}$/.test(tuteurTelephone) || parseInt(tuteurTelephone, 10) < 750000000 || parseInt(tuteurTelephone, 10) > 789999999) {
            showError(document.getElementById("tuteur_telephone"), "Le téléphone du tuteur doit être un nombre de 9 chiffres dans la plage 750000000 à 789999999.");
            isValid = false;
        }

        // Validate names
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
            // Capitalize inputs
            document.getElementById("tuteur_nom").value = toTitleCase(tuteurNom);
            document.getElementById("tuteur_prenom").value = toTitleCase(tuteurPrenom);
            document.getElementById("eleve_nom").value = toTitleCase(eleveNom);
            document.getElementById("eleve_prenom").value = toTitleCase(elevePrenom);
            form.submit(); // Submit the form
        }
    });

    function showError(inputElement, message) {
        const errorMessageDiv = inputElement.nextElementSibling; // Get the error message div
        errorMessageDiv.innerHTML = message; // Set the error message
        errorMessageDiv.style.visibility = "visible"; // Make it visible
        inputElement.classList.add("input-error"); // Add error class to input
    }
    
    function clearErrors() {
        // Clear all error messages
        const errorMessages = document.querySelectorAll(".error-message");
        errorMessages.forEach(div => {
            div.innerHTML = '';
            div.style.visibility = "hidden"; // Hide error message
        });
        
        // Remove error class from all inputs
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
