<?php
require '../../php/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"]);
    $jeu = trim($_POST["jeu"]);
    $max_joueurs = (int) $_POST["max_joueurs"];
    $type_tournoi = trim($_POST["type_tournoi"]);

    // Vérification des champs
    if (empty($nom) || empty($jeu) || $max_joueurs <= 0 || empty($type_tournoi)) {
        echo "⚠ Tous les champs doivent être remplis correctement.";
        exit();
    }

    $date_debut = date("Y-m-d H:i:s"); // 🕒 Date actuelle
    $date_fin = date("Y-m-d H:i:s", strtotime("+1 day")); // 🕒 Ajoute 1 jour automatiquement

    try {
        $stmt = $pdo->prepare("INSERT INTO tournois (nom, jeu, max_joueurs, type_tournoi, statut, date_debut, date_fin) VALUES (?, ?, ?, ?, 'ouvert', ?, ?)");
        $stmt->execute([$nom, $jeu, $max_joueurs, $type_tournoi, $date_debut, $date_fin]);

        header("Location: organisateur.php"); // ✅ Redirection après création
        exit();
    } catch (PDOException $e) {
        echo "❌ Erreur SQL : " . $e->getMessage();
    }
}

// Récupérer les joueurs inscrits à ce tournoi
$stmt = $pdo->prepare("SELECT joueur_id FROM inscriptions WHERE tournoi_id = ?");
$stmt->execute([$tournoi_id]);
$joueurs = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Vérifier s'il y a assez de joueurs pour créer des matchs
if (count($joueurs) < 2) {
    echo "⚠ Pas assez de joueurs pour créer des matchs.";
    exit();
}

// Générer des matchs en 1v1
for ($i = 0; $i < count($joueurs); $i += 2) {
    if (isset($joueurs[$i + 1])) {
        $stmt = $pdo->prepare("INSERT INTO matchs (tournoi_id, joueur1_id, joueur2_id, statut) VALUES (?, ?, ?, 'en attente')");
        $stmt->execute([$tournoi_id, $joueurs[$i], $joueurs[$i + 1]]);
    }
}

echo "✅ Matchs générés avec succès !";

?>
