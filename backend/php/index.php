<?php
session_start();
require "conn.php";

$_SESSION['csrf_token'] = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
$errors = [];
$success = '';

// Signup
if (isset($_POST['signsubmit'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $errors[] = "Ongeldige aanvraag.";
    } else {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $gebruikersnaam = trim($_POST['gebruikersnaam'] ?? '');
        
        $check = $conn->prepare("SELECT email, gebruikersnaam FROM gebruiker WHERE email = ? OR gebruikersnaam = ?");
        $check->execute([$email, $gebruikersnaam]);
        
        if ($existing = $check->fetch()) {
            if ($existing['email'] === $email) $errors[] = "Dit e-mailadres is al in gebruik.";
            if ($existing['gebruikersnaam'] === $gebruikersnaam) $errors[] = "Deze gebruikersnaam is al in gebruik.";
        } else {
            $stmt = $conn->prepare("INSERT INTO gebruiker (naam, achternaam, email, gemeente, gebruikersnaam, wachtwoord) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([
                trim($_POST['naam']), 
                trim($_POST['achternaam']), 
                $email,
                trim($_POST['gemeente'] ?? ''),
                $gebruikersnaam, 
                password_hash($_POST['wachtwoord'], PASSWORD_BCRYPT)
            ])) {
                $success = "Account succesvol aangemaakt!";
            }
        }
    }
}

// Login
elseif (isset($_POST['logsubmit'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $errors[] = "Ongeldige aanvraag.";
    } else {
        $stmt = $conn->prepare("SELECT userID, wachtwoord, gebruikersnaam FROM gebruiker WHERE gebruikersnaam = ?");
        $stmt->execute([trim($_POST['gNaam'] ?? '')]);
        
        if ($user = $stmt->fetch()) {
            if (password_verify($_POST['wWoord'] ?? '', $user['wachtwoord'])) {
                $_SESSION['user_id'] = $user['userID'];
                $_SESSION['username'] = $user['gebruikersnaam'];
                header("Location: profiel.php");
                exit;
            }
        }
        $errors[] = "Onjuiste gebruikersnaam of wachtwoord.";
    }
}

$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
include "views/index_view.php";