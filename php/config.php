<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupérer correctement la bonne variable d’environnement
$db_url = getenv("JAWSDB_PUCE_URL") ?: $_ENV["JAWSDB_PUCE_URL"];
$db = parse_url($db_url);

try {
    $dsn = sprintf(
        "mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4",
        $db["host"],
        $db["port"] ?? 3306,
        ltrim($db["path"], "/")
    );

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
        PDO::ATTR_PERSISTENT => false
    ];

    $pdo = new PDO($dsn, $db["user"], $db["pass"], $options);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
