<?php
require '../../vendor/autoload.php';
require '../../php/config.php';

use MongoDB\Client;
use MongoDB\BSON\ObjectId;

header('Content-Type: application/json');

// ✅ Vérifier les permissions
if (!isset($_SESSION["user"]) || !in_array($_SESSION["user"]["role"], ["admin", "moderator"])) {
    echo json_encode(["error" => "Accès refusé"]);
    exit();
}

// ✅ Lire l'ID depuis le body JSON
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data["id"])) {
    echo json_encode(["error" => "ID non fourni"]);
    exit();
}

try {
    // Connexion à MongoDB Atlas
    $client = new Client($_ENV['MONGODB_URI']);
    $db = $client->Gameverse_db;
    $collection = $db->messages;

    // Supprimer le message
    $deleteResult = $collection->deleteOne(["_id" => new ObjectId($data["id"])]);

    if ($deleteResult->getDeletedCount() > 0) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Message introuvable"]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => "Erreur MongoDB : " . $e->getMessage()]);
}
