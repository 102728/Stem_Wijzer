<?php
session_start();
include("conn.php");
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT gebruikersnaam FROM gebruiker WHERE userID = ? LIMIT 1");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$gebruikersnaam = htmlspecialchars($user['gebruikersnaam']);

include("profiel_view.php");
?>