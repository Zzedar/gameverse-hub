<?php
require 'php/config.php';

session_start();

if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["error" => "Utilisateur non connecté"]);
    exit();
}

try {
    $stmt = $pdo->prepare("UPDATE users SET last_activity = NOW() WHERE id = ?");
    $stmt->execute([$_SESSION["user"]["id"]]);

    echo json_encode(["success" => "Statut mis à jour"]);
} catch (Exception $e) {
    echo json_encode(["error" => "Impossible de mettre à jour le statut"]);
}
?>
