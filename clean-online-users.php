<?php
require 'vendor/autoload.php';
require 'php/config.php';

use MongoDB\Client;

try {
    $mongo = new Client("mongodb://localhost:27017");
    $db = $mongo->gameverse_db;
    $collection = $db->online_users;

    // Supprimer les utilisateurs inactifs depuis plus de 60 secondes
    $timeLimit = new MongoDB\BSON\UTCDateTime((time() - 60) * 1000);
    $collection->deleteMany(["last_active" => ['$lt' => $timeLimit]]);

    echo json_encode(["success" => "Utilisateurs inactifs supprimés"]);

} catch (Exception $e) {
    echo json_encode(["error" => "Erreur MongoDB : " . $e->getMessage()]);
}
