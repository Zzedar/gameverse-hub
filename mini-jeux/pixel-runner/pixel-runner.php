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
    <title>Pixel Runner</title>
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        body {
            text-align: center;
            background-color: #222;
            color: white;
            font-family: Arial, sans-serif;
        }
        canvas {
            background: #ddd;
            display: block;
            margin: auto;
            border: 2px solid black;
        }
    </style>
</head>
<body>
<header>
    <h1>Pixel Runner</h1>
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

<p>Appuie sur ESPACE ou ↑ pour sauter !</p>
<canvas id="gameCanvas" width="800" height="400"></canvas>

<script src="pixel-runner.js"></script>

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
