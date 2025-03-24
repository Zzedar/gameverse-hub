<?php
require 'config.php';

if (!isset($_SESSION["user"])) {
    echo json_encode(["error" => "Utilisateur non connecté"]);
    exit();
}

$user_id = $_SESSION["user"]["id"];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["avatar"])) {
    $avatar_name = basename($_FILES["avatar"]["name"]);
    $upload_dir = "../uploads/";
    $upload_file = $upload_dir . $avatar_name;

    // 🔹 Déplacer le fichier uploadé
    if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $upload_file)) {
        $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
        if ($stmt->execute([$avatar_name, $user_id])) {
            $_SESSION["user"]["avatar"] = $avatar_name; // 🔹 Mise à jour de la session
            header("Location: profil.php"); // 🔹 Rediriger après succès
            exit();
        } else {
            echo json_encode(["error" => "Échec de mise à jour"]);
        }
    } else {
        echo json_encode(["error" => "Échec de l'upload"]);
    }
} else {
    echo json_encode(["error" => "Aucun fichier reçu"]);
}

?>
