<?php
require '../vendor/autoload.php'; // Chemin mis à jour

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    echo json_encode(["success" => "Connexion MongoDB réussie"]);
} catch (Exception $e) {
    echo json_encode(["error" => "Connexion MongoDB échouée", "details" => $e->getMessage()]);
}
