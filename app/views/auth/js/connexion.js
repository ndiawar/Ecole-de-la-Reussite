// Fonction de validation du formulaire
document.getElementById('login-button').addEventListener('click', function(e) {
    const matricule = document.getElementById('matricule');
    const password = document.getElementById('password');
    let isValid = true;

    // Réinitialiser les messages d'erreur
    clearErrors();

    // Validation du matricule
    if (matricule.value.trim() === '') {
        showError(matricule, "Email ou Matricule obligatoire.");
        matricule.classList.add('input-error'); // Colorie l'input en rouge
        isValid = false;
    } else {
        matricule.classList.remove('input-error'); // Enlève le style d'erreur si le matricule est correct
    }

    // Validation du mot de passe (non vide et longueur minimale)
    if (password.value.trim() === '') {
        showError(password, "Le mot de passe est obligatoire.");
        password.classList.add('input-error'); // Colorie l'input en rouge
        isValid = false;
    } else if (password.value.length < 8) {
        showError(password, "Le mot de passe doit contenir au moins 8 caractères.");
        password.classList.add('input-error'); // Colorie l'input en rouge
        isValid = false;
    } else {
        password.classList.remove('input-error'); // Enlève le style d'erreur
    }

    // Empêcher la soumission si des erreurs existent
    if (!isValid) {
        e.preventDefault();
    }
});

// Fonction pour afficher un message d'erreur
function showError(input, msg) {
    const errEl = input.parentElement.querySelector('.error-message');
    if (errEl) {
        errEl.textContent = msg;
        errEl.style.visibility = 'visible'; // Rendre visible le message
        input.classList.add('input-error'); // Ajouter la classe d'erreur pour changer la couleur
    }
}

// Fonction pour effacer tous les messages d'erreur
function clearErrors() {
    const errors = document.querySelectorAll('.error-message');
    errors.forEach(err => {
        err.textContent = ''; // Vider le contenu
        err.style.visibility = 'hidden'; // Cacher à nouveau
    });

    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        // Réinitialiser uniquement les styles des champs sans erreur
        if (!input.classList.contains('input-error')) {
            input.style.borderColor = ''; // Réinitialiser la couleur de la bordure
        }
    });
}

// Fonction pour afficher/masquer le mot de passe
document.getElementById('toggle-password').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const eyeIcon = this.querySelector('i');

    // Alterner entre 'password' et 'text'
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    }
});