// Fonction de validation du formulaire
document.getElementById('login-button').addEventListener('click', function(event) {
    const matricule = document.getElementById('matricule');
    const password = document.getElementById('password');
    let isValid = true;
    
    // Réinitialiser les messages d'erreur
    clearErrors();

    // Validation du matricule (non vide)
    if (matricule.value.trim() === '') {
        showError(matricule, "Le matricule est obligatoire.");
        isValid = false;
    }

    // Validation du mot de passe (non vide et longueur minimale)
    if (password.value.trim() === '') {
        showError(password, "Le mot de passe est obligatoire.");
        isValid = false;
    } else if (password.value.length < 8) {
        showError(password, "Le mot de passe doit contenir au moins 8 caractères.");
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
