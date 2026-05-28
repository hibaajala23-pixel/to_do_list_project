<?php
session_start();
require "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$titre = $_POST['titre'];
$description = $_POST['description'];
$priorite = $_POST['priorite'];
$date_limite = $_POST['date_limite'];
$categorie_id = $_POST['categorie_id'];
$user_id = $_SESSION['user_id'];

$sql = "INSERT INTO tasks 
        (titre, description, priorite, date_limite, user_id, categorie_id)
        VALUES 
        (:titre, :description, :priorite, :date_limite, :user_id, :categorie_id)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':titre' => $titre,
    ':description' => $description,
    ':priorite' => $priorite,
    ':date_limite' => $date_limite,
    ':user_id' => $user_id,
    ':categorie_id' => $categorie_id
]);

header("Location: dashboard.php");
exit();
?>