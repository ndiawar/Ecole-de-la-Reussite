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
        const eleveDateNaissance = document.getElementById("eleve_date_naissance").value;
        const classeId = document.getElementById("classe_id").value;
        const eleveEmail = document.getElementById("eleve_email").value.trim();
        const eleveSexe = document.getElementById("eleve_sexe").value;
        const eleveAdresse = document.getElementById("eleve_adresse").value.trim(); // Ajout de l'adresse de l'élève

        // Validation des champs obligatoires
        if (!tuteurNom) {
            showError("tuteur_nom", "Le nom du tuteur est obligatoire.");
            isValid = false;
        } else if (!/^[a-zA-ZÀ-ÿ' ]+$/.test(tuteurNom)) {
            showError("tuteur_nom", "Le nom du tuteur ne doit contenir que des lettres.");
            isValid = false;
        }

        if (!tuteurPrenom) {
            showError("tuteur_prenom", "Le prénom du tuteur est obligatoire.");
            isValid = false;
        } else if (!/^[a-zA-ZÀ-ÿ' ]+$/.test(tuteurPrenom)) {
            showError("tuteur_prenom", "Le prénom du tuteur ne doit contenir que des lettres.");
            isValid = false;
        }

        if (!tuteurTelephone) {
            showError("tuteur_telephone", "Le téléphone du tuteur est obligatoire.");
            isValid = false;
        } else if (!/^[0-9]{9}$/.test(tuteurTelephone) || parseInt(tuteurTelephone, 10) < 750000000 || parseInt(tuteurTelephone, 10) > 789999999) {
            showError("tuteur_telephone", "Le téléphone doit être un nombre de 9 chiffres dans la plage 750000000 à 789999999.");
            isValid = false;
        }

        if (!tuteurAdresse) {
            showError("tuteur_adresse", "L'adresse du tuteur est obligatoire.");
            isValid = false;
        }

        if (!eleveNom) {
            showError("eleve_nom", "Le nom de l'élève est obligatoire.");
            isValid = false;
        } else if (!/^[a-zA-ZÀ-ÿ' ]+$/.test(eleveNom)) {
            showError("eleve_nom", "Le nom de l'élève ne doit contenir que des lettres.");
            isValid = false;
        }

        if (!elevePrenom) {
            showError("eleve_prenom", "Le prénom de l'élève est obligatoire.");
            isValid = false;
        } else if (!/^[a-zA-ZÀ-ÿ' ]+$/.test(elevePrenom)) {
            showError("eleve_prenom", "Le prénom de l'élève ne doit contenir que des lettres.");
            isValid = false;
        }

        if (!eleveAdresse) {
            showError("eleve_adresse", "L'adresse de l'élève est obligatoire."); // Validation de l'adresse
            isValid = false;
        }

        if (!eleveDateNaissance) {
            showError("eleve_date_naissance", "La date de naissance est obligatoire.");
            isValid = false;
        }

        if (!classeId) {
            showError("classe_id", "La classe est obligatoire.");
            isValid = false;
        }

        // Vérifier l'email du tuteur
        if (!tuteurEmail) {
            showError("tuteur_email", "L'adresse email du tuteur est obligatoire.");
            isValid = false;
        } else if (!validateEmail(tuteurEmail)) {
            showError("tuteur_email", "L'adresse email du tuteur n'est pas valide.");
            isValid = false;
        }

        // Vérifier l'email de l'élève
        if (!eleveEmail) {
            showError("eleve_email", "L'email de l'élève est obligatoire.");
            isValid = false;
        } else if (!validateEmail(eleveEmail)) {
            showError("eleve_email", "L'adresse email de l'élève n'est pas valide.");
            isValid = false;
        }

        // Vérifier le sexe de l'élève
        if (!eleveSexe) {
            showError("eleve_sexe", "Le sexe de l'élève est obligatoire.");
            isValid = false;
        }

        // Vérification de l'âge
        if (eleveDateNaissance) {
            const birthDate = new Date(eleveDateNaissance);
            const age = new Date().getFullYear() - birthDate.getFullYear();
            if (age < 6 || age > 18) {
                showError("eleve_date_naissance", "L'âge doit être compris entre 6 et 18 ans.");
                isValid = false;
            }
        }

        if (isValid) {
            // Capitaliser les entrées
            document.getElementById("tuteur_nom").value = toTitleCase(tuteurNom);
            document.getElementById("tuteur_prenom").value = toTitleCase(tuteurPrenom);
            document.getElementById("eleve_nom").value = toTitleCase(eleveNom);
            document.getElementById("eleve_prenom").value = toTitleCase(elevePrenom);
            form.submit(); // Soumettre le formulaire
        }
    });

    function showError(inputId, message) {
        const inputElement = document.getElementById(inputId);
        const errorMessageDiv = document.createElement("div");
        errorMessageDiv.className = "error-message text-danger";
        errorMessageDiv.innerHTML = message;
        inputElement.parentNode.insertBefore(errorMessageDiv, inputElement.nextSibling); // Insérer le message après l'input
        inputElement.classList.add("input-error"); // Ajouter une classe d'erreur à l'input
        inputElement.style.border = "1px solid red"; // Changer la bordure en rouge
    }

    function clearErrors() {
        // Effacer tous les messages d'erreur
        const errorMessages = document.querySelectorAll(".error-message");
        errorMessages.forEach(div => {
            div.remove(); // Supprimer le message d'erreur
        });

        // Retirer la classe d'erreur de tous les inputs
        const inputs = document.querySelectorAll("input, select");
        inputs.forEach(input => {
            input.classList.remove("input-error");
            input.style.border = ""; // Réinitialiser la bordure
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

// Limiter la date de naissance
document.addEventListener("DOMContentLoaded", function() {
    const today = new Date();
    
    // Date minimale (6 ans)
    const minDate = new Date();
    minDate.setFullYear(today.getFullYear() - 6);
    const formattedMinDate = minDate.toISOString().split('T')[0];
    
    // Date maximale (18 ans)
    const maxDate = new Date();
    maxDate.setFullYear(today.getFullYear() - 18);
    const formattedMaxDate = maxDate.toISOString().split('T')[0];
    
    const dateInput = document.getElementById("eleve_date_naissance");
    dateInput.setAttribute("min", formattedMaxDate); // Mettre à jour l'attribut min
    dateInput.setAttribute("max", formattedMinDate); // Mettre à jour l'attribut max
});



    $(document).ready(function() {
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
