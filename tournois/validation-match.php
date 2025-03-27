<?php
require '../php/config.php';

// Vérification de l'accès (uniquement organisateurs et admins)
if (!isset($_SESSION["user"]) || !in_array($_SESSION["user"]["role"], ["organizer", "admin"])) {
    die("❌ Accès refusé.");
}

$tournoi_id = $_GET["tournoi_id"] ?? null;
if (!$tournoi_id) {
    die("❌ ID du tournoi manquant.");
}

// Récupérer les matchs du tournoi en attente de validation
$stmt = $pdo->prepare("SELECT * FROM matchs WHERE tournoi_id = ? AND statut = 'en attente'");
$stmt->execute([$tournoi_id]);
$matchs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation des Matchs</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header>
    <h1>Validation des Matchs</h1>
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


<table>
    <thead>
    <tr>
        <th>Joueur 1</th>
        <th>Score 1</th>
        <th>Joueur 2</th>
        <th>Score 2</th>
        <th>Preuve</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($matchs as $match): ?>
        <tr>
            <td><?= htmlspecialchars($match["joueur1_id"]) ?></td>
            <td><?= htmlspecialchars($match["score_joueur1"] ?? "Non soumis") ?></td>
            <td><?= htmlspecialchars($match["joueur2_id"]) ?></td>
            <td><?= htmlspecialchars($match["score_joueur2"] ?? "Non soumis") ?></td>
            <td>
                <?php if ($match["preuve"]): ?>
                    <a href="../../uploads/<?= htmlspecialchars($match["preuve"]) ?>" target="_blank">📷 Voir</a>
                <?php else: ?>
                    ❌ Pas de preuve
                <?php endif; ?>
            </td>
            <td>
                <form method="POST" action="valider-match.php">
                    <input type="hidden" name="match_id" value="<?= $match['id'] ?>">
                    <button type="submit" name="valider">✅ Valider</button>
                    <button type="submit" name="rejeter" style="color: red;">❌ Rejeter</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<footer>
    <p>&copy; 2025 GameVerse Hub - Tous droits réservés</p>
</footer>

</body>
</html>
