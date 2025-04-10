<?php
require '../../vendor/autoload.php';
require '../config.php';

use MongoDB\Client;

header('Content-Type: application/json');

try {
    $uri = getenv("MONGODB_URI");
    $mongo = new Client(getenv("MONGODB_URI"));

    $client = new MongoDB\Client($uri, [], [
        'ssl' => true,
        'tlsAllowInvalidCertificates' => true
    ]);

    $db = $client->Gameverse_db;
    $collection = $db->messages;

    // Vérifier que l'utilisateur est bien connecté
    if (!isset($_SESSION["user"]["username"])) {
        echo json_encode(["error" => "Utilisateur non connecté"]);
        exit();
    }

    // Récupérer l'expéditeur côté serveur
    $sender = $_SESSION["user"]["username"];

    // Récupérer le message envoyé via fetch()
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['message']) || trim($data['message']) === "") {
        echo json_encode(["error" => "Message vide"]);
        exit();
    }

    $messageData = [
        "sender" => $sender, // Utilisation correcte du pseudo
        "message" => $data['message'],
        "timestamp" => new MongoDB\BSON\UTCDateTime()
    ];

    $collection->insertOne($messageData);

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["error" => "Connexion MongoDB échouée : " . $e->getMessage()]);
}
?>
