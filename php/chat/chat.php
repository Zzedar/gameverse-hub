<?php
require '../config.php';


if (!isset($_SESSION["user"])) {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION["user"]["username"];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<header>
    <div class="logo">GameVerse Hub</div>
    <nav>
        <ul>
            <li><a href="../../index.php">Accueil</a></li>
            <li><a href="chat.php">Chat</a></li>
            <li><a href="../../tournois/tournois.php">Tournois</a></li>
            <li><a href="../../mini-jeux/mini-jeux.php">Mini-Jeux</a></li>
            <li><a href="../../mini-jeux/classements.php">🏆 Classements</a></li>

            <?php if (isset($_SESSION["user"])): ?>
                <li><a href="../profil.php">Profil</a></li>
                <li><a href="../logout.php">Déconnexion</a></li>

                <!-- 🔹 Si l'utilisateur est admin ou modérateur, il voit le panel -->
                <?php if ($_SESSION["user"]["role"] === "admin" || $_SESSION["user"]["role"] === "moderator"): ?>
                    <li><a href="../../role/modo/moderateur.php">🛠 Panel Modération</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION["user"]) && in_array($_SESSION["user"]["role"], ["organizer", "admin"])): ?>
                    <li><a href="../../role/organisateur/organisateur.php">Panel Organisateur</a></li>
                <?php endif; ?>

                <!-- 🔹 Si l'utilisateur est admin, il voit aussi l'admin panel -->
                <?php if ($_SESSION["user"]["role"] === "admin"): ?>
                    <li><a href="../../role/admin/administration.php">⚙️ Administration</a></li>
                <?php endif; ?>

            <?php else: ?>
                <li><a href="../login.php">Connexion</a></li>
                <li><a href="../register.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<h1>Chat en Temps Réel</h1>

<!--  Liste des utilisateurs en ligne -->
<div id="users-box">
    <h3>Utilisateurs en ligne</h3>
    <ul id="users-list"></ul>
</div>

<!--  Zone d'affichage des messages -->
<div id="chat-box"></div>


<!--  Zone de saisie du message -->
<div id="input-container">
    <input type="text" id="message-input" placeholder="Écrivez un message...">
    <select id="receiver">
        <option value="general">Général</option> <!-- Chat public -->
    </select>
    <button id="send-button">Envoyer</button>
</div>

<script>

    let sender = "<?php echo $username; ?>";

    /** 🔹 Mettre à jour l'état "En ligne" de l'utilisateur */
    function updateOnlineStatus() {
        fetch("../../update-online.php")
            .then(response => response.json())
            .then(data => console.log("✅ Mise à jour du statut :", data))
            .catch(error => console.error("⚠ Erreur mise à jour online:", error));
    }

    setInterval(updateOnlineStatus, 10000); // Mise à jour toutes les 10 secondes
    updateOnlineStatus(); // Mise à jour immédiate
</script>

<!--  Importer le fichier JS du chat -->
<script src="../../js/chat.js"></script>

<footer>
    <p>&copy; 2025 GameVerse Hub - Tous droits réservés</p>
</footer>

</body>
</html>
