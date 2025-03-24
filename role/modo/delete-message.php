<?php
require '../../vendor/autoload.php';
require '../../php/config.php';

use MongoDB\Client;

header('Content-Type: application/json');

if (!isset($_SESSION["user"]) || !in_array($_SESSION["user"]["role"], ["admin", "moderator"])) {
    echo json_encode(["error" => "Accès refusé"]);
    exit();
}

if (isset($_GET["id"])) {
    try {
        $mongo = new Client("mongodb://localhost:27017");
        $db = $mongo->gameverse_db; // Nom de ta base MongoDB
        $collection = $db->messages; // Collection des messages

        $deleteResult = $collection->deleteOne(["_id" => new MongoDB\BSON\ObjectId($_GET["id"])]);

        if ($deleteResult->getDeletedCount() > 0) {
            echo json_encode(["message" => "Message supprimé avec succès"]);
        } else {
            echo json_encode(["error" => "Aucun message trouvé avec cet ID"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Erreur MongoDB : " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "ID invalide"]);
}
?>
