<?php
require '../../php/config.php';

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: ../../index.php"); // Redirige si pas admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - GameVerse Hub</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/admin.css">
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
                    <li><a href="../modo/moderateur.php">🛠 Panel Modération</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION["user"]) && in_array($_SESSION["user"]["role"], ["organizer", "admin"])): ?>
                    <li><a href="../organisateur/organisateur.php">Panel Organisateur</a></li>
                <?php endif; ?>

                <!-- 🔹 Si l'utilisateur est admin, il voit aussi l'admin panel -->
                <?php if ($_SESSION["user"]["role"] === "admin"): ?>
                    <li><a href="administration.php">⚙️ Administration</a></li>
                <?php endif; ?>

            <?php else: ?>
                <li><a href="../../php/login.php">Connexion</a></li>
                <li><a href="../../php/register.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<h1>Panel Administrateur</h1>

<div class="admin-container">
    <h2>Gestion des Utilisateurs</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Pseudo</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody id="user-list">
        <!-- Liste des utilisateurs chargée dynamiquement -->
        </tbody>
    </table>
</div>

<script>
    function loadUsers() {
        fetch("get-users.php")
            .then(response => response.json())
            .then(users => {
                let userList = document.getElementById("user-list");
                userList.innerHTML = "";

                users.forEach(user => {
                    userList.innerHTML += `
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td>${user.role}</td>
                            <td>
                                <button onclick="deleteUser(${user.id})">❌ Supprimer</button>
                                <button onclick="changeRole(${user.id})">🔄 Changer Rôle</button>
                            </td>
                        </tr>`;
                });
            })
            .catch(error => console.error("Erreur chargement utilisateurs :", error));
    }

    function deleteUser(userId) {
        if (confirm("Voulez-vous vraiment supprimer cet utilisateur ?")) {
            fetch(`delete-user.php?id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    loadUsers(); // Rafraîchir la liste après suppression
                });
        }
    }

    function changeRole(userId) {
        let newRole = prompt("Entrez le nouveau rôle (user, moderator, organizer, admin) :");
        if (newRole) {
            fetch(`change-role.php?id=${userId}&role=${newRole}`)
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    loadUsers();
                });
        }
    }

    loadUsers();
</script>

<footer>
    <p>&copy; 2025 GameVerse Hub - Tous droits réservés</p>
</footer>

</body>
</html>
