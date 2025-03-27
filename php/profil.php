<?php
require 'config.php';

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - GameVerse Hub</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/profil.css"
</head>
<body>
<header>
    <div class="logo">GameVerse Hub</div>
    <nav>
        <ul>
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="chat/chat.php">Chat</a></li>
            <li><a href="../tournois/tournois.php">Tournois</a></li>
            <li><a href="../mini-jeux/mini-jeux.php">Mini-Jeux</a></li>
            <li><a href="../mini-jeux/classements.php">🏆 Classements</a></li>

            <?php if (isset($_SESSION["user"])): ?>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="logout.php">Déconnexion</a></li>

                <!-- 🔹 Si l'utilisateur est admin ou modérateur, il voit le panel -->
                <?php if ($_SESSION["user"]["role"] === "admin" || $_SESSION["user"]["role"] === "moderator"): ?>
                    <li><a href="../role/modo/moderateur.php">🛠 Panel Modération</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION["user"]) && in_array($_SESSION["user"]["role"], ["organizer", "admin"])): ?>
                    <li><a href="../role/organisateur/organisateur.php">Panel Organisateur</a></li>
                <?php endif; ?>

                <!-- 🔹 Si l'utilisateur est admin, il voit aussi l'admin panel -->
                <?php if ($_SESSION["user"]["role"] === "admin"): ?>
                    <li><a href="../role/admin/administration.php">⚙️ Administration</a></li>
                <?php endif; ?>

            <?php else: ?>
                <li><a href="login.php">Connexion</a></li>
                <li><a href="register.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<h1>Profil de <?php echo htmlspecialchars($user["username"]); ?></h1>

<div class="profile-container">
    <div class="avatar-section">
        <?php
        $avatar = !empty($user["avatar"]) ? $user["avatar"] : "default.png";
        ?>
        <img src="../uploads/<?php echo htmlspecialchars($avatar) . '?t=' . time(); ?>" alt="Avatar" id="avatar">

        <form action="update-avatar.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="avatar">
            <button type="submit">Changer l'avatar</button>
        </form>
    </div>

    <div class="info-section">
        <p><strong>Pseudo :</strong> <?php echo htmlspecialchars($user["username"]); ?></p>
        <p><strong>Email :</strong> <?php echo htmlspecialchars($user["email"]); ?></p>
        <p><strong>Rôle :</strong> <?php echo htmlspecialchars($user["role"]); ?></p>

        <form action="update-profile.php" method="POST">
            <input type="text" name="new_username" placeholder="Nouveau pseudo">
            <button type="submit">Mettre à jour</button>
        </form>
    </div>
</div>

<footer>
    <p>&copy; 2025 GameVerse Hub - Tous droits réservés</p>
</footer>

</body>
</html>
