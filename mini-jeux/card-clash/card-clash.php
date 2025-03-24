<?php
require '../../php/config.php'; // Connexion à la BDD + session_start()

if (!isset($_SESSION["user"])) {
    header("Location: ../../php/login.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Clash</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<header>
    <h1>Card Clash 🃏🔥</h1>
    <nav>
        <ul>
            <li><a href="../../index.php">Accueil</a></li>
            <li><a href="../../php/chat/chat.php">Chat</a></li>
            <li><a href="../../tournois/tournois.php">Tournois</a></li>
            <li><a href="../mini-jeux.php">Mini-Jeux</a></li>
            <li><a href="../classements.php">🏆 Classements</a></li>

            <?php if (isset($_SESSION["user"])): ?>
                <li><a href="../../php/profil.php">Profil</a></li>
                <li><a href="../../php/logout.php">Déconnexion</a></li>

                <!-- 🔹 Si l'utilisateur est admin ou modérateur, il voit le panel -->
                <?php if ($_SESSION["user"]["role"] === "admin" || $_SESSION["user"]["role"] === "moderator"): ?>
                    <li><a href="../../role/modo/moderateur.php">🛠 Panel Modération</a></li>
                <?php endif; ?>

                <?php if (in_array($_SESSION["user"]["role"], ["organizer", "admin"])): ?>
                    <li><a href="../../role/organisateur/organisateur.php">Panel Organisateur</a></li>
                <?php endif; ?>

                <!-- 🔹 Si l'utilisateur est admin, il voit aussi l'admin panel -->
                <?php if ($_SESSION["user"]["role"] === "admin"): ?>
                    <li><a href="../../role/admin/administration.php">⚙️ Administration</a></li>
                <?php endif; ?>

            <?php else: ?>
                <li><a href="../../php/login.php">Connexion</a></li>
                <li><a href="../../php/register.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>


<div id="game-container">
    <div id="player-area">
        <h2>Votre main</h2>
        <div id="player-cards" class="card-container"></div>
    </div>

    <div id="battlefield">
        <h2>Manche <span id="round">1</span>/5</h2>
        <div id="play-area">
            <div id="player-card"></div>
            <div id="versus">VS</div>
            <div id="opponent-card"></div>
        </div>
        <p id="round-result"></p>
    </div>

    <div id="opponent-area">
        <h2>Adversaire</h2>
        <div id="opponent-cards" class="card-container"></div>
    </div>

    <button id="play-button" disabled>Jouer</button>
</div>

<p id="scoreboard">Score : Joueur <span id="player-score">0</span> - <span id="opponent-score">0</span> Adversaire</p>

<script src="game.js"></script>

<footer>
    <p>&copy; 2025 GameVerse Hub - Tous droits réservés</p>
</footer>

</body>
</html>
