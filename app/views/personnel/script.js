document.addEventListener('DOMContentLoaded', function() {
    // Gestion des icônes pour afficher/masquer les mots de passe
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPassword = document.getElementById('confirm-password');

    if (togglePassword) {
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }

    if (toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }

    document.getElementById('register-button').addEventListener('click', function(event) {
        const nom = document.getElementById('nom');
        const prenom = document.getElementById('prenom');
        const email = document.getElementById('email');
        const telephone = document.getElementById('telephone');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm-password');
        const sexe = document.getElementById('sexe');
        const role = document.getElementById('role');
        const id_salaire = document.getElementById('id_salaire');
        const derniere_connexion = document.getElementById('derniere_connexion');
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

        // Validation de l'email
        if (email.value.trim() === '') {
            showError(email, "L'email est obligatoire.");
            isValid = false;
        } else if (!validateEmail(email.value.trim())) {
            showError(email, "L'email n'est pas valide.");
            isValid = false;
        }

        // Validation du téléphone
        if (telephone.value.trim() === '') {
            showError(telephone, "Le numéro de téléphone est obligatoire.");
            isValid = false;
        } else {
            const phoneValue = telephone.value.trim();
            if (!/^[0-9]{9}$/.test(phoneValue)) {
                showError(telephone, "Le numéro de téléphone doit contenir 9 chiffres.");
                isValid = false;
            } else {
                const phoneNumber = parseInt(phoneValue, 10);
                if (phoneNumber < 750000000 || phoneNumber > 789999999) {
                    showError(telephone, "Le numéro de téléphone doit être dans la plage 750000000 à 789999999.");
                    isValid = false;
                }
            }
        }

        // Validation du mot de passe
        if (password.value.trim() === '') {
            showError(password, "Le mot de passe est obligatoire.");
            isValid = false;
        } else if (password.value.length < 8) {
            showError(password, "Le mot de passe doit contenir au moins 8 caractères.");
            isValid = false;
        } else if (password.value !== confirmPassword.value) {
            showError(confirmPassword, "Les mots de passe ne correspondent pas.");
            isValid = false;
        }

        // Validation du sexe
        if (sexe.value.trim() === '') {
            showError(sexe, "Le sexe est obligatoire.");
            isValid = false;
        }

        // Validation du rôle
        if (role.value.trim() === '') {
            showError(role, "Le rôle est obligatoire.");
            isValid = false;
        }

        // Validation de l'identifiant de salaire
        if (id_salaire.value.trim() === '') {
            showError(id_salaire, "L'identifiant de salaire est obligatoire.");
            isValid = false;
        }

        // Validation de la dernière connexion
        if (derniere_connexion.value.trim() === '') {
            showError(derniere_connexion, "La date de prise de poste est obligatoire.");
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
