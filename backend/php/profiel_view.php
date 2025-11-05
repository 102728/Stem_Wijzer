<?php
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiel - StemWijzer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="profile-page">
    <nav id="navbar" role="navigation" aria-label="Hoofdnavigatie">
        <a class="brand" href="#">StemWijzer</a>
        <div class="nav-links">
            <a href="#">Partijen</a>
            <a href="#">Standen</a>
            <a href="profiel.php">Profiel</a>
        </div>
        <img src="scale_logo.png" alt="logo">
    </nav>
    <div class="container container--card">
        <h1>Hallo <?= $naam ?>!</h1>
        <p>Welkom op je profielpagina.</p>

        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    
        <p id="gegevens">Pas gegevens aan</p>
        <div id="changeinfo" class="form-panel">
            <form method="post" action="profiel.php" novalidate>
                <label for="naam">Naam</label>
                <input type="text" name="naam" id="naam" value="<?= htmlspecialchars($formData['naam'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

                <label for="achternaam">Achternaam</label>
                <input type="text" name="achternaam" id="achternaam" value="<?= htmlspecialchars($formData['achternaam'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

                <label for="email">E-mailadres</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($formData['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" autocomplete="email" required>

                <label for="wachtwoord">Nieuw wachtwoord</label>
                <input type="password" name="wachtwoord" id="wachtwoord" autocomplete="new-password" placeholder="Laat leeg om niet te wijzigen">

                <label for="herhaalwachtwoord">Herhaal wachtwoord</label>
                <input type="password" name="herhaalwachtwoord" id="herhaalwachtwoord" autocomplete="new-password" placeholder="Herhaal nieuw wachtwoord">

                <button type="submit">Opslaan</button>
            </form>
        </div>
    </div>
    <script src="./main.js"></script>
</body>
</html>