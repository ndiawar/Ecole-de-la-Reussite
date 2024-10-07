// Fonction de validation du formulaire d'inscription
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('register-button').addEventListener('click', function(event) {
        const nom = document.getElementById('nom');
        const prenom = document.getElementById('prenom');
        const email = document.getElementById('email');
        const telephone = document.getElementById('telephone');
        const password = document.getElementById('password');
        const sexe = document.getElementById('sexe');
        const role = document.getElementById('role');
        const statut_compte = document.getElementById('statut_compte');
        const id_salaire = document.getElementById('id_salaire');
        let isValid = true;

        // Réinitialiser les messages d'erreur
        clearErrors();

        // Validation des champs
        if (nom.value.trim() === '') {
            showError(nom, "Le nom est obligatoire.");
            isValid = false;
        }

        if (prenom.value.trim() === '') {
            showError(prenom, "Le prénom est obligatoire.");
            isValid = false;
        }

        // Validation de l'email (non vide et format valide)
        if (email.value.trim() === '') {
            showError(email, "L'email est obligatoire.");
            isValid = false;
        } else if (!validateEmail(email.value.trim())) {
            showError(email, "L'email n'est pas valide.");
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

        // Validation du mot de passe (non vide et longueur minimale)
        if (password.value.trim() === '') {
            showError(password, "Le mot de passe est obligatoire.");
            isValid = false;
        } else if (password.value.length < 8) {
            showError(password, "Le mot de passe doit contenir au moins 8 caractères.");
            isValid = false;
        }

        // Validation du sexe (non vide)
        if (sexe.value.trim() === '') {
            showError(sexe, "Le sexe est obligatoire.");
            isValid = false;
        }

        // Validation du rôle (non vide)
        if (role.value.trim() === '') {
            showError(role, "Le rôle est obligatoire.");
            isValid = false;
        }

        // Validation du statut du compte (non vide)
        if (statut_compte.value.trim() === '') {
            showError(statut_compte, "Le statut du compte est obligatoire.");
            isValid = false;
        }

        // Validation de l'identifiant de salaire (non vide, si nécessaire)
        if (id_salaire && id_salaire.value.trim() === '') {
            showError(id_salaire, "L'identifiant de salaire est obligatoire.");
            isValid = false;
        }

        // Empêcher la soumission si des erreurs sont présentes
        if (!isValid) {
            event.preventDefault();
        }
    });

    // Fonction pour afficher un message d'erreur sous le champ
    function showError(inputElement, errorMessage) {
        const errorElement = document.createElement('div');
        errorElement.classList.add('error-message');
        errorElement.textContent = errorMessage;
        inputElement.parentElement.appendChild(errorElement);
        inputElement.classList.add('input-error');
    }

    // Fonction pour effacer tous les messages d'erreur
    function clearErrors() {
        const errors = document.querySelectorAll('.error-message');
        errors.forEach(error => error.remove());
        const inputs = document.querySelectorAll('.input-error');
        inputs.forEach(input => input.classList.remove('input-error'));
    }

    // Fonction pour valider le format de l'email
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }
});
