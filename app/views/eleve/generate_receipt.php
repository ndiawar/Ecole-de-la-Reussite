<?php
require('fpdf186/fpdf.php'); // 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les informations de paiement
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $matricule = $_POST['matricule'];
    $montant = $_POST['montant'];
    $type_paiement = $_POST['type_paiement'];
    $moyen_paiement = $_POST['moyen_paiement'];
    $date_paiement = $_POST['date_paiement'];
    $mois_paiement = $_POST['mois_paiement'] ?? '';

    // Créer une instance de FPDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Ajouter un titre
    $pdf->Cell(0, 10, 'Reçu de Paiement', 0, 1, 'C');

    // Ajouter les détails du paiement
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Nom: $nom $prenom", 0, 1);
    $pdf->Cell(0, 10, "Matricule: $matricule", 0, 1);
    $pdf->Cell(0, 10, "Montant: $montant FCFA", 0, 1);
    $pdf->Cell(0, 10, "Type de Paiement: $type_paiement", 0, 1);
    $pdf->Cell(0, 10, "Moyen de Paiement: $moyen_paiement", 0, 1);
    $pdf->Cell(0, 10, "Date de Paiement: $date_paiement", 0, 1);
    if ($mois_paiement) {
        $pdf->Cell(0, 10, "Mois de Paiement: $mois_paiement", 0, 1);
    }

    // Enregistrer le PDF
    $pdfFileName = "receipt_{$matricule}_" . date('YmdHis') . ".pdf";
    $pdf->Output('D', $pdfFileName); // 'D' pour forcer le téléchargement
    exit; // Terminer le script après le téléchargement
}
