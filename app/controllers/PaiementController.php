<?php
require_once(__DIR__ . '/../models/PaiementModel.php'); // Assurez-vous que le chemin est correct


class PaiementController
{
    public function initiatePayment()
    {
        $paymentModel = new PaiementModel();

        $res = ['name' => 'Test Item'];
        $total = 100; // Montant en XOF
        $ref_commande = 'ORDER1234';
        $commande = 'Test Command';
        $success_url = 'http://localhost/Ecole-de-la-Reussite/public/index.php?action=success';
        $ipn_url = 'http://localhost/Ecole-de-la-Reussite/public/index.php?action=ipn';
        $cancel_url = 'http://localhost/Ecole-de-la-Reussite/public/index.php?action=cancel';
        $customfield = 'Données Personnalisées';

        $api_key = 'c308a012c5cfc159fefb3ca94ffb564c1bd65ff20e5748150ec82e5dfb8f0b3d';
        $api_secret = 'e3a963be70cd66d300d363563d295cc33dd4a17d1f39369e23a873eb6179848e';

        $postFields = [
            "item_name"    => $res['name'],
            "item_price"   => $total,
            "currency"     => "xof",
            "ref_command"  => $ref_commande,
            "command_name" => $commande,
            "success_url"  => $success_url,
            "ipn_url"      => $ipn_url,
            "cancel_url"   => $cancel_url,
            "custom_field" => $customfield
        ];

        $response = $paymentModel->makePayment($postFields, $api_key, $api_secret);

        $jsonResponse = json_decode($response, true);

        if ($jsonResponse === null) {
            error_log("Erreur de décodage JSON: " . json_last_error_msg());
            error_log("Réponse brute de l'API: " . $response);
            require_once '../app/views/paiement/error.php';
            return;
        }

        error_log("Réponse de l'API de paiement: " . print_r($jsonResponse, true));

        // Préparer les données à sauvegarder dans la base de données
        $transactionData = [
            'item_name' => $res['name'],
            'item_price' => $total,
            'currency' => 'xof',
            'ref_command' => $ref_commande,
            'command_name' => $commande,
            'custom_field' => $customfield,
            'status' => $jsonResponse['status'] ?? 'failed',
            'response_text' => $response,
            'id_eleve' => null, // Remplacez par l'ID de l'élève si applicable
            'id_personnel' => null // Remplacez par l'ID du personnel si applicable
        ];

        if (isset($jsonResponse['status']) && $jsonResponse['status'] === 'success') {
            // Enregistrer la transaction dans la base de données
            $paymentModel->saveTransaction($transactionData);
            require '../app/views/paiement/success.php';
        } else {
            // En cas d'erreur ou si le statut n'est pas 'success'
            $transactionData['status'] = $jsonResponse['status'] ?? 'failed';
            $paymentModel->saveTransaction($transactionData);
            require_once '../app/views/paiement/error.php';
        }
        
    }
}
