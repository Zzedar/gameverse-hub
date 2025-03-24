<?php
require 'php/config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("SELECT username FROM users WHERE last_activity >= NOW() - INTERVAL 1 MINUTE");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($users);
} catch (Exception $e) {
    echo json_encode(["error" => "Erreur lors de la récupération des utilisateurs en ligne"]);
}
?>
