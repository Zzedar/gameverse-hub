<?php
require '../../php/config.php';

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    echo json_encode(["error" => "Accès refusé"]);
    exit();
}

if (isset($_GET["id"]) && isset($_GET["role"])) {
    $allowedRoles = ["user", "moderator", "organizer", "admin"];
    if (!in_array($_GET["role"], $allowedRoles)) {
        echo json_encode(["error" => "Rôle invalide"]);
        exit();
    }

    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    if ($stmt->execute([$_GET["role"], $_GET["id"]])) {
        echo json_encode(["message" => "Rôle mis à jour avec succès"]);
    } else {
        echo json_encode(["error" => "Erreur lors de la mise à jour"]);
    }
}
?>
