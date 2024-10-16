document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Empêche l'envoi par défaut
        clearErrors(); // Efface les erreurs précédentes

        let isValid = true;

        // Obtenir et assainir les valeurs des champs
        const tuteurNom = sanitizeInput(document.getElementById("tuteur_nom").value.trim());
        const tuteurPrenom = sanitizeInput(document.getElementById("tuteur_prenom").value.trim());
        const tuteurEmail = document.getElementById("tuteur_email").value.trim();
        const tuteurTelephone = document.getElementById("tuteur_telephone").value.trim();
        const tuteurAdresse = document.getElementById("tuteur_adresse").value.trim();
        const eleveNom = sanitizeInput(document.getElementById("eleve_nom").value.trim());
        const elevePrenom = sanitizeInput(document.getElementById("eleve_prenom").value.trim());
        const eleveEmail = document.getElementById("eleve_email").value.trim(); // Email de l'élève
        const eleveAdresse = document.getElementById("eleve_adresse").value.trim();
        const eleveDateNaissance = document.getElementById("eleve_date_naissance").value;
        const classeId = document.getElementById("classe_id").value;

        // Valider les champs requis
        const requiredFields = [
            { value: tuteurNom, id: "tuteur_nom", message: "Le nom du tuteur est obligatoire." },
            { value: tuteurPrenom, id: "tuteur_prenom", message: "Le prénom du tuteur est obligatoire." },
            { value: tuteurTelephone, id: "tuteur_telephone", message: "Le téléphone du tuteur est obligatoire." },
            { value: tuteurAdresse, id: "tuteur_adresse", message: "L'adresse du tuteur est obligatoire." },
            { value: tuteurEmail, id: "tuteur_email", message: "L'email du tuteur est obligatoire." },
            { value: eleveNom, id: "eleve_nom", message: "Le nom de l'élève est obligatoire." },
            { value: elevePrenom, id: "eleve_prenom", message: "Le prénom de l'élève est obligatoire." },
            { value: eleveEmail, id: "eleve_email", message: "L'email de l'élève est obligatoire." },
            { value: eleveDateNaissance, id: "eleve_date_naissance", message: "La date de naissance est obligatoire." },
            { value: eleveAdresse, id: "eleve_adresse", message: "L'adresse de l'élève est obligatoire." },
            { value: classeId, id: "classe_id", message: "La classe est obligatoire." },
        ];

        requiredFields.forEach(field => {
            if (!field.value) {
                showError(field.id, field.message);
                isValid = false;
            }
        });

         // Vérification de l'âge
         if (eleveDateNaissance) {
            const birthDate = new Date(eleveDateNaissance);
            const age = new Date().getFullYear() - birthDate.getFullYear();
            if (age < 6 || age > 18) {
                showError("eleve_date_naissance", "L'âge doit être compris entre 6 et 18 ans.");
                isValid = false;
            }
        }

        // Valider les emails
        if (isValid) {
            if (!validateEmail(tuteurEmail)) {
                showError("tuteur_email", "L'adresse email du tuteur n'est pas valide.");
                isValid = false;
            }

            if (!validateEmail(eleveEmail)) {
                showError("eleve_email", "L'adresse email de l'élève n'est pas valide.");
                isValid = false;
            }
        }

        // Valider le téléphone du tuteur
        if (isValid && (!/^[0-9]{9}$/.test(tuteurTelephone) || 
            parseInt(tuteurTelephone, 10) < 750000000 || 
            parseInt(tuteurTelephone, 10) > 789999999)) {
            showError("tuteur_telephone", "Le téléphone du tuteur doit être un nombre de 9 chiffres dans la plage 750000000 à 789999999.");
            isValid = false;
        }

        // Valider les noms
        const nameFields = [
            { value: tuteurNom, id: "tuteur_nom" },
            { value: tuteurPrenom, id: "tuteur_prenom" },
            { value: eleveNom, id: "eleve_nom" },
            { value: elevePrenom, id: "eleve_prenom" },
        ];

        nameFields.forEach(field => {
            if (!/^[a-zA-ZÀ-ÿ' ]+$/.test(field.value)) {
                showError(field.id, `${field.id === "eleve_nom" ? "Le nom de l'élève" : "Le nom du tuteur"} ne doit contenir que des lettres.`);
                isValid = false;
            }
        });

        // Soumettre le formulaire si tout est valide
        if (isValid) {
            form.submit();
        }
    });

    // Fonction pour afficher une erreur
    function showError(inputId, message) {
        const inputElement = document.getElementById(inputId);
        const errorMessageDiv = inputElement.nextElementSibling; // Obtenir le div de message d'erreur
        errorMessageDiv.innerHTML = message; // Définir le message d'erreur
        errorMessageDiv.style.visibility = "visible"; // Le rendre visible
        inputElement.classList.add("input-error"); // Ajouter une classe d'erreur à l'input
        inputElement.style.border = "1px solid red"; // Changer la bordure en rouge
    }

    // Fonction pour effacer les erreurs
    function clearErrors() {
        const errorMessages = document.querySelectorAll(".error-message");
        errorMessages.forEach(div => {
            div.innerHTML = '';
            div.style.visibility = "hidden"; // Masquer le message d'erreur
        });

        const inputs = document.querySelectorAll("input, select");
        inputs.forEach(input => {
            input.classList.remove("input-error");
            input.style.border = ""; // Réinitialiser la bordure
        });
    }

    // Fonction pour valider l'email
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

    // Fonction pour assainir l'entrée
    function sanitizeInput(input) {
        return input.replace(/</g, "&lt;").replace(/>/g, "&gt;");
    }

    // Fonction pour mettre en majuscule la première lettre de chaque mot
    function toTitleCase(str) {
        return str.replace(/\b\w/g, char => char.toUpperCase());
    }

   // Limiter la date de naissance
   const today = new Date();
   const minDate = new Date();
   minDate.setFullYear(today.getFullYear() - 18); // 18 ans
   const maxDate = new Date();
   maxDate.setFullYear(today.getFullYear() - 6); // 6 ans

   const dateInput = document.getElementById("eleve_date_naissance");
   dateInput.setAttribute("min", minDate.toISOString().split('T')[0]); // Mettre à jour l'attribut min
   dateInput.setAttribute("max", maxDate.toISOString().split('T')[0]); // Mettre à jour l'attribut max

});
