<?php
require 'config.php';

if (!isset($_SESSION["user"])) {
    echo json_encode(["error" => "Utilisateur non connecté"]);
    exit();
}

require '../vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary([
    'cloud' => [
        'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
        'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
        'api_secret' => $_ENV['CLOUDINARY_API_SECRET']
    ],
    'url' => [
        'secure' => true
    ]
]);

$user_id = $_SESSION["user"]["id"];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["avatar"])) {
    $tempFile = $_FILES["avatar"]["tmp_name"];

    try {
        $result = $cloudinary->uploadApi()->upload($tempFile, [
            'folder' => 'avatars_gameverse',
            'public_id' => "user_" . $user_id,
            'overwrite' => true,
            'resource_type' => 'image'
        ]);

        $avatar_url = $result['secure_url'];

        // Mettre à jour l'avatar dans la BDD
        $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
        if ($stmt->execute([$avatar_url, $user_id])) {
            $_SESSION["user"]["avatar"] = $avatar_url;
            header("Location: profil.php");
            exit();
        } else {
            echo json_encode(["error" => "Erreur de mise à jour BDD"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Erreur Cloudinary : " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Aucun fichier reçu"]);
}
