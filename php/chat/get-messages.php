<?php
require '../../vendor/autoload.php';
require '../config.php';

use MongoDB\Client;

header('Content-Type: application/json'); // Indique que c'est du JSON

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $uri = "mongodb+srv://dylanfragnaud16:2orxZj2vzbn9chBI@cluster0.w1i7o.mongodb.net/Gameverse_db?retryWrites=true&w=majority&tls=true";

    $client = new MongoDB\Client($uri, [], [
        'ssl' => true,
        'tlsAllowInvalidCertificates' => true
    ]);

    $db = $client->Gameverse_db;
    $collection = $db->messages;

    $messages = $collection->find([], ['sort' => ['timestamp' => 1]]);
    $messageArray = [];

    foreach ($messages as $message) {
        $messageArray[] = [
            "id" => (string) $message['_id'], // Convertir `_id` en string pour JavaScript
            "sender" => $message['sender'],
            "message" => $message['message'],
            "timestamp" => $message['timestamp']->toDateTime()->format('H:i')
        ];
    }

    echo json_encode($messageArray);
} catch (Exception $e) {
    echo json_encode(["error" => "Connexion MongoDB échouée : " . $e->getMessage()]);
}
?>
