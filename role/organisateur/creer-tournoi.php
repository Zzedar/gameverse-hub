<?php
require '../../php/config.php'; // Vérifie le chemin correct

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"]);
    $date_debut = $_POST["date_debut"];
    $date_fin = $_POST["date_fin"];
    $statut = $_POST["statut"];

    $stmt = $pdo->prepare("INSERT INTO tournois (nom, date_debut, date_fin, statut) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $date_debut, $date_fin, $statut]);

    // Redirection vers la page organisateur après la création
    header("Location: organisateur.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un Tournoi</title>
    <link rel="stylesheet" href="../../css/style.css">
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

            <?php if (isset($_SESSION["user"])): ?>
                <li><a href="../../php/profil.php">Profil</a></li>
                <li><a href="../../php/logout.php">Déconnexion</a></li>

                <!-- 🔹 Si l'utilisateur est admin ou modérateur, il voit le panel -->
                <?php if ($_SESSION["user"]["role"] === "admin" || $_SESSION["user"]["role"] === "moderator"): ?>
                    <li><a href="../modo/moderateur.php">🛠 Panel Modération</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION["user"]) && in_array($_SESSION["user"]["role"], ["organizer", "admin"])): ?>
                    <li><a href="organisateur.php">Panel Organisateur</a></li>
                <?php endif; ?>

                <!-- 🔹 Si l'utilisateur est admin, il voit aussi l'admin panel -->
                <?php if ($_SESSION["user"]["role"] === "admin"): ?>
                    <li><a href="../admin/administration.php">⚙️ Administration</a></li>
                <?php endif; ?>

            <?php else: ?>
                <li><a href="../../php/login.php">Connexion</a></li>
                <li><a href="../../php/register.php">Inscription</a></li>
            <?php endif; ?>
</header>



<section>
    <form action="creer-tournoi.php" method="POST">
        <label>Nom du Tournoi :</label>
        <input type="text" name="nom" required>

        <label>Date de Début :</label>
        <input type="datetime-local" name="date_debut" required>

        <label>Date de Fin :</label>
        <input type="datetime-local" name="date_fin" required>

        <label>Statut :</label>
        <select name="statut">
            <option value="ouvert">Ouvert</option>
            <option value="en cours">En cours</option>
            <option value="terminé">Terminé</option>
        </select>

        <button type="submit">Créer</button>
    </form>
</section>

<footer>
    <p>&copy; 2025 GameVerse Hub - Tous droits réservés</p>
</footer>

</body>
</html>
