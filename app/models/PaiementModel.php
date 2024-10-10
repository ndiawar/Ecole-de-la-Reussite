<?php
require_once '../config/config.php';

class PaiementModel
{
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getPDO();
    }

    public function makePayment($data, $api_key, $api_secret)
    {
        $url = 'https://paytech.sn/api/payment/request-payment';
        $headers = [
            "API_KEY: " . $api_key,
            "API_SECRET: " . $api_secret
        ];

        $response = $this->post($url, $data, $headers);

        if ($response === false) {
            error_log("Erreur lors de l'appel cURL");
        } else {
            error_log("Réponse cURL: " . $response);
        }

        return $response;
    }

    public function post($url, $data = [], $headers = [])
    {
        $strPostField = http_build_query($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $strPostField);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers, [
            'Content-Type: application/x-www-form-urlencoded;charset=utf-8',
            'Content-Length: ' . mb_strlen($strPostField)
        ]));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log("Erreur cURL: " . curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }

    public function saveTransaction($data)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO transactions 
                (item_name, item_price, currency, ref_command, command_name, custom_field, status, response_text, id_eleve, id_personnel) 
                VALUES (:item_name, :item_price, :currency, :ref_command, :command_name, :custom_field, :status, :response_text, :id_eleve, :id_personnel)");
            
            $stmt->execute([
                ':item_name' => $data['item_name'],
                ':item_price' => $data['item_price'],
                ':currency' => $data['currency'],
                ':ref_command' => $data['ref_command'],
                ':command_name' => $data['command_name'],
                ':custom_field' => $data['custom_field'],
                ':status' => $data['status'],
                ':response_text' => $data['response_text'],
                ':id_eleve' => $data['id_eleve'] ?? null,
                ':id_personnel' => $data['id_personnel'] ?? null,
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'enregistrement de la transaction : " . $e->getMessage());
            return false;
        }
    }
}

