<?php
require '../php/config.php';

// Vérification de l'accès (uniquement organisateurs et admins)
if (!isset($_SESSION["user"]) || !in_array($_SESSION["user"]["role"], ["organizer", "admin"])) {
    die("❌ Accès refusé.");
}

// Vérifier les données reçues
if (!isset($_POST["match_id"])) {
    die("❌ ID du match manquant.");
}

$match_id = $_POST["match_id"];

// Récupérer les infos du match
$stmt = $pdo->prepare("SELECT * FROM matchs WHERE id = ?");
$stmt->execute([$match_id]);
$match = $stmt->fetch();

if (!$match) {
    die("❌ Match introuvable.");
}

// Action de validation ou rejet
if (isset($_POST["valider"])) {
    $stmt = $pdo->prepare("UPDATE matchs SET statut = 'validé' WHERE id = ?");
    $stmt->execute([$match_id]);
    echo "✅ Match validé !";
} elseif (isset($_POST["rejeter"])) {
    $stmt = $pdo->prepare("UPDATE matchs SET statut = 'rejeté' WHERE id = ?");
    $stmt->execute([$match_id]);
    echo "❌ Match rejeté.";
}

// Rediriger après action
header("Location: validation-match.php?tournoi_id=" . $match["tournoi_id"]);
exit();
?>
