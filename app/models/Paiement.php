<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../config/config.php';

class Paiement {

    public $id_eleve;
    public $nom;
    public $prenom;
    public $matricule;
    public $adresse;
    public $email;
    public $telephone;
    public $date_naissance;
    public $niveau;
    public $enseignant_id;
    public $tuteur_id;
    public $classe_id;

    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getPDO();
    }

     

    // Récupérer les reçus d'un élève
    public function getRecusParEleve($id_eleve) {
        $sql = "SELECT * FROM recu r JOIN inscription i ON r.id_inscription = i.id_inscription WHERE i.id_eleve = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_eleve]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les détails d'un reçu
    public function getDetailsRecu($id_recu) {
        $sql = "SELECT * FROM recu WHERE id_recu = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_recu]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour un reçu
    public function mettreAJourRecu($id_recu, $nouveau_montant, $nouveau_moyen) {
        $sql = "UPDATE recu SET montant_paye = ?, moyen_paiement = ? WHERE id_recu = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nouveau_montant, $nouveau_moyen, $id_recu]);
    }

    // Annuler un reçu
    public function annulerRecu($id_recu) {
        $sql = "DELETE FROM recu WHERE id_recu = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_recu]);
    }

    // Créer un bulletin de salaire
    public function creerBulletinSalaire($id_employe, $nombre_heures, $type_salaire) {
        $salaire = $this->getSalaireEmploye($id_employe, $type_salaire);
        $montant_HT = $salaire * $nombre_heures;
        $taux_TVA = 0.20; // Par exemple, 20% de TVA
        $montant_TTC = $montant_HT + ($montant_HT * $taux_TVA);
        $date_bulletin = date('Y-m-d');

        $sql = "INSERT INTO bulletin_de_salaire (date_bulletin, nombre_heures, montant_HT, taux_TVA, montant_TTC, id_salaire) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$date_bulletin, $nombre_heures, $montant_HT, $taux_TVA, $montant_TTC, $id_employe]);

        return $this->pdo->lastInsertId();
    }

    // Récupérer tous les bulletins de salaire d'un employé
    public function getBulletinsParEmploye($id_employe) {
        $sql = "SELECT * FROM bulletin_de_salaire WHERE id_salaire = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_employe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les détails d'un bulletin de salaire
    public function getDetailsBulletinSalaire($id_bulletin) {
        $sql = "SELECT * FROM bulletin_de_salaire WHERE id_bulletin = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_bulletin]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour un bulletin de salaire
    public function mettreAJourBulletinSalaire($id_bulletin, $nouveau_nombre_heures) {
        $sql = "UPDATE bulletin_de_salaire SET nombre_heures = ? WHERE id_bulletin = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nouveau_nombre_heures, $id_bulletin]);
    }

    // Annuler un bulletin de salaire
    public function annulerBulletinSalaire($id_bulletin) {
        $sql = "DELETE FROM bulletin_de_salaire WHERE id_bulletin = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_bulletin]);
    }

    // Obtenir des statistiques sur les paiements
    public function getStatistiquesPaiements() {
        $sql = "SELECT COUNT(*) as total_paiements, SUM(montant_paye) as total_montant FROM recu";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    // Récupère le nombre total d'éléves
    public function getTotal() {
        $sql = "SELECT COUNT(*) FROM eleve";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchColumn(); // Retourne le nombre total d'élèves
    }


    // Récupère tous les élèves de la base de données
    // public function getAll($currentPage, $itemsPerPage) {
    //     $offset = ($currentPage - 1) * $itemsPerPage; // Calculer l'offset
    
    //     $sql = "SELECT e.*, t.nom AS tuteur_nom, t.prenom AS tuteur_prenom
    //             FROM eleve e
    //             LEFT JOIN tuteur t ON e.Tuteur_id_tuteur = t.id_tuteur
    //             LIMIT :offset, :itemsPerPage";
    
    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    //     $stmt->bindValue(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
    //     $stmt->execute();
    
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne les élèves pour la page actuelle
    // }
    
    
    public function getAll($currentPage, $itemsPerPage) {
        $offset = ($currentPage - 1) * $itemsPerPage; // Calculer l'offset

        $sql = "SELECT e.*, 
                       t.nom AS tuteur_nom, 
                       t.prenom AS tuteur_prenom,
                       CASE 
                           WHEN i.id_eleve IS NOT NULL THEN 'Actif'
                           ELSE 'Inactif'
                       END AS statut_inscription
                FROM eleve e
                LEFT JOIN tuteur t ON e.Tuteur_id_tuteur = t.id_tuteur
                LEFT JOIN inscription i ON e.id_eleve = i.id_eleve
                LIMIT :offset, :itemsPerPage";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne les élèves avec statut d'inscription
    }

    // Recupérer l'ensemble des paiements
    public function getAllPaiements() {
        $sql = "SELECT 
                    p.id_paiement,
                    p.montant,
                    p.date_paiement,
                    p.moyen_paiement,
                    p.type_paiement,
                    r.date_recu,
                    r.montant_paye,
                    r.moyen_paiement AS recu_moyen_paiement,
                    i.date_inscription,
                    i.annee_scolaire,
                    b.nombre_heures,
                    b.date_bulletin,
                    b.montant_HT,
                    b.taux_TVA,
                    b.montant_TTC,
                    per.nom AS personnel_nom,
                    per.prenom AS personnel_prenom,
                    per.email AS personnel_email,
                    m.montant AS eleve_mensualite_montant,
                    m.mois AS eleve_mensualite_mois,
                    e.prenom AS prenom_eleve,
                    e.nom AS nom_eleve,
                    e.date_naissance AS datenaissance_eleve
                FROM paiement p
                LEFT JOIN recu r ON p.id_recu = r.id_recu
                LEFT JOIN inscription i ON p.id_inscription = i.id_inscription
                LEFT JOIN bulletin_salaire b ON p.id_facture = b.id_facture
                LEFT JOIN personnel per ON p.id_personnel = per.id_personnel
                LEFT JOIN mensualite m ON p.id_mensualite = m.id_mensualite
                LEFT JOIN eleve e ON p.id_eleve = e.id_eleve";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer le salaire de l'employé selon le type de salaire
    private function getSalaireEmploye($id_employe, $type_salaire) {
        // Logique pour récupérer le salaire de l'employé (valeur fictive pour l'exemple)
        return 20; 
    }


    // Méthode pour traiter la création d'un paiement
    

    // Méthode pour afficher les informations d'un élève
    public function showEleveInfo($id_eleve) {
        // Récupérez les informations de l'élève via le modèle
        $eleveInfo = $this->paymentModel->getEleveInfo($id_eleve);
        
        // Incluez la vue pour afficher les informations de l'élève
        // include 'views/eleve_info.php';
    }
    
    public function createPayment($data) {
        try {
            // Récupérer id_inscription en fonction de id_eleve
            $sql_get_inscription = "SELECT id_inscription FROM inscription WHERE id_eleve = :id_eleve";
            $stmt_get_inscription = $this->pdo->prepare($sql_get_inscription);
            $stmt_get_inscription->execute([':id_eleve' => $data['id_eleve']]);
            $id_inscription = $stmt_get_inscription->fetchColumn();
    
            if (!$id_inscription) {
                throw new Exception('Inscription non trouvée pour cet élève.');
            }

    
            // Gestion du mois de paiement pour type "mensualité"
            $mois = null; 
            echo  $data;

            
            if ($data['type_paiement'] === 'mensualite' && !empty($data['mois'])) {
                $mois = $data['mois']; // S'assurer que le mois est bien récupéré
            }
    
            // Conversion du montant
            $montant = floatval($data['montant']); 
    
            // Requête d'insertion avec id_personnel facultatif et ajout du champ 'mois'
            $sql = "INSERT INTO paiement ( id_eleve, type_paiement, montant, moyen_paiement, date_paiement,id_personnel, id_inscription)
                    VALUES ( :id_eleve, :type_paiement, :montant, :moyen_paiement, :date_paiement, :id_personnel, :id_inscription)";
    
            $stmt = $this->pdo->prepare($sql);
    
            // Paramètres pour l'insertion
            $params = [
                ':id_eleve' => $data['id_eleve'],
                ':type_paiement' => "['type_paiement']",
                ':montant' => "tetess", 
                ':moyen_paiement' => "res",
                ':date_paiement' => $data['date_paiement'],
                ':id_personnel' => $data['id_personnel'],
                ':id_inscription' => $id_inscription,   
                //':mois' => "Octobre" // Vérifiez si le mois est bien assigné
            ];
    
            // Débogage : afficher les paramètres
            print_r($params); 
    
            // Exécuter la requête
            if (!$stmt->execute($params)) {
                // Afficher les erreurs si l'exécution échoue
                print_r($stmt->errorInfo()); // Afficher les erreurs
                return false; 
            }
    
            $id_paiement = $this->pdo->lastInsertId();
    
            // Récupérer les informations supplémentaires via jointure
            $sql_join = "
                SELECT p.id_paiement, p.type_paiement, p.montant, p.moyen_paiement, p.date_paiement, p.mois,
                       e.nom AS nom_eleve, e.prenom AS prenom_eleve, e.matricule, e.email, e.telephone
                FROM paiement p
                JOIN eleve e ON p.id_eleve = e.id_eleve
                WHERE p.id_paiement = :id_paiement";
    
            $stmt_join = $this->pdo->prepare($sql_join);
            $stmt_join->execute([':id_paiement' => $id_paiement]);
    
            return $stmt_join->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Afficher l'erreur
            echo 'Erreur : ' . $e->getMessage();
            return false; 
        }
    }
    
 // Fonction pour récupérer les mois payés d'un élève
 public function getMensualitesByEleve($idEleve) {
    // Requête pour récupérer les mois payés
    $query = $this->db->prepare("SELECT mois FROM paiement WHERE id_eleve = :idEleve");
    $query->bindParam(':idEleve', $idEleve, PDO::PARAM_INT);
    $query->execute();

    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

// Fonction pour récupérer les mois d'octobre à juillet
public function getTousLesMois() {
    return [
        'Octobre', 
        'Novembre', 
        'Décembre', 
        'Janvier', 
        'Février', 
        'Mars', 
        'Avril', 
        'Mai', 
        'Juin', 
        'Juillet'
    ];
}
    
    
    
    
    
//     public function getElevesAvecPaiements() {
//         $sql = "SELECT e.id_eleve, e.nom, e.prenom, e.matricule, e.telephone,
//                     i.annee_scolaire, 
//                     COUNT(CASE WHEN p.type_paiement = 'inscription' THEN 1 END) AS inscription_paye,
//                     GROUP_CONCAT(DISTINCT MONTH(p.date_paiement) ORDER BY MONTH(p.date_paiement)) AS mois_paiement,
//                     COUNT(CASE WHEN p.type_paiement = 'mensualite' THEN 1 END) AS mensualite_paye
//                 FROM eleve e
//                 LEFT JOIN inscription i ON e.id_eleve = i.id_eleve
//                 LEFT JOIN paiement p ON e.id_eleve = p.id_eleve
//                 GROUP BY e.id_eleve, i.annee_scolaire";

//     $stmt = $this->db->prepare($sql);
//     $stmt->execute();
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }





}
