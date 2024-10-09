document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");

    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Empêche la soumission par défaut
        clearErrors(); // Efface les erreurs précédentes

        let isValid = true;

        const nom = sanitizeInput(document.getElementById("nom").value.trim());
        const prenom = sanitizeInput(document.getElementById("prenom").value.trim());
        const email = document.getElementById("email").value.trim();
        const telephone = document.getElementById("telephone");
        const sexe = document.getElementById("sexe").value;
        const role = document.getElementById("role").value;
        const id_salaire = document.getElementById("id_salaire").value;
        const derniere_connexion = document.getElementById("derniere_connexion").value;

        if (!nom || !prenom || !email || !telephone.value.trim() || !sexe || !role || !id_salaire || !derniere_connexion) {
            showError(document.getElementById("nom"), "Tous les champs sont obligatoires.");
            isValid = false;
        }

        if (!validateEmail(email)) {
            showError(document.getElementById("email"), "L'adresse email n'est pas valide.");
            isValid = false;
        }

        // Validation du téléphone (doit être dans la plage spécifiée)
        const telephoneValue = telephone.value.trim();
        if (telephoneValue === '') {
            showError(telephone, "Le numéro de téléphone est obligatoire.");
            isValid = false;
        } else {
            const numTel = parseInt(telephoneValue, 10);
            if (!/^[0-9]{9}$/.test(telephoneValue) || numTel < 750000000 || numTel > 789999999) {
                showError(telephone, "Le numéro de téléphone doit être un nombre de 9 chiffres dans la plage 750000000 à 789999999.");
                isValid = false;
            }
        }

        // Vérification des caractères spéciaux dans le nom et le prénom
        if (!/^[a-zA-ZÀ-ÿ' ]+$/.test(nom)) {
            showError(document.getElementById("nom"), "Le nom ne doit contenir que des lettres et des espaces.");
            isValid = false;
        }
        if (!/^[a-zA-ZÀ-ÿ' ]+$/.test(prenom)) {
            showError(document.getElementById("prenom"), "Le prénom ne doit contenir que des lettres et des espaces.");
            isValid = false;
        }

        if (isValid) {
            // Transforme les entrées en noms propres
            document.getElementById("nom").value = toTitleCase(nom);
            document.getElementById("prenom").value = toTitleCase(prenom);
            form.submit(); // Soumet le formulaire si tout est valide
        }
    });

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function sanitizeInput(input) {
        return input.replace(/[^a-zA-ZÀ-ÿ' ]/g, "");
    }

    function toTitleCase(str) {
        return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
    }

    function showError(element, message) {
        const errorDisplay = document.createElement("div");
        errorDisplay.classList.add("text-danger", "mt-1");
        errorDisplay.textContent = message;
        element.classList.add("is-invalid");
        element.parentNode.insertBefore(errorDisplay, element.nextSibling);
    }

    function clearErrors() {
        const errorMessages = document.querySelectorAll(".text-danger");
        errorMessages.forEach(error => error.remove());
        const inputs = document.querySelectorAll("input, select");
        inputs.forEach(input => input.classList.remove("is-invalid"));
    }
});
