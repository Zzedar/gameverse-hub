<?php
require '../../vendor/autoload.php';
require '../config.php';

use MongoDB\Client;

header('Content-Type: application/json');

try {
    $mongo = new MongoDB\Client(getenv("MONGODB_URI"));
    $db = $mongo->Gameverse_db; // Base de données
    $collection = $db->messages; // Collection des messages

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
