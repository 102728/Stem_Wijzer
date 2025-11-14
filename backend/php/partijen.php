<?php
session_start();
require "conn.php";

// Handle voting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['stem'])) {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['vote_error'] = "Je moet ingelogd zijn om te kunnen stemmen.";
        header("Location: partijen.php");
        exit;
    }
    
    $userID = $_SESSION['user_id'];
    $partijID = $_POST['partij_id'] ?? 0;
    
    try {
        // Get user's full name (naam + achternaam)
        $userStmt = $conn->prepare("SELECT naam, achternaam FROM gebruiker WHERE userID = ?");
        $userStmt->execute([$userID]);
        $userData = $userStmt->fetch();
        $voorzitterNaam = $userData['naam'] . ' ' . $userData['achternaam'];
        
        // Check if user is trying to vote for their own party
        $ownPartyStmt = $conn->prepare("SELECT partijID FROM partij WHERE partijID = ? AND voorzitter = ?");
        $ownPartyStmt->execute([$partijID, $voorzitterNaam]);
        
        if ($ownPartyStmt->fetch()) {
            $_SESSION['vote_error'] = "Je kunt niet op je eigen partij stemmen!";
            header("Location: partijen.php");
            exit;
        }
        
        // Increment vote count
        $updateStmt = $conn->prepare("UPDATE partij SET aantalStem = COALESCE(aantalStem, 0) + 1 WHERE partijID = ?");
        $updateStmt->execute([$partijID]);
        
        $_SESSION['vote_success'] = "Je stem is geteld!";
        
        // Redirect to prevent resubmission
        header("Location: partijen.php");
        exit;
    } catch (PDOException $e) {
        $error = "Fout bij stemmen: " . $e->getMessage();
    }
}

// Get messages
$vote_error = $_SESSION['vote_error'] ?? null;
$vote_success = $_SESSION['vote_success'] ?? null;
unset($_SESSION['vote_error'], $_SESSION['vote_success']);

// Get user's voorzitter name if logged in
$userVoorzitterNaam = null;
if (isset($_SESSION['user_id'])) {
    try {
        // Get full name (naam + achternaam)
        $userStmt = $conn->prepare("SELECT naam, achternaam FROM gebruiker WHERE userID = ?");
        $userStmt->execute([$_SESSION['user_id']]);
        $userData = $userStmt->fetch();
        if ($userData) {
            $userVoorzitterNaam = $userData['naam'] . ' ' . $userData['achternaam'];
        }
    } catch (PDOException $e) {
        // Silently fail
    }
}

// Fetch all parties with their photos
try {
    $stmt = $conn->prepare("
        SELECT 
            p.partijID,
            p.partij_naam,
            p.voorzitter,
            p.ideologie,
            p.themas,
            p.aantalStem,
            pm.bestand_data,
            pm.bestand_type
        FROM partij p
        LEFT JOIN partij_materiaal pm ON p.partijID = pm.partijID AND pm.type = 'foto'
        ORDER BY p.partijID DESC
    ");
    $stmt->execute();
    $partijen = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $partijen = [];
    $error = "Fout bij ophalen van partijen: " . $e->getMessage();
}

include("./views/partijen_view.php");