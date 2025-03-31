<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameVerse Hub - Accueil</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="logo">GameVerse Hub</div>
    <div class="menu-toggle" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="php/chat/chat.php">Chat</a></li>
            <li><a href="tournois/tournois.php">Tournois</a></li>
            <li><a href="mini-jeux/mini-jeux.php">Mini-Jeux</a></li>
            <li><a href="mini-jeux/classements.php">🏆 Classements</a></li>

            <?php if (isset($_SESSION["user"])): ?>
                <li><a href="php/profil.php">Profil</a></li>
                <li><a href="php/logout.php">Déconnexion</a></li>

                <!-- 🔹 Si l'utilisateur est admin ou modérateur, il voit le panel -->
                <?php if ($_SESSION["user"]["role"] === "admin" || $_SESSION["user"]["role"] === "moderator"): ?>
                    <li><a href="role/modo/moderateur.php">🛠 Panel Modération</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION["user"]) && in_array($_SESSION["user"]["role"], ["organizer", "admin"])): ?>
                    <li><a href="role/organisateur/organisateur.php">Panel Organisateur</a></li>
                <?php endif; ?>

                <!-- 🔹 Si l'utilisateur est admin, il voit aussi l'admin panel -->
                <?php if ($_SESSION["user"]["role"] === "admin"): ?>
                    <li><a href="role/admin/administration.php">⚙️ Administration</a></li>
                <?php endif; ?>

            <?php else: ?>
                <li><a href="php/login.php">Connexion</a></li>
                <li><a href="php/register.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<section class="banner">
    <h1>Bienvenue sur GameVerse Hub</h1>
    <p>La plateforme gaming ultime pour les mini-jeux et tournois en ligne !</p>
</section>

<section class="features">
    <div class="feature">
        <h2>🕹️ Mini-Jeux</h2>
        <p>Jouez à des mini-jeux multijoueurs et défiez vos amis.</p>
    </div>
    <div class="feature">
        <h2>🏆 Tournois</h2>
        <p>Participez à des tournois et remportez des récompenses.</p>
    </div>
    <div class="feature">
        <h2>💬 Chat Communautaire</h2>
        <p>Discutez avec d'autres joueurs en temps réel.</p>
    </div>
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
