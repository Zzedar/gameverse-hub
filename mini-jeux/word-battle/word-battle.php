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
    <title>Word Battle</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<header>
    <h1>Word Battle 🔤⚔️</h1>
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
    <h2>Lettres disponibles :</h2>
    <div id="letters"></div>

    <h2>Votre mot :</h2>
    <input type="text" id="player-word" placeholder="Entrez votre mot...">
    <button id="submit-word">Valider</button>

    <p id="timer">⏳ Temps restant : <span id="time">30</span>s</p>
    <p id="score">Score : <span id="player-score">0</span></p>
</div>

<script src="game.js"></script>

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
