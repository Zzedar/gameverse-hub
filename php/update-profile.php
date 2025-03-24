<?php
require 'config.php';

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_username = trim($_POST["new_username"]);

    if (!empty($new_username)) {
        $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->execute([$new_username, $_SESSION["user"]["id"]]);

        $_SESSION["user"]["username"] = $new_username;
        header("Location: profil.php?success=pseudo_updated");
        exit();
    }
}
?>
