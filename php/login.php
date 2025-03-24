<?php
require 'config.php'; // Connexion à la BDD + session_start()

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = trim($_POST["input"]); // Peut être un pseudo ou un email
    $password = trim($_POST["password"]);

    // 🔍 Vérifier si l'utilisateur existe avec email ou pseudo
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$input, $input]);
    $user = $stmt->fetch();

    // 🔐 Vérification du mot de passe
    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user"] = [
            "id" => $user["id"],
            "username" => $user["username"],
            "email" => $user["email"],
            "role" => $user["role"]
        ];

        // ✅ Redirection après connexion
        header("Location: ../index.php");
        exit();
    } else {
        $error = "⚠ Identifiants incorrects.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../css/auth.css">
    <link rel="stylesheet" href="../css/style.css">
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

            <?php if (isset($_SESSION["user"])): ?>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
                <?php if ($_SESSION["user"]["role"] === "admin"): ?>
                    <li><a href="admin.php">Administration</a></li>
                <?php endif; ?>
            <?php else: ?>
                <li><a href="login.php">Connexion</a></li>
                <li><a href="register.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<div class="auth-container">
    <h2>Connexion</h2>
    <?php if (isset($error)) : ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="input" placeholder="Pseudo ou Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
    <p><a href="register.php">Créer un compte</a></p>
</div>

<footer>
    <p>&copy; 2025 GameVerse Hub - Tous droits réservés</p>
</footer>

</body>
</html>
