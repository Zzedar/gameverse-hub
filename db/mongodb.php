<?php
require __DIR__ . '/../vendor/autoload.php'; // Charger MongoDB via Composer

try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $database = $mongoClient->gameverse_db; // Connexion à la base de données
} catch (Exception $e) {
    die("Erreur de connexion à MongoDB : " . $e->getMessage());
}
?>
