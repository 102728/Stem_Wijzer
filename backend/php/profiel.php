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

$stmt = $conn->prepare("SELECT naam, achternaam, email, gemeente, gebruikersnaam FROM gebruiker WHERE userID = ?");
$stmt->execute([$user_id]);

if (!$user = $stmt->fetch()) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$formData = $_SESSION['profile_data'] ?? $user;
unset($_SESSION['profile_data']);

// Check if user has a partij
$voorzitterNaam = $user['naam'] . ' ' . $user['achternaam'];
$partijStmt = $conn->prepare("SELECT p.*, COUNT(pm.materiaalID) as heeft_foto 
                               FROM partij p 
                               LEFT JOIN partij_materiaal pm ON p.partijID = pm.partijID AND pm.type = 'foto'
                               WHERE p.voorzitter = ? 
                               GROUP BY p.partijID");
$partijStmt->execute([$voorzitterNaam]);
$userPartij = $partijStmt->fetch();

// Get partij foto if exists
$partijFoto = null;
if ($userPartij) {
    $fotoStmt = $conn->prepare("SELECT bestand_data, bestand_type FROM partij_materiaal WHERE partijID = ? AND type = 'foto' LIMIT 1");
    $fotoStmt->execute([$userPartij['partijID']]);
    $partijFoto = $fotoStmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle partij deletion
    if (isset($_POST['verwijder_partij'])) {
        $partijID = $_POST['partij_id'] ?? 0;
        $voorzitter = $user['naam'] . ' ' . $user['achternaam'];
        try {
            $conn->prepare("DELETE FROM partij WHERE partijID = ? AND voorzitter = ?")
                 ->execute([$partijID, $voorzitter]);
            $_SESSION['success'] = "Partij succesvol verwijderd!";
        } catch (PDOException $e) {
            $errors[] = "Fout bij verwijderen: " . $e->getMessage();
            $_SESSION['profile_errors'] = $errors;
        }
        header("Location: profiel.php");
        exit;
    }

    // Handle partij update
    if (isset($_POST['update_partij'])) {
        $partijID = $_POST['partij_id'] ?? 0;
        $partij_naam = trim($_POST['partij_naam'] ?? '');
        $ideologie = trim($_POST['ideologie'] ?? '');
        $themas = isset($_POST['themas']) ? implode(', ', $_POST['themas']) : '';
        $voorzitter = $user['naam'] . ' ' . $user['achternaam'];

        try {
            // Update partij info
            $conn->prepare("UPDATE partij SET partij_naam = ?, ideologie = ?, themas = ? WHERE partijID = ? AND voorzitter = ?")
                 ->execute([$partij_naam, $ideologie, $themas, $partijID, $voorzitter]);

            // Handle new foto if uploaded
            if (isset($_FILES['partij_foto']) && $_FILES['partij_foto']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['partij_foto'];
                $bestand_naam = $file['name'];
                $bestand_type = $file['type'];
                $bestand_data = file_get_contents($file['tmp_name']);
                $grootte = $file['size'];

                // Delete old foto
                $conn->prepare("DELETE FROM partij_materiaal WHERE partijID = ? AND type = 'foto'")
                     ->execute([$partijID]);

                // Insert new foto
                $conn->prepare("INSERT INTO partij_materiaal (partijID, type, bestand_naam, bestand_type, bestand_data, grootte) VALUES (?, 'foto', ?, ?, ?, ?)")
                     ->execute([$partijID, $bestand_naam, $bestand_type, $bestand_data, $grootte]);
            }

            $_SESSION['success'] = "Partij succesvol bijgewerkt!";
        } catch (PDOException $e) {
            $errors[] = "Fout bij bijwerken: " . $e->getMessage();
            $_SESSION['profile_errors'] = $errors;
        }
        header("Location: profiel.php");
        exit;
    }

    // Handle partij creation
    if (isset($_POST['maak_partij'])) {
        $partij_naam = trim($_POST['partij_naam'] ?? '');
        $ideologie = trim($_POST['ideologie'] ?? '');
        $themas = isset($_POST['themas']) ? implode(', ', $_POST['themas']) : '';
        $voorzitter = $user['naam'] . ' ' . $user['achternaam'];

        // Handle file upload
        if (isset($_FILES['partij_foto']) && $_FILES['partij_foto']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['partij_foto'];
            $bestand_naam = $file['name'];
            $bestand_type = $file['type'];
            $bestand_data = file_get_contents($file['tmp_name']);
            $grootte = $file['size'];

            try {
                // Insert partij
                $stmt = $conn->prepare("INSERT INTO partij (partij_naam, voorzitter, ideologie, themas) VALUES (?, ?, ?, ?)");
                $stmt->execute([$partij_naam, $voorzitter, $ideologie, $themas]);
                $partijID = $conn->lastInsertId();

                // Insert foto in partij_materiaal
                $stmt = $conn->prepare("INSERT INTO partij_materiaal (partijID, type, bestand_naam, bestand_type, bestand_data, grootte) VALUES (?, 'foto', ?, ?, ?, ?)");
                $stmt->execute([$partijID, $bestand_naam, $bestand_type, $bestand_data, $grootte]);

                $_SESSION['success'] = "Partij succesvol aangemaakt!";
            } catch (PDOException $e) {
                $errors[] = "Fout bij aanmaken partij: " . $e->getMessage();
            }
        } else {
            $errors[] = "Voeg een afbeelding toe voor de partij.";
        }

        if (!empty($errors)) {
            $_SESSION['profile_errors'] = $errors;
        }
        header("Location: profiel.php");
        exit;
    }

    // Handle profile update
    $formData = [
        'naam' => trim($_POST['naam'] ?? ''),
        'achternaam' => trim($_POST['achternaam'] ?? ''),
        'email' => strtolower(trim($_POST['email'] ?? '')),
        'gemeente' => trim($_POST['gemeente'] ?? '')
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
        $conn->prepare("UPDATE gebruiker SET naam = ?, achternaam = ?, email = ?, gemeente = ? WHERE userID = ?")
             ->execute([$formData['naam'], $formData['achternaam'], $formData['email'], $formData['gemeente'], $user_id]);

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
include "views/profiel_view.php";