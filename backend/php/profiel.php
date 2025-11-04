<?php
session_start();
include("conn.php");
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$errors = [];

$stmt = $conn->prepare("SELECT naam, achternaam, email FROM gebruiker WHERE userID = ? LIMIT 1");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$formData = [
    'naam' => $user['naam'] ?? '',
    'achternaam' => $user['achternaam'] ?? '',
    'email' => $user['email'] ?? '',
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if (isset($_SESSION['profile_data']) && is_array($_SESSION['profile_data'])) {
        $formData = array_merge($formData, $_SESSION['profile_data']);
        unset($_SESSION['profile_data']);
    }

    if (isset($_SESSION['profile_errors']) && is_array($_SESSION['profile_errors'])) {
        $errors = $_SESSION['profile_errors'];
        unset($_SESSION['profile_errors']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['naam'] = trim($_POST['naam'] ?? '');
    $formData['achternaam'] = trim($_POST['achternaam'] ?? '');
    $formData['email'] = strtolower(trim($_POST['email'] ?? ''));
    $wachtwoord = $_POST['wachtwoord'] ?? '';
    $herhaal = $_POST['herhaalwachtwoord'] ?? '';

    $changed = false;

    if ($formData['naam'] === '' || $formData['achternaam'] === '' || $formData['email'] === '') {
        $errors[] = "Alle velden behalve wachtwoorden zijn verplicht.";
    }

    if ($formData['naam'] !== ($user['naam'] ?? '')) {
        $changed = true;
    }
    if ($formData['achternaam'] !== ($user['achternaam'] ?? '')) {
        $changed = true;
    }
    if ($formData['email'] !== strtolower($user['email'] ?? '')) {
        $changed = true;
    }

    if ($formData['email'] !== '' && !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Ongeldig e-mailadres.";
    }

    if ($formData['email'] !== '' && strtolower($user['email'] ?? '') !== $formData['email']) {
        $check = $conn->prepare("SELECT userID FROM gebruiker WHERE email = ? AND userID <> ? LIMIT 1");
        $check->execute([$formData['email'], $user_id]);
        if ($check->fetch()) {
            $errors[] = "Dit e-mailadres is al in gebruik.";
        }
    }

    if ($wachtwoord !== '' || $herhaal !== '') {
        $changed = true;
        if ($wachtwoord !== $herhaal) {
            $errors[] = "Wachtwoorden komen niet overeen.";
        } elseif (strlen($wachtwoord) < 8) {
            $errors[] = "Wachtwoord moet minimaal 8 tekens bevatten.";
        }
    }

    if (empty($errors) && $changed) {
        try {
            $conn->beginTransaction();

            $update = $conn->prepare(
                "UPDATE gebruiker SET naam = ?, achternaam = ?, email = ? WHERE userID = ?"
            );
            $update->execute([
                $formData['naam'],
                $formData['achternaam'],
                $formData['email'],
                $user_id,
            ]);

            if ($wachtwoord !== '') {
                $hashed = password_hash($wachtwoord, PASSWORD_BCRYPT);
                $pwdUpdate = $conn->prepare("UPDATE gebruiker SET wachtwoord = ? WHERE userID = ?");
                $pwdUpdate->execute([$hashed, $user_id]);
            }

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollBack();
            $errors[] = "Bijwerken is mislukt. Probeer het later opnieuw.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['profile_errors'] = $errors;
        $_SESSION['profile_data'] = $formData;
    } else {
        unset($_SESSION['profile_errors'], $_SESSION['profile_data']);
    }

    header("Location: profiel.php");
    exit;
}

$naam = htmlspecialchars($formData['naam'] !== '' ? $formData['naam'] : 'Bezoeker', ENT_QUOTES, 'UTF-8');

include("profiel_view.php");
?>