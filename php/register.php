<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Vérifier si l'utilisateur existe déjà
    $role = 'user'; // 🔹 Définit le rôle de base

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$username, $email, $hashedPassword, $role]))     {
        header("Location: login.php");
        exit();
    }

    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        $error = "⚠ Nom d'utilisateur ou email déjà utilisé.";
    } else {
        // Insérer l'utilisateur en base
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
        if ($stmt->execute([$username, $email, $hashedPassword])) {
            header("Location: login.php"); // Rediriger après inscription
            exit();
        } else {
            $error = "⚠ Erreur lors de l'inscription.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../css/auth.css">
    <link rel="stylesheet" href="../css/style.css">
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
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="chat/chat.php">Chat</a></li>
            <li><a href="tournois.php">Tournois</a></li>
            <li><a href="mini-jeux.php">Mini-Jeux</a></li>

            <?php if (isset($_SESSION["user"])): ?>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
                <?php if ($_SESSION["user"]["role"] === "admin"): ?>
                    <li><a href="../role/admin/administration.php">Administration</a></li>
                <?php endif; ?>
            <?php else: ?>
                <li><a href="login.php">Connexion</a></li>
                <li><a href="register.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<div class="auth-container">
    <h2>Inscription</h2>
    <?php if (isset($error)) : ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Pseudo" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">S'inscrire</button>
    </form>
    <p><a href="login.php">Déjà un compte ? Se connecter</a></p>
</div>

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
