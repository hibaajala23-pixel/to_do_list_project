<?php
$host = "sql111.infinityfree.com";
$dbname = "if0_42051541_taskflow";
$username = "if0_42051541";
$password = "mFMpCUNmhO4";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erreur connexion DB : " . $e->getMessage());
}
?>