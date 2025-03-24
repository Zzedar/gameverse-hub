<?php
require '../../php/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    die("❌ Erreur : Vous devez être connecté.");
}

$user_id = $_SESSION["user"]["id"];

// Vérifier que les données ont bien été envoyées
if (!isset($_POST["match_id"], $_POST["score"])) {
    die("❌ Données manquantes.");
}

$match_id = $_POST["match_id"];
$score = intval($_POST["score"]); // Convertir en nombre entier

// Vérifier si le match existe et que l'utilisateur y participe
$stmt = $pdo->prepare("
    SELECT * FROM matchs 
    WHERE id = ? AND (joueur1_id = ? OR joueur2_id = ?)
");
$stmt->execute([$match_id, $user_id, $user_id]);
$match = $stmt->fetch();

if (!$match) {
    die("❌ Le match n'existe pas ou vous n'y participez pas.");
}

// Déterminer si l'utilisateur est joueur1 ou joueur2
if ($match["joueur1_id"] == $user_id) {
    $column = "score_joueur1";
} elseif ($match["joueur2_id"] == $user_id) {
    $column = "score_joueur2";
} else {
    die("❌ Vous ne faites pas partie de ce match !");
}

// Mettre à jour le score
$stmt = $pdo->prepare("UPDATE matchs SET $column = ? WHERE id = ?");
$stmt->execute([$score, $match_id]);

echo "✅ Score soumis avec succès !";
header("Location: details-tournoi.php?id=" . $match["tournoi_id"]);
exit();
?>