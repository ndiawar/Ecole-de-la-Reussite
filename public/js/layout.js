// Fonction pour afficher une alerte lors de l'archivage
function archiverEmploye(nom) {
    alert("L'employé " + nom + " a été archivé avec succès !");
}

// Fonction pour afficher une alerte lors de la suppression
function supprimerEmploye(nom) {
    if (confirm("Êtes-vous sûr de vouloir supprimer l'employé " + nom + " ?")) {
        alert("L'employé " + nom + " a été supprimé avec succès !");
    }
}

// Fonction pour afficher une alerte lors de la modification
function modifierEmploye(nom) {
    alert("Vous allez modifier les informations de l'employé " + nom + ".");
}
