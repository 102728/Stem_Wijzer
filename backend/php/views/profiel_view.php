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
            <a href="partijen.php">Partijen</a>
            <a href="#">Standen</a>
            <a href="profiel.php">Profiel</a>
        </div>
        <img src="scale_logo.png" alt="logo">
    </nav>
    <div class="container container--card">
        <img src="pfp.png" alt="img" width="80px">
        <h2>Hallo <?= $naam ?>!</h2>
        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <p id="gegevens">Pas gegevens aan</p>
        
        <?php if (!$userPartij): ?>
            <p id="partij">Maak Partij</p>
        <?php endif; ?>
        
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

        <?php if ($userPartij): ?>
            <!-- User has a partij - show partij details directly -->
            <div id="mijnPartij" class="partij-display" style="display: block;">
                <h3>Mijn Partij</h3>
                
                <?php if ($partijFoto): ?>
                    <img src="data:<?= htmlspecialchars($partijFoto['bestand_type']) ?>;base64,<?= base64_encode($partijFoto['bestand_data']) ?>" 
                         alt="Partij foto" class="partij-foto">
                <?php endif; ?>
                
                <div class="partij-info">
                    <h4><?= htmlspecialchars($userPartij['partij_naam']) ?></h4>
                    <p><strong>Ideologie:</strong> <?= htmlspecialchars($userPartij['ideologie']) ?></p>
                    <p><strong>Themas:</strong> <?= htmlspecialchars($userPartij['themas']) ?></p>
                    <p><strong>Aantal stemmen:</strong> <?= $userPartij['aantalStem'] ?? 0 ?></p>
                </div>
                
                <div class="partij-actions">
                    <button id="pasPartijAan">Partij Aanpassen</button>
                    <form method="post" style="display:inline;" onsubmit="return confirm('Weet u zeker dat u uw partij wilt verwijderen? Dit kan niet ongedaan worden gemaakt.');">
                        <input type="hidden" name="partij_id" value="<?= $userPartij['partijID'] ?>">
                        <button type="submit" name="verwijder_partij" class="delete-btn">Partij Verwijderen</button>
                    </form>
                </div>
            </div>
            
            <!-- Partij aanpassen form -->
            <div id="pasPartijAanForm" style="display: none;">
                <form method="post" action="profiel.php" enctype="multipart/form-data">
                    <h3>Pas Partij Aan</h3>
                    
                    <input type="hidden" name="partij_id" value="<?= $userPartij['partijID'] ?>">
                    
                    <label for="edit_partij_naam">Partij naam</label>
                    <input type="text" name="partij_naam" id="edit_partij_naam" value="<?= htmlspecialchars($userPartij['partij_naam']) ?>" required>

                    <label for="edit_ideologie">Beschrijf jouw ideologie</label>
                    <textarea name="ideologie" id="edit_ideologie" rows="5" required><?= htmlspecialchars($userPartij['ideologie']) ?></textarea>

                    <label for="edit_partij_foto">Nieuwe afbeelding (laat leeg om huidige te behouden)</label>
                    <input type="file" name="partij_foto" id="edit_partij_foto" accept="image/*">
                    
                    <?php if ($partijFoto): ?>
                        <div style="margin: 10px 0;">
                            <p style="font-size: 0.9rem; color: #2e2e2e;">Huidige foto:</p>
                            <img src="data:<?= htmlspecialchars($partijFoto['bestand_type']) ?>;base64,<?= base64_encode($partijFoto['bestand_data']) ?>" 
                                 alt="Huidige foto" style="max-width: 200px; border-radius: 8px;">
                        </div>
                    <?php endif; ?>
                    
                    <div id="editImagePreview" style="display: none;">
                        <p style="font-size: 0.9rem; color: #2e2e2e;">Nieuwe foto preview:</p>
                        <img id="editPreviewImg" src="" alt="Preview" style="max-width: 200px; border-radius: 8px; margin-top: 10px;">
                        <button type="button" id="removeEditImage" style="display: block; margin-top: 10px; padding: 8px 16px; background-color: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer;">Afbeelding Verwijderen</button>
                    </div>

                    <label>Selecteer themas (meerdere mogelijk)</label>
                    <div class="thema-checkboxes">
                        <?php 
                        $selectedThemas = array_map('trim', explode(',', $userPartij['themas']));
                        $allThemas = ['Onderwijs', 'Zorg', 'Klimaat', 'Economie', 'Veiligheid', 'Wonen', 'Immigratie', 'Cultuur'];
                        foreach ($allThemas as $thema): 
                            $checked = in_array($thema, $selectedThemas) ? 'checked' : '';
                        ?>
                            <label><input type="checkbox" name="themas[]" value="<?= $thema ?>" <?= $checked ?>> <?= $thema ?></label>
                        <?php endforeach; ?>
                    </div>

                    <button type="submit" name="update_partij">Opslaan</button>
                </form>
            </div>
        <?php else: ?>
            <!-- User doesn't have a partij - show create form -->
            <div id="maakPartij">
            <form method="post" action="profiel.php" enctype="multipart/form-data">
                <h3>Maak een Partij</h3>
                
                <label for="partij_naam">Partij naam</label>
                <input type="text" name="partij_naam" id="partij_naam" required>

                <label for="ideologie">Beschrijf jouw ideologie</label>
                <textarea name="ideologie" id="ideologie" rows="5" placeholder="Beschrijf de ideologie van jouw partij..." required></textarea>

                <label for="partij_foto">Voeg afbeelding toe</label>
                <input type="file" name="partij_foto" id="partij_foto" accept="image/*" required>
                
                <div id="imagePreview" style="display: none;">
                    <img id="previewImg" src="" alt="Preview" style="max-width: 300px; border-radius: 8px; margin-top: 10px;">
                    <button type="button" id="removeImage" style="display: block; margin-top: 10px; padding: 8px 16px; background-color: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer;">Afbeelding Verwijderen</button>
                </div>

                <label>Selecteer themas (meerdere mogelijk)</label>
                <div class="thema-checkboxes">
                    <label><input type="checkbox" name="themas[]" value="Onderwijs"> Onderwijs</label>
                    <label><input type="checkbox" name="themas[]" value="Zorg"> Zorg</label>
                    <label><input type="checkbox" name="themas[]" value="Klimaat"> Klimaat</label>
                    <label><input type="checkbox" name="themas[]" value="Economie"> Economie</label>
                    <label><input type="checkbox" name="themas[]" value="Veiligheid"> Veiligheid</label>
                    <label><input type="checkbox" name="themas[]" value="Wonen"> Wonen</label>
                    <label><input type="checkbox" name="themas[]" value="Immigratie"> Immigratie</label>
                    <label><input type="checkbox" name="themas[]" value="Cultuur"> Cultuur</label>
                </div>

                <button type="submit" name="maak_partij">Partij Aanmaken</button>
            </form>
        </div>
        <?php endif; ?>
    </div>
    <script src="./main.js"></script>
</body>
</html>
