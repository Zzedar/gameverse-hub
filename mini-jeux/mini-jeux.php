<?php
require '../php/config.php'; // Connexion à la BDD + session_start()

if (!isset($_SESSION["user"])) {
    header("Location: ../php/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini-Jeux - GameVerse Hub</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/mini-jeux.css">
</head>
<body>
<header>
    <div class="logo">GameVerse Hub</div>
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

                <!-- 🔹 Si l'utilisateur est admin ou modérateur, il voit le panel -->
                <?php if ($_SESSION["user"]["role"] === "admin" || $_SESSION["user"]["role"] === "moderator"): ?>
                    <li><a href="../role/modo/moderateur.php">🛠 Panel Modération</a></li>
                <?php endif; ?>

                <?php if (in_array($_SESSION["user"]["role"], ["organizer", "admin"])): ?>
                    <li><a href="../role/organisateur/organisateur.php">Panel Organisateur</a></li>
                <?php endif; ?>

                <!-- 🔹 Si l'utilisateur est admin, il voit aussi l'admin panel -->
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

<h1>Mini-Jeux</h1>

<div class="games-container">
    <div class="game">
        <img src="../assets/games/pixel-runner.png" alt="Pixel Runner">
        <h3>Pixel Runner</h3>
        <p>Un jeu de course infinie où vous devez éviter les obstacles !</p>
        <a href="../mini-jeux/pixel-runner/pixel-runner.php" class="play-button">Jouer</a>
    </div>

    <div class="game">
        <img src="../assets/games/tower-defense.png" alt="Tower Defense Arena">
        <h3>Tower Defense Arena</h3>
        <p>Défendez votre base contre des vagues d’ennemis stratégiques.</p>
        <a href="../mini-jeux/tower-defense/tower-defense.php" class="play-button">Jouer</a>
    </div>

    <div class="game">
        <img src="../assets/games/card-clash.png" alt="Card Clash">
        <h3>Card Clash</h3>
        <p>Un jeu de cartes rapide où chaque tour compte !</p>
        <a href="card-clash/card-clash.php" class="play-button">Jouer</a>
    </div>

    <div class="game">
        <img src="../assets/games/word-battle.png" alt="Word Battle">
        <h3>Word Battle</h3>
        <p>Formez les mots les plus longs sous la pression du temps !</p>
        <a href="word-battle/word-battle.php" class="play-button">Jouer</a>
    </div>
</div>

<footer>
    <p>&copy; 2025 GameVerse Hub - Tous droits réservés</p>
</footer>

</body>
</html>
