<?php
require '../php/config.php';

if (!isset($_SESSION["user"])) {
    header("Location: ../php/login.php");
    exit();
}

$user_id = $_SESSION["user"]["id"];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournois - GameVerse Hub</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
    <h1>Liste des Tournois</h1>
    <div class="menu-toggle" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <nav>
        <ul>
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="../php/chat/chat.php">Chat</a></li>
            <li><a href="../tournois/tournois.php">Tournois</a></li>
            <li><a href="../mini-jeux/mini-jeux.php">Mini-Jeux</a></li>
            <li><a href="../mini-jeux/classements.php">🏆 Classements</a></li>

            <?php if (isset($_SESSION["user"])): ?>
                <li><a href="../php/profil.php">Profil</a></li>
                <li><a href="../php/logout.php">Déconnexion</a></li>

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
                <li><a href="../php/login.php">Connexion</a></li>
                <li><a href="../php/register.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<!-- ✅ Tableau affichant tous les tournois -->
<section>
    <h2>Tournois disponibles</h2>
    <table>
        <thead>
        <tr>
            <th>Nom</th>
            <th>Jeu</th>
            <th>Type</th>
            <th>Joueurs</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $tournois = $pdo->query("SELECT t.*, 
            (SELECT COUNT(*) FROM inscriptions i WHERE i.tournoi_id = t.id) AS inscrits 
            FROM tournois t ORDER BY t.date_debut DESC")->fetchAll();

        foreach ($tournois as $tournoi) {
            echo "<tr>
                    <td>{$tournoi['nom']}</td>
                    <td>{$tournoi['jeu']}</td>
                    <td>{$tournoi['type_tournoi']}</td>
                    <td>{$tournoi['inscrits']} / {$tournoi['max_joueurs']}</td>
                    <td>{$tournoi['statut']}</td>
                    <td>";

            if ($tournoi['statut'] == "ouvert" && $tournoi['inscrits'] < $tournoi['max_joueurs']) {
                echo "<form action='../role/organisateur/inscription-tournoi.php' method='POST' style='display:inline;'>
                        <input type='hidden' name='tournoi_id' value='{$tournoi["id"]}'>
                        <button type='submit'>S'inscrire</button>
                      </form>";
            }

            echo " <a href='../role/organisateur/details-tournoi.php?id={$tournoi['id']}' class='details-button'>🔍 Détails</a>
                  </td>
                 </tr>";
        }
        ?>
        </tbody>
    </table>
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
