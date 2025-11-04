<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>stemwijs</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body class="auth-page">
    <?php if (!empty($success)): ?>
        <div class="success-message"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error-message">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <nav id="navbar" role="navigation" aria-label="Main navigation">
        <a class="brand" href="./partijen.html">StemWijzer</a>
        <div class="nav-links">
            <a href="./partijen.html">Partijen</a>
            <a href="./standen.html">Standen</a>
        </div>
        <img src="scale_logo.png" alt="logo">
    </nav>

    <div id="forms">
        <div id="login" class="form-panel active">
            <form method="post" novalidate>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="form-group">
                    <label for="gNaam">Gebruikernaam:</label>
                    <input id="gNaam" type="text" name="gNaam" required 
                           pattern="[a-zA-Z0-9._]{3,30}" 
                           title="3-30 tekens: letters, cijfers, . of _">
                </div>

                <div class="form-group">
                    <label for="wWoord">Wachtwoord:</label>
                    <input id="wWoord" type="password" name="wWoord" required>
                    <button type="submit" class="inbu" name="logsubmit">Inloggen</button>
                </div>
                <a id="logBut" href="#">Nog geen account? Meld je aan</a>
            </form>
        </div>

        <div id="aanmelden" class="form-panel">
            <form method="post" novalidate>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
                <label for="naam">Naam</label>
                <input type="text" name="naam" id="naam" required 
                       pattern="[a-zA-Z\s\-]{2,50}" 
                       title="2-50 tekens: alleen letters, spatie of -">

                <label for="achternaam">Achternaam</label>
                <input type="text" name="achternaam" id="achternaam" required 
                       pattern="[a-zA-Z\s\-]{2,50}" 
                       title="2-50 tekens: alleen letters, spatie of -">

                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>

                <label for="gebruikersnaam">Gebruikersnaam</label>
                <input type="text" name="gebruikersnaam" id="gebruikersnaam" required 
                       pattern="[a-zA-Z0-9._]{3,30}" 
                       title="3-30 tekens: letters, cijfers, . of _">
        
                <label for="wachtwoord">Wachtwoord</label>
                <input type="password" name="wachtwoord" id="wachtwoord" required 
                       pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$" 
                       title="Min. 8 tekens: 1 hoofdletter, 1 kleine letter, 1 cijfer">

                <button type="submit" class="inbu" name="signsubmit">Aanmelden</button>
                <a id="signBut" href="#">Inloggen</a>
            </form>
        </div>
    </div>

    <script src="./main.js"></script>
</body>
</html>