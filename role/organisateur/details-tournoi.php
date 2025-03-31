<?php
require '../../php/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: ../../php/login.php");
    exit();
}

$user = $_SESSION["user"];

// Vérifier si un ID de tournoi est fourni
if (!isset($_GET["id"])) {
    die("ID du tournoi manquant.");
}

$tournoi_id = $_GET["id"];

// 🔍 Récupérer les détails du tournoi
$stmt = $pdo->prepare("SELECT * FROM tournois WHERE id = ?");
$stmt->execute([$tournoi_id]);
$tournoi = $stmt->fetch();

if (!$tournoi) {
    die("Tournoi introuvable.");
}

// 🔍 Récupérer la liste des joueurs inscrits
$stmt = $pdo->prepare("
    SELECT u.username 
    FROM inscriptions i 
    JOIN users u ON i.joueur_id = u.id 
    WHERE i.tournoi_id = ?
");
$stmt->execute([$tournoi_id]);
$joueurs_inscrits = $stmt->fetchAll();

// Vérifier si l'utilisateur est déjà inscrit
$stmt = $pdo->prepare("SELECT * FROM inscriptions WHERE tournoi_id = ? AND joueur_id = ?");
$stmt->execute([$tournoi_id, $user["id"]]);
$deja_inscrit = $stmt->rowCount() > 0;

// 📌 Gérer l'inscription et la désinscription du tournoi
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["inscription"]) && !$deja_inscrit) {
        $stmt = $pdo->prepare("INSERT INTO inscriptions (tournoi_id, joueur_id) VALUES (?, ?)");
        $stmt->execute([$tournoi_id, $user["id"]]);
        header("Location: details-tournoi.php?id=$tournoi_id"); // Rafraîchir la page après action
        exit();
    }

    if (isset($_POST["desinscription"]) && $deja_inscrit) {
        $stmt = $pdo->prepare("DELETE FROM inscriptions WHERE tournoi_id = ? AND joueur_id = ?");
        $stmt->execute([$tournoi_id, $user["id"]]);
        header("Location: details-tournoi.php?id=$tournoi_id"); // Rafraîchir la page après action
        exit();
    }
}


