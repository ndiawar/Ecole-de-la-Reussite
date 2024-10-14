function toggleMoisPaiement() {
    var typePaiement = document.getElementById("type_paiement").value;
    var moisPaiement = document.getElementById("mois_paiement");

    if (typePaiement === "mensualite") {
        moisPaiement.disabled = false; // Activer le champ "Mois de paiement"
    } else {
        moisPaiement.disabled = true; // Désactiver le champ "Mois de paiement"
        moisPaiement.value = ""; // Réinitialiser la sélection
    }
}
