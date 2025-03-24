<?php
require '../../php/config.php';

if (!isset($_SESSION["user"])) {
    header("Location: ../../php/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tournoi_id = $_POST["tournoi_id"];
    $joueur_id = $_SESSION["user"]["id"];

    // Vérifier si le joueur est déjà inscrit
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM inscriptions WHERE tournoi_id = ? AND joueur_id = ?");
    $stmt->execute([$tournoi_id, $joueur_id]);
    $isAlreadyRegistered = $stmt->fetchColumn();

    if ($isAlreadyRegistered) {
        echo "⚠ Vous êtes déjà inscrit à ce tournoi.";
        exit();
    }

// Vérifier combien de joueurs sont inscrits
    $stmt = $pdo->prepare("SELECT joueur_id FROM inscriptions WHERE tournoi_id = ?");
    $stmt->execute([$tournoi_id]);
    $joueurs = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Générer les matchs uniquement si un nombre pair de joueurs est atteint
    if (count($joueurs) >= 2) {
        for ($i = 0; $i < count($joueurs); $i += 2) {
            if (isset($joueurs[$i + 1])) {
                $stmt = $pdo->prepare("INSERT INTO matchs (tournoi_id, joueur1_id, joueur2_id, statut) VALUES (?, ?, ?, 'en attente')");
                $stmt->execute([$tournoi_id, $joueurs[$i], $joueurs[$i + 1]]);
            }
        }
    }

    // Vérifier le nombre max de joueurs
    $stmt = $pdo->prepare("SELECT max_joueurs FROM tournois WHERE id = ?");
    $stmt->execute([$tournoi_id]);
    $max_joueurs = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM inscriptions WHERE tournoi_id = ?");
    $stmt->execute([$tournoi_id]);
    $current_players = $stmt->fetchColumn();

    if ($current_players >= $max_joueurs) {
        echo "❌ Ce tournoi est complet.";
        exit();
    }

    // Inscription du joueur
    $stmt = $pdo->prepare("INSERT INTO inscriptions (tournoi_id, joueur_id) VALUES (?, ?)");
    $stmt->execute([$tournoi_id, $joueur_id]);

    header("Location: details-tournoi.php?id=" . $tournoi_id);
    exit();
}
?>
