<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = parse_url(getenv("JAWSDB_URL"));

try {
    $pdo = new PDO(
        sprintf(
            "mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4",
            $db["host"],
            $db["port"],
            ltrim($db["path"], "/")
        ),
        $db["user"],
        $db["pass"]
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
