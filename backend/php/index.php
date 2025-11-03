<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("conn.php"); 

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
$success = '';

if (isset($_POST['signsubmit'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = "Ongeldige aanvraag (CSRF).";
    } else {
        $naam           = trim($_POST['naam'] ?? '');
        $achternaam     = trim($_POST['achternaam'] ?? '');
        $email          = strtolower(trim($_POST['email'] ?? ''));
        $gebruikersnaam = trim($_POST['gebruikersnaam'] ?? '');
        $wachtwoord     = $_POST['wachtwoord'] ?? '';
        if (empty($naam) || empty($achternaam) || empty($email) || empty($gebruikersnaam) || empty($wachtwoord)) {
            $errors[] = "Alle velden zijn verplicht.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Ongeldig e-mailadres.";
        } else {
            $check = $conn->prepare("SELECT userID FROM gebruiker WHERE email = ? OR gebruikersnaam = ?");
            $check->execute([$email, $gebruikersnaam]);
            if ($check->rowCount() > 0) {
                $row = $check->fetch();
                $dup = $conn->prepare("SELECT email, gebruikersnaam FROM gebruiker WHERE userID = ?");
                $dup->execute([$row['userID']]);
                $existing = $dup->fetch();
                if ($existing['email'] === $email) $errors[] = "Dit e-mailadres is al in gebruik.";
                if ($existing['gebruikersnaam'] === $gebruikersnaam) $errors[] = "Deze gebruikersnaam is al in gebruik.";
            }

            if (empty($errors)) {
                $hashed = password_hash($wachtwoord, PASSWORD_BCRYPT);
                $sql = "INSERT INTO gebruiker (naam, achternaam, email, gebruikersnaam, wachtwoord) 
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if ($stmt->execute([$naam, $achternaam, $email, $gebruikersnaam, $hashed])) {
                    $success = "Account succesvol aangemaakt! Je kunt nu inloggen.";
                } else {
                    $errors[] = "Er ging iets mis. Probeer opnieuw.";
                }
            }
        }
    }
}

elseif (isset($_POST['logsubmit'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = "Ongeldige aanvraag (CSRF).";
    } else {
        $gebruikersnaam = trim($_POST['gNaam'] ?? '');
        $wachtwoord     = $_POST['wWoord'] ?? '';

        if (empty($gebruikersnaam) || empty($wachtwoord)) {
            $errors[] = "Vul gebruikersnaam en wachtwoord in.";
        } else {
            $stmt = $conn->prepare("SELECT userID, wachtwoord, gebruikersnaam FROM gebruiker WHERE gebruikersnaam = ? LIMIT 1");
            $stmt->execute([$gebruikersnaam]);
            $user = $stmt->fetch();

            if ($user && password_verify($wachtwoord, $user['wachtwoord'])) {
                $_SESSION['user_id'] = $user['userID'];
                $_SESSION['username'] = $user['gebruikersnaam'];
                header("Location: profiel.php");
                exit;
            } else {
                $errors[] = "Onjuiste gebruikersnaam of wachtwoord.";
            }
        }
    }
}
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
include("index_view.php");
?>