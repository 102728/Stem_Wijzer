<?php
session_start();
include("conn.php");

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = '';
if ($isLoggedIn) {
    $stmt = $conn->prepare("SELECT naam FROM gebruiker WHERE userID = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $userName = $user['naam'] ?? '';
}

// Haal top 5 partijen op met meeste stemmen
$stmt = $conn->prepare("SELECT partij_naam, aantalStem, ideologie FROM partij ORDER BY aantalStem DESC LIMIT 5");
$stmt->execute();
$topPartijen = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Bereken totaal aantal stemmen
$stmtTotal = $conn->prepare("SELECT COUNT(*) as totaal FROM stemmer");
$stmtTotal->execute();
$totaalStemmen = $stmtTotal->fetch(PDO::FETCH_ASSOC)['totaal'];

// Bereken totaal aantal partijen
$stmtPartijen = $conn->prepare("SELECT COUNT(*) as totaal FROM partij");
$stmtPartijen->execute();
$totaalPartijen = $stmtPartijen->fetch(PDO::FETCH_ASSOC)['totaal'];

include("views/intropage_view.php");