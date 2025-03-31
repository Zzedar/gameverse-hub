<?php
require '../php/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: ../php/login.php");
    exit();
}

// Récupérer le jeu sélectionné
$jeu_selectionne = $_GET['jeu'] ?? 'all';

// Préparation de la requête SQL
$query = "SELECT u.username, s.score, s.jeu, s.date_enregistrement 
          FROM scores s 
          JOIN users u ON s.joueur_id = u.id";

if ($jeu_selectionne !== 'all') {
    $query .= " WHERE s.jeu = ? ORDER BY s.score DESC, s.date_enregistrement ASC LIMIT 10";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$jeu_selectionne]);
} else {
    $query .= " ORDER BY s.score DESC, s.date_enregistrement ASC LIMIT 10";
    $stmt = $pdo->query($query);
}

$scores = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classements</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header>
    <h1>Classements des Mini-Jeux</h1>
    <div class="menu-toggle" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <nav>
        <ul>
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="../php/chat/chat.php">Chat</a></li>
            <li><a href="../tournois/tournois.php">Tournois</a></li>
            <li><a href="mini-jeux.php">Mini-Jeux</a></li>
            <li><a href="classements.php">🏆 Classements</a></li>

            <?php if (isset($_SESSION["user"])): ?>
                <li><a href="../php/profil.php">Profil</a></li>
                <li><a href="../php/logout.php">Déconnexion</a></li>

                <?php if ($_SESSION["user"]["role"] === "admin" || $_SESSION["user"]["role"] === "moderator"): ?>
                    <li><a href="../role/modo/moderateur.php">🛠 Panel Modération</a></li>
                <?php endif; ?>

                <?php if (in_array($_SESSION["user"]["role"], ["organizer", "admin"])): ?>
                    <li><a href="../role/organisateur/organisateur.php">Panel Organisateur</a></li>
                <?php endif; ?>

                <?php if ($_SESSION["user"]["role"] === "admin"): ?>
                    <li><a href="../role/admin/administration.php">⚙️ Administration</a></li>
                <?php endif; ?>

            <?php else: ?>
                <li><a href="../php/login.php">Connexion</a></li>
                <li><a href="../php/register.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<section>
    <h2>Meilleurs Scores</h2>
    <form method="GET" action="classements.php">
        <label for="jeu">Choisir un jeu :</label>
        <select name="jeu" id="jeu" onchange="this.form.submit()">
            <option value="all" <?= ($jeu_selectionne == 'all') ? 'selected' : '' ?>>Tous les jeux</option>
            <option value="Pixel Runner" <?= ($jeu_selectionne == 'Pixel Runner') ? 'selected' : '' ?>>Pixel Runner</option>
            <option value="Tower Defense Arena" <?= ($jeu_selectionne == 'Tower Defense Arena') ? 'selected' : '' ?>>Tower Defense Arena</option>
            <option value="Card Clash" <?= ($jeu_selectionne == 'Card Clash') ? 'selected' : '' ?>>Card Clash</option>
            <option value="Word Battle" <?= ($jeu_selectionne == 'Word Battle') ? 'selected' : '' ?>>Word Battle</option>
        </select>
    </form>

    <table>
        <thead>
        <tr>
            <th>Position</th>
            <th>Joueur</th>
            <th>Score</th>
            <th>Jeu</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        <?php $position = 1; ?>
        <?php foreach ($scores as $score): ?>
            <tr>
                <td><?= $position++; ?></td>
                <td><?= htmlspecialchars($score['username']); ?></td>
                <td><?= htmlspecialchars($score['score']); ?></td>
                <td><?= htmlspecialchars($score['jeu']); ?></td>
                <td><?= htmlspecialchars(date("d/m/Y H:i", strtotime($score['date_enregistrement']))); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
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
