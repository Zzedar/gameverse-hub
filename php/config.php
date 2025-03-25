<?php
<?php
$db = parse_url(getenv("JAWSDB_URL"));

try {
    $pdo = new PDO(
        sprintf(
            "mysql://a1zqt1b9x4jzccnz:miyfjqpe2l6267ii@gi6kn64hu98hy0b6.chr7pe7iynqr.eu-west-1.rds.amazonaws.com:3306/x0hlz1shwi79uhfb",
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

?>
