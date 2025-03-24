<?php
require '../../php/config.php';

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    echo json_encode(["error" => "Accès refusé"]);
    exit();
}

$query = $pdo->query("SELECT id, username, email, role FROM users");
$users = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($users);
?>
