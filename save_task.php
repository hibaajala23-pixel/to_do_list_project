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
$reminder_datetime = !empty($_POST['reminder_datetime']) 
    ? $_POST['reminder_datetime'] 
    : null;

$sql = "INSERT INTO tasks 
        (titre, description, priorite, date_limite, user_id, categorie_id, reminder_datetime)
        VALUES 
        (:titre, :description, :priorite, :date_limite, :user_id, :categorie_id, :reminder_datetime)";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':titre' => $titre,
    ':description' => $description,
    ':priorite' => $priorite,
    ':date_limite' => $date_limite,
    ':user_id' => $user_id,
    ':categorie_id' => $categorie_id,
    ':reminder_datetime' => $reminder_datetime
]);

header("Location: dashboard.php");
exit();
?>