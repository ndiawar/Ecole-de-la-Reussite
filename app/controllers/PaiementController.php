<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Paiement.php';
require_once __DIR__ . '/../../config/config.php';

class paiementController {
    private $paiementModel;

    
    public function __construct() {
        $database = new Database();
        $db = $database->getPDO();
        $this->paiementModel = new Paiement($db);
    }

    // Enregistrer un paiement et générer un reçu
    public function enregistrerPaiement($id_inscription, $montant_paye, $moyen_paiement) {
        if ($this->validerMontant($montant_paye)) {
            $id_recu = $this->paiementModel->creerRecu($id_inscription, $montant_paye, $moyen_paiement);
            echo $id_recu ? "Paiement enregistré avec succès. Reçu ID: " . $id_recu : "Erreur lors de l'enregistrement du paiement.";
        } else {
            echo "Montant payé invalide.";
        }
    }

    // Récupérer tous les reçus d'un élève
    public function recupererRecusEleve($id_eleve) {
        return $this->paiementModel->getRecusParEleve($id_eleve);
    }

    // Récupérer les détails d'un reçu spécifique
    public function recupererDetailsRecu($id_recu) {
        return $this->paiementModel->getDetailsRecu($id_recu);
    }

    // Mettre à jour un paiement
    public function mettreAJourPaiement($id_recu, $nouveau_montant, $nouveau_moyen) {
        if ($this->validerMontant($nouveau_montant)) {
            $this->paiementModel->mettreAJourRecu($id_recu, $nouveau_montant, $nouveau_moyen);
        } else {
            echo "Montant payé invalide.";
        }
    }

    // Annuler un paiement
    public function annulerPaiement($id_recu) {
        $this->paiementModel->annulerRecu($id_recu);
    }

    // Générer un bulletin de salaire
    public function genererBulletinSalaire($id_employe, $nombre_heures, $type_salaire) {
        $bulletin = $this->paiementModel->creerBulletinSalaire($id_employe, $nombre_heures, $type_salaire);
        return $bulletin;
    }

    // Récupérer tous les bulletins de salaire d'un employé
    public function recupererBulletinsSalaire($id_employe) {
        return $this->paiementModel->getBulletinsParEmploye($id_employe);
    }

    // Récupérer les détails d'un bulletin de salaire spécifique
    public function recupererDetailsBulletin($id_bulletin) {
        return $this->paiementModel->getDetailsBulletinSalaire($id_bulletin);
    }

    // Mettre à jour un bulletin de salaire
    public function mettreAJourBulletin($id_bulletin, $nouveau_nombre_heures) {
        $this->paiementModel->mettreAJourBulletinSalaire($id_bulletin, $nouveau_nombre_heures);
    }

    // Annuler un bulletin de salaire
    public function annulerBulletin($id_bulletin) {
        $this->paiementModel->annulerBulletinSalaire($id_bulletin);
    }

    public function index() {
        $paiements = $this->paiementModel->getAllPaiements(); // Récupérer tous les paiements
        require '../app/views/eleve/listPaiements.php'; // Inclure la vue pour afficher les paiements
    }

    // Affiche la liste des élèves
    public function listEléves() {
        $eleves = $this->paiementModel->getAll(); // Récupérer tous les éléves
        // $eleves = Eleve::getAll();
        require_once '../app/views/eleve/listEleve.php';
    }

    // Obtenir des statistiques sur les paiements
    public function obtenirStatistiques() {
        return $this->paiementModel->getStatistiquesPaiements();
    }

    // Valider le montant
    private function validerMontant($montant) {
        return is_numeric($montant) && $montant > 0;
    }
}
