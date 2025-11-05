<?php
session_start();
require "conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$errors = $_SESSION['profile_errors'] ?? [];
unset($_SESSION['profile_errors']);

$stmt = $conn->prepare("SELECT naam, achternaam, email FROM gebruiker WHERE userID = ?");
$stmt->execute([$user_id]);

if (!$user = $stmt->fetch()) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$formData = $_SESSION['profile_data'] ?? $user;
unset($_SESSION['profile_data']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'naam' => trim($_POST['naam'] ?? ''),
        'achternaam' => trim($_POST['achternaam'] ?? ''),
        'email' => strtolower(trim($_POST['email'] ?? ''))
    ];
    $wachtwoord = $_POST['wachtwoord'] ?? '';
    $herhaal = $_POST['herhaalwachtwoord'] ?? '';

    // Check duplicate email
    if ($formData['email'] !== $user['email']) {
        $check = $conn->prepare("SELECT userID FROM gebruiker WHERE email = ? AND userID <> ?");
        $check->execute([$formData['email'], $user_id]);
        if ($check->fetch()) $errors[] = "Dit e-mailadres is al in gebruik.";
    }

    // Check password match
    if ($wachtwoord !== '' && $wachtwoord !== $herhaal) {
        $errors[] = "Wachtwoorden komen niet overeen.";
    }

    if (empty($errors)) {
        $conn->prepare("UPDATE gebruiker SET naam = ?, achternaam = ?, email = ? WHERE userID = ?")
             ->execute([$formData['naam'], $formData['achternaam'], $formData['email'], $user_id]);

        if ($wachtwoord !== '') {
            $conn->prepare("UPDATE gebruiker SET wachtwoord = ? WHERE userID = ?")
                 ->execute([password_hash($wachtwoord, PASSWORD_BCRYPT), $user_id]);
        }
    } else {
        $_SESSION['profile_errors'] = $errors;
        $_SESSION['profile_data'] = $formData;
    }

    header("Location: profiel.php");
    exit;
}

$naam = htmlspecialchars($formData['naam'] ?: 'Bezoeker');
include "profiel_view.php";