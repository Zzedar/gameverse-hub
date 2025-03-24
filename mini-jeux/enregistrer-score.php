<?php
require '../php/config.php';

if (!isset($_SESSION["user"])) {
    die(json_encode(["error" => "Utilisateur non connecté."]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $joueur_id = $_SESSION["user"]["id"];
    $jeu = $_POST["jeu"] ?? "Pixel Runner";
    $score = $_POST["score"] ?? 0;

    if ($score > 0) {
        $stmt = $pdo->prepare("INSERT INTO scores (joueur_id, jeu, score) VALUES (?, ?, ?)");
        $stmt->execute([$joueur_id, $jeu, $score]);

        echo json_encode(["success" => "Score enregistré avec succès !"]);
    } else {
        echo json_encode(["error" => "Score invalide."]);
    }
}
?>
