<?php
require '../../php/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tournoi_id = $_POST["tournoi_id"];
    $nom = trim($_POST["nom"]);
    $jeu = trim($_POST["jeu"]);
    $max_joueurs = (int) $_POST["max_joueurs"];
    $type_tournoi = trim($_POST["type_tournoi"]);

    if (empty($nom) || empty($jeu) || $max_joueurs <= 0 || empty($type_tournoi)) {
        echo "⚠ Tous les champs doivent être remplis correctement.";
        exit();
    }

    try {
        $stmt = $pdo->prepare("UPDATE tournois SET nom = ?, jeu = ?, max_joueurs = ?, type_tournoi = ? WHERE id = ?");
        $stmt->execute([$nom, $jeu, $max_joueurs, $type_tournoi, $tournoi_id]);

        header("Location: details-tournoi.php?id=" . $tournoi_id);
        exit();
    } catch (PDOException $e) {
        echo "❌ Erreur SQL : " . $e->getMessage();
    }
}
?>