// 🔍 Récupérer le match associé au tournoi et au joueur connecté
$stmt = $pdo->prepare("
    SELECT * FROM matchs 
    WHERE tournoi_id = ? 
    AND (joueur1_id = ? OR joueur2_id = ?)
");
$stmt->execute([$tournoi_id, $user["id"], $user["id"]]);
$match = $stmt->fetch();

// 🔥 Suppression du tournoi (organisateur ou admin)
if (isset($_POST["supprimer"]) && in_array($user["role"], ["organizer", "admin"])) {
    $stmt = $pdo->prepare("DELETE FROM tournois WHERE id = ?");
    $stmt->execute([$tournoi_id]);

    $stmt = $pdo->prepare("DELETE FROM inscriptions WHERE tournoi_id = ?");
    $stmt->execute([$tournoi_id]);

    header("Location: organisateur.php");
    exit();
}

// 🔄 Mise à jour du tournoi (organisateur ou admin)
if (isset($_POST["modifier"]) && in_array($user["role"], ["organizer", "admin"])) {
    $nouveau_nom = $_POST["nom"];
    $nouveau_statut = $_POST["statut"];
    $stmt = $pdo->prepare("UPDATE tournois SET nom = ?, statut = ? WHERE id = ?");
    $stmt->execute([$nouveau_nom, $nouveau_statut, $tournoi_id]);

    header("Location: details-tournoi.php?id=$tournoi_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Tournoi - <?= htmlspecialchars($tournoi["nom"]) ?></title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

<header>
    <h1>Détails du Tournoi</h1>
    <div class="menu-toggle" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <nav>
        <ul>
            <li><a href="../../index.php">Accueil</a></li>
            <li><a href="../../php/chat/chat.php">Chat</a></li>
            <li><a href="../../tournois/tournois.php">Tournois</a></li>
            <li><a href="../../mini-jeux/mini-jeux.php">Mini-Jeux</a></li>

            <?php if (isset($_SESSION["user"])): ?>
                <li><a href="../../php/profil.php">Profil</a></li>
                <li><a href="../../php/logout.php">Déconnexion</a></li>

                <?php if ($_SESSION["user"]["role"] === "admin" || $_SESSION["user"]["role"] === "moderator"): ?>
                    <li><a href="../modo/moderateur.php">🛠 Panel Modération</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION["user"]) && in_array($_SESSION["user"]["role"], ["organizer", "admin"])): ?>
                    <li><a href="../../role/organisateur/organisateur.php">Panel Organisateur</a></li>
                <?php endif; ?>

                <?php if ($_SESSION["user"]["role"] === "admin"): ?>
                    <li><a href="../admin/administration.php">⚙️ Administration</a></li>
                <?php endif; ?>

            <?php else: ?>
                <li><a href="../../php/login.php">Connexion</a></li>
                <li><a href="../../php/register.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<section>
    <h2><?= htmlspecialchars($tournoi["nom"]) ?></h2>
    <p><strong>Jeu :</strong> <?= htmlspecialchars($tournoi["jeu"]) ?></p>
    <p><strong>Type :</strong> <?= htmlspecialchars($tournoi["type_tournoi"]) ?></p>
    <p><strong>Max Joueurs :</strong> <?= htmlspecialchars($tournoi["max_joueurs"]) ?></p>
    <p><strong>Statut :</strong> <?= htmlspecialchars($tournoi["statut"]) ?></p>

    <!-- Modifier le tournoi (organisateur ou admin) -->
    <?php if (in_array($user["role"], ["organizer", "admin"])): ?>
        <form method="POST">
            <input type="text" name="nom" value="<?= htmlspecialchars($tournoi["nom"]) ?>" required>
            <select name="statut">
                <option value="ouvert" <?= $tournoi["statut"] == "ouvert" ? "selected" : "" ?>>Ouvert</option>
                <option value="en cours" <?= $tournoi["statut"] == "en cours" ? "selected" : "" ?>>En cours</option>
                <option value="terminé" <?= $tournoi["statut"] == "terminé" ? "selected" : "" ?>>Terminé</option>
            </select>
            <button type="submit" name="modifier">Modifier</button>
        </form>
    <?php endif; ?>

    <!-- 📌 Liste des joueurs inscrits -->
    <h3>Joueurs inscrits</h3>
    <ul>
        <?php foreach ($joueurs_inscrits as $joueur): ?>
            <li><?= htmlspecialchars($joueur["username"]) ?></li>
        <?php endforeach; ?>
    </ul>

    <!-- 📌 Formulaire d'inscription / désinscription -->
    <?php if ($tournoi["statut"] === "ouvert") : ?>
        <form method="POST">
            <input type="hidden" name="tournoi_id" value="<?= $tournoi_id ?>">
            <?php if (!$deja_inscrit): ?>
                <button type="submit" name="inscription">✅ S'inscrire</button>
            <?php else: ?>
                <button type="submit" name="desinscription">❌ Se désinscrire</button>
            <?php endif; ?>
        </form>
    <?php else: ?>
        <p>🚫 Les inscriptions sont fermées.</p>
    <?php endif; ?>

    <!-- 🏆 Soumettre un résultat (uniquement si un match existe) -->
    <?php if ($match): ?>
        <form method="POST" action="soumettre-resultat.php">
            <input type="hidden" name="match_id" value="<?= $match['id']; ?>">
            <label for="score">Score :</label>
            <input type="number" name="score" required>
            <button type="submit">Soumettre</button>
        </form>
    <?php else: ?>
        <p>⚠ Vous n'avez pas encore de match dans ce tournoi.</p>
    <?php endif; ?>

    <!-- 🚨 Supprimer le tournoi -->
    <?php if (in_array($user["role"], ["organizer", "admin"])): ?>
        <form method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce tournoi ?');">
            <button type="submit" name="supprimer" style="color: red;">❌ Supprimer le Tournoi</button>
        </form>
    <?php endif; ?>

    <?php if (in_array($user["role"], ["organizer", "admin"])): ?>
        <h3>Validation des matchs</h3>
        <a href="../../tournois/validation-match.php?tournoi_id=<?= $tournoi_id; ?>" class="btn">📌 Gérer les matchs</a>
    <?php endif; ?>


</section>

<footer>
    <p>&copy; 2025 GameVerse Hub - Tous droits réservés</p>
</footer>

<script>
    function toggleMenu() {
        const nav = document.querySelector("nav ul");
        nav.classList.toggle("show");
    }
</script>

</body>
</html>
