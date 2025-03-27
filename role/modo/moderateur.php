<?php
require '../../php/config.php';

// Vérification de l'accès (modérateurs et admins uniquement)
if (!isset($_SESSION["user"]) || !in_array($_SESSION["user"]["role"], ["admin", "moderator"])) {
    header("Location: ../../index.php"); // Redirige si pas modérateur ou admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modération - GameVerse Hub</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/moderateur.css">
</head>
<body>

<header>
    <div class="logo">GameVerse Hub</div>
    <nav>
        <ul>
            <li><a href="../../index.php">Accueil</a></li>
            <li><a href="../../php/chat/chat.php">Chat</a></li>
            <li><a href="../../tournois/tournois.php">Tournois</a></li>
            <li><a href="../../mini-jeux/mini-jeux.php">Mini-Jeux</a></li>
            <li><a href="../../mini-jeux/classements.php">🏆 Classements</a></li>

            <?php if (isset($_SESSION["user"])): ?>
                <li><a href="../../php/profil.php">Profil</a></li>
                <li><a href="../../php/logout.php">Déconnexion</a></li>

                <!-- 🔹 Si l'utilisateur est admin ou modérateur, il voit le panel -->
                <?php if ($_SESSION["user"]["role"] === "admin" || $_SESSION["user"]["role"] === "moderator"): ?>
                    <li><a href="moderateur.php">🛠 Panel Modération</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION["user"]) && in_array($_SESSION["user"]["role"], ["organizer", "admin"])): ?>
                    <li><a href="../organisateur/organisateur.php">Panel Organisateur</a></li>
                <?php endif; ?>

                <!-- 🔹 Si l'utilisateur est admin, il voit aussi l'admin panel -->
                <?php if ($_SESSION["user"]["role"] === "admin"): ?>
                    <li><a href="../admin/administration.php">⚙️ Administration</a></li>
                <?php endif; ?>

            <?php else: ?>
                <li><a href="../../php/login.php">Connexion</a></li>
                <li><a href="../../php/register.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<h1>Panneau de Modération</h1>

<div class="moderation-container">
    <h2>Gestion des Messages</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Utilisateur</th>
            <th>Message</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody id="message-list">
        <!-- Messages chargés dynamiquement -->
        </tbody>
    </table>

    <h2>Utilisateurs en ligne</h2>
    <ul id="online-users">
        <!-- Utilisateurs connectés chargés dynamiquement -->
    </ul>
</div>

<script>
    function loadMessages() {
        fetch("../../php/chat/get-messages.php")
            .then(response => response.json())
            .then(messages => {
                let messageList = document.getElementById("message-list");
                messageList.innerHTML = "";

                messages.forEach(msg => {
                    messageList.innerHTML += `
                        <tr>
                            <td>${msg.id}</td>
                            <td>${msg.sender}</td>
                            <td>${msg.message}</td>
                            <td><button onclick="deleteMessage('${msg.id}')">❌ Supprimer</button></td>
                        </tr>`;
                });
            })
            .catch(error => console.error("Erreur chargement messages :", error));
    }

    function deleteMessage(messageId) {
        if (!messageId) {
            console.error("⚠ Erreur : messageId est undefined !");
            return;
        }

        if (confirm("Voulez-vous vraiment supprimer ce message ?")) {
            fetch(`delete-message.php?id=${messageId}`)
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    loadMessages(); // Recharge les messages après suppression
                })
                .catch(error => console.error("Erreur suppression :", error));
        }
    }

    function loadOnlineUsers() {
        fetch("../../get-online-users.php")
            .then(response => response.json())
            .then(users => {
                let userList = document.getElementById("online-users");
                userList.innerHTML = "";

                if (!Array.isArray(users) || users.length === 0) {
                    userList.innerHTML = "<li>Aucun utilisateur en ligne</li>";
                    return;
                }

                users.forEach(user => {
                    userList.innerHTML += `<li>${user} </li>`;
                });
            })
            .catch(error => console.error("Erreur chargement utilisateurs en ligne:", error));
    }

    function updateUserActivity() {
        fetch("../../update-online.php")
            .then(response => response.json())
            .then(data => console.log("Statut utilisateur mis à jour"))
            .catch(error => console.error("Erreur mise à jour online:", error));
    }

    setInterval(loadOnlineUsers, 5000); // Rafraîchir toutes les 5 secondes
    setInterval(updateUserActivity, 10000);


    loadMessages();
    setInterval(loadMessages, 5000);
    loadOnlineUsers();
</script>

<footer>
    <p>&copy; 2025 GameVerse Hub - Tous droits réservés</p>
</footer>

</body>
</html>
