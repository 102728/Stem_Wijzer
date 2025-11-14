<?php
session_start();
require "conn.php";

// Get partij ID from URL
$partijID = $_GET['id'] ?? 0;

// Handle removing vote
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verwijder_stem'])) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['vote_error'] = "Je moet ingelogd zijn.";
        header("Location: partij_detail.php?id=" . $partijID);
        exit;
    }
    
    $userID = $_SESSION['user_id'];
    
    try {
        // Check if user has voted on this party
        $checkStmt = $conn->prepare("SELECT stemmerID, partijID FROM stemmer WHERE userID = ?");
        $checkStmt->execute([$userID]);
        $existingVote = $checkStmt->fetch();
        
        if ($existingVote && $existingVote['partijID'] == $partijID) {
            // Remove vote from stemmer table
            $deleteStmt = $conn->prepare("DELETE FROM stemmer WHERE userID = ?");
            $deleteStmt->execute([$userID]);
            
            // Decrease vote count
            $updateStmt = $conn->prepare("UPDATE partij SET aantalStem = GREATEST(COALESCE(aantalStem, 0) - 1, 0) WHERE partijID = ?");
            $updateStmt->execute([$partijID]);
            
            $_SESSION['vote_success'] = "Je stem is verwijderd!";
        } else {
            $_SESSION['vote_error'] = "Je hebt niet op deze partij gestemd.";
        }
        
        header("Location: partij_detail.php?id=" . $partijID);
        exit;
    } catch (PDOException $e) {
        $_SESSION['vote_error'] = "Fout bij verwijderen stem: " . $e->getMessage();
        header("Location: partij_detail.php?id=" . $partijID);
        exit;
    }
}

// Handle voting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['stem'])) {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['vote_error'] = "Je moet ingelogd zijn om te kunnen stemmen.";
        header("Location: partij_detail.php?id=" . $partijID);
        exit;
    }
    
    $userID = $_SESSION['user_id'];
    
    try {
        // Get user's full name (naam + achternaam) and gemeente
        $userStmt = $conn->prepare("SELECT naam, achternaam, gemeente FROM gebruiker WHERE userID = ?");
        $userStmt->execute([$userID]);
        $userData = $userStmt->fetch();
        $voorzitterNaam = $userData['naam'] . ' ' . $userData['achternaam'];
        $userGemeente = $userData['gemeente'] ?? null;
        
        // Check if user is trying to vote for their own party
        $ownPartyStmt = $conn->prepare("SELECT partijID FROM partij WHERE partijID = ? AND voorzitter = ?");
        $ownPartyStmt->execute([$partijID, $voorzitterNaam]);
        
        if ($ownPartyStmt->fetch()) {
            $_SESSION['vote_error'] = "Je kunt niet op je eigen partij stemmen!";
            header("Location: partij_detail.php?id=" . $partijID);
            exit;
        }
        
        // Check if user has already voted
        $checkVoteStmt = $conn->prepare("SELECT stemmerID, partijID FROM stemmer WHERE userID = ?");
        $checkVoteStmt->execute([$userID]);
        $existingVote = $checkVoteStmt->fetch();
        
        if ($existingVote) {
            $_SESSION['vote_error'] = "Je hebt al gestemd! Je kunt je stem verwijderen en dan opnieuw stemmen.";
            header("Location: partij_detail.php?id=" . $partijID);
            exit;
        }
        
        // Add vote to stemmer table with gemeente
        $insertStmt = $conn->prepare("INSERT INTO stemmer (userID, partijID, gemeente, datum_plaatsing) VALUES (?, ?, ?, NOW())");
        $insertStmt->execute([$userID, $partijID, $userGemeente]);
        
        // Increment vote count
        $updateStmt = $conn->prepare("UPDATE partij SET aantalStem = COALESCE(aantalStem, 0) + 1 WHERE partijID = ?");
        $updateStmt->execute([$partijID]);
        
        $_SESSION['vote_success'] = "Je stem is geteld!";
        
        // Redirect to prevent resubmission
        header("Location: partij_detail.php?id=" . $partijID);
        exit;
    } catch (PDOException $e) {
        $_SESSION['vote_error'] = "Fout bij stemmen: " . $e->getMessage();
        header("Location: partij_detail.php?id=" . $partijID);
        exit;
    }
}

// Get messages
$vote_error = $_SESSION['vote_error'] ?? null;
$vote_success = $_SESSION['vote_success'] ?? null;
unset($_SESSION['vote_error'], $_SESSION['vote_success']);

// Get user's voorzitter name if logged in
$userVoorzitterNaam = null;
$userHasVoted = false;
$userVotedForThisParty = false;

if (isset($_SESSION['user_id'])) {
    try {
        $userStmt = $conn->prepare("SELECT naam, achternaam FROM gebruiker WHERE userID = ?");
        $userStmt->execute([$_SESSION['user_id']]);
        $userData = $userStmt->fetch();
        if ($userData) {
            $userVoorzitterNaam = $userData['naam'] . ' ' . $userData['achternaam'];
        }
        
        // Check if user has voted
        $voteCheckStmt = $conn->prepare("SELECT partijID FROM stemmer WHERE userID = ?");
        $voteCheckStmt->execute([$_SESSION['user_id']]);
        $userVote = $voteCheckStmt->fetch();
        
        if ($userVote) {
            $userHasVoted = true;
            if ($userVote['partijID'] == $partijID) {
                $userVotedForThisParty = true;
            }
        }
    } catch (PDOException $e) {
        // Silently fail
    }
}

// Check if this is user's own party
$isOwnParty = false;
if ($userVoorzitterNaam && isset($partij['voorzitter']) && $partij['voorzitter'] === $userVoorzitterNaam) {
    $isOwnParty = true;
}

// Fetch partij details
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
        WHERE p.partijID = ?
    ");
    $stmt->execute([$partijID]);
    $partij = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$partij) {
        header("Location: partijen.php");
        exit;
    }
} catch (PDOException $e) {
    $error = "Fout bij ophalen van partij: " . $e->getMessage();
    header("Location: partijen.php");
    exit;
}

include("./views/partij_detail_view.php");
