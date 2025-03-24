<?php
require '../../php/config.php';

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    echo json_encode(["error" => "Accès refusé"]);
    exit();
}

if (isset($_GET["id"])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$_GET["id"]])) {
        echo json_encode(["message" => "Utilisateur supprimé avec succès"]);
    } else {
        echo json_encode(["error" => "Erreur lors de la suppression"]);
    }
}
?>
