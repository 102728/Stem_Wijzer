<?php
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - StemWijzer</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body class="auth-page">
    <div id="toast" class="toast"></div>
    <div class="auth-container">
        <div class="auth-header">
            <h1>StemWijzer</h1>
        </div>

        <div id="login" class="auth-form active">
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="form-group">
                    <label for="gNaam">Gebruikersnaam</label>
                    <input id="gNaam" type="text" name="gNaam" required 
                           pattern="[a-zA-Z0-9._]{3,30}" 
                           title="3-30 tekens: letters, cijfers, . of _"
                           placeholder="Voer je gebruikersnaam in">
                </div>

                <div class="form-group">
                    <label for="wWoord">Wachtwoord</label>
                    <input id="wWoord" type="password" name="wWoord" required
                           placeholder="Voer je wachtwoord in">
                </div>

                <button type="submit" class="auth-btn" name="logsubmit">Inloggen</button>
                
                <div class="auth-switch">
                    <span>Nog geen account?</span>
                    <a id="logBut" href="#">Meld je aan</a>
                </div>
            </form>
        </div>

        <div id="aanmelden" class="auth-form">
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
                <div class="form-group">
                    <label for="naam">Naam</label>
                    <input type="text" name="naam" id="naam" required 
                           pattern="[a-zA-Z\s\-]{2,50}" 
                           title="2-50 tekens: alleen letters, spatie of -"
                           placeholder="Voer je naam in">
                </div>

                <div class="form-group">
                    <label for="achternaam">Achternaam</label>
                    <input type="text" name="achternaam" id="achternaam" required 
                           pattern="[a-zA-Z\s\-]{2,50}" 
                           title="2-50 tekens: alleen letters, spatie of -"
                           placeholder="Voer je achternaam in">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required
                           placeholder="voorbeeld@email.com">
                </div>

                <div class="form-group">
                    <label for="gemeente">Gemeente</label>
                    <select name="gemeente" id="gemeente" required>
                        <option value="">Selecteer je gemeente</option>
                        <?php
                        $gemeentes = json_decode(file_get_contents('gemeentes.json'), true)['gemeentes'];
                        foreach ($gemeentes as $gemeente):
                        ?>
                            <option value="<?= htmlspecialchars($gemeente) ?>"><?= htmlspecialchars($gemeente) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="gebruikersnaam">Gebruikersnaam</label>
                    <input type="text" name="gebruikersnaam" id="gebruikersnaam" required 
                           pattern="[a-zA-Z0-9._]{3,30}" 
                           title="3-30 tekens: letters, cijfers, . of _"
                           placeholder="Kies een gebruikersnaam">
                </div>
        
                <div class="form-group">
                    <label for="wachtwoord">Wachtwoord</label>
                    <input type="password" name="wachtwoord" id="wachtwoord" required 
                           pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$" 
                           title="Min. 8 tekens: 1 hoofdletter, 1 kleine letter, 1 cijfer"
                           placeholder="Kies een sterk wachtwoord">
                </div>
                       
                <button type="submit" class="auth-btn" name="signsubmit">Account aanmaken</button>
                
                <div class="auth-switch">
                    <span>Al een account?</span>
                    <a id="signBut" href="#">Inloggen</a>
                </div>
            </form>
        </div>
    </div>

    <script src="./main.js"></script>
    
    <?php if (!empty($success)): ?>
        <script>
            showToast('<?= addslashes($success) ?>', 'success');
        </script>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <script>
            <?php foreach ($errors as $error): ?>
                showToast('<?= addslashes($error) ?>', 'error');
            <?php endforeach; ?>
        </script>
    <?php endif; ?>
</body>
</html>