<?php
require '../../php/config.php';

if (!isset($_SESSION["user"]) || !in_array($_SESSION["user"]["role"], ["organizer", "admin"])) {
    header("Location: ../../index.php");
    exit();
}

// Suppression d'un tournoi
if (isset($_POST["delete_tournoi"])) {
    $id = intval($_POST["delete_tournoi"]);
    $stmt = $pdo->prepare("DELETE FROM tournois WHERE id = ?");
    $stmt->execute([$id]);
}

// Modification du statut d'un tournoi
if (isset($_POST["update_statut"])) {
    $id = intval($_POST["tournoi_id"]);
    $statut = $_POST["statut"];
    $stmt = $pdo->prepare("UPDATE tournois SET statut = ? WHERE id = ?");
    $stmt->execute([$statut, $id]);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Tournois - Organisateur</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

<header>
    <h1>Gestion des Tournois</h1>
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

                <?php if ($_SESSION["user"]["role"] === "admin" || $_SESSION["user"]["role"] === "moderator"): ?>
                    <li><a href="../modo/moderateur.php">🛠 Panel Modération</a></li>
                <?php endif; ?>

                <?php if (in_array($_SESSION["user"]["role"], ["organizer", "admin"])): ?>
                    <li><a href="organisateur.php">Panel Organisateur</a></li>
                <?php endif; ?>

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

<!-- 📌 Formulaire de création d'un tournoi -->
<section>
    <h2>Créer un Tournoi</h2>
    <form action="traiter-creation-tournoi.php" method="POST">
        <label for="nom">Nom du Tournoi :</label>
        <input type="text" name="nom" required>

        <label for="jeu">Choisir le Jeu :</label>
        <select name="jeu">
            <option value="Pixel Runner">Pixel Runner</option>
            <option value="Tower Defense Arena">Tower Defense Arena</option>
            <option value="Card Clash">Card Clash</option>
            <option value="Word Battle">Word Battle</option>
        </select>

        <label for="type_tournoi">Type de Tournoi :</label>
        <select name="type_tournoi">
            <option value="Élimination directe">Élimination directe</option>
            <option value="Round Robin">Round Robin</option>
            <option value="Swiss System">Swiss System</option>
        </select>

        <label for="max_joueurs">Nombre maximum de joueurs :</label>
        <input type="number" name="max_joueurs" min="2" required>

        <button type="submit">Créer Tournoi</button>
    </form>
</section>

<!-- 📌 Liste des tournois existants -->
<section>
    <h2>Tournois existants</h2>
    <table>
        <thead>
        <tr>
            <th>Nom</th>
            <th>Jeu</th>
            <th>Max Joueurs</th>
            <th>Type</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $tournois = $pdo->query("SELECT * FROM tournois ORDER BY date_debut DESC")->fetchAll();
        foreach ($tournois as $tournoi) {
            echo '<tr>
                    <td>' . htmlspecialchars($tournoi["nom"]) . '</td>
                    <td>' . htmlspecialchars($tournoi["jeu"]) . '</td>
                    <td>' . intval($tournoi["max_joueurs"]) . '</td>
                    <td>' . htmlspecialchars($tournoi["type_tournoi"]) . '</td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="tournoi_id" value="' . intval($tournoi["id"]) . '">
                            <select name="statut" onchange="this.form.submit()">
                                <option value="ouvert" ' . ($tournoi["statut"] == "ouvert" ? "selected" : "") . '>Ouvert</option>
                                <option value="en cours" ' . ($tournoi["statut"] == "en cours" ? "selected" : "") . '>En cours</option>
                                <option value="terminé" ' . ($tournoi["statut"] == "terminé" ? "selected" : "") . '>Terminé</option>
                            </select>
                            <input type="hidden" name="update_statut" value="1">
                        </form>
                    </td>
                    <td>
                        <a href="details-tournoi.php?id=' . intval($tournoi["id"]) . '" class="details-button">🔍 Détails</a> |
                        <form method="POST" style="display:inline;" onsubmit="return confirm(\'Supprimer ce tournoi ?\')">
                            <input type="hidden" name="delete_tournoi" value="' . intval($tournoi["id"]) . '">
                            <button type="submit" style="color: red;">❌ Supprimer</button>
                        </form>
                    </td>
                </tr>';
        }
        ?>
        </tbody>
    </table>
</section>

<footer>
    <p>&copy; 2025 GameVerse Hub - Tous droits réservés</p>
</footer>

</body>
</html>
