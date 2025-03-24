<?php
session_start();

// Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Récupération des infos de l'utilisateur
$user = $_SESSION["user"];
?>
