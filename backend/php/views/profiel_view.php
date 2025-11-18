<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiel - StemWijzer</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-page {
            padding-top: 80px;
            min-height: 100vh;
        }
        .profile-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        .profile-header {
            background: #3a3a3a;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .profile-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }
        .profile-header-info h2 {
            color: #fff;
            margin: 0 0 5px 0;
        }
        .profile-header-info p {
            color: #aaa;
            margin: 0;
        }
        .logout-btn {
            margin-left: auto;
            padding: 8px 16px;
            background: #d32f2f;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            font-size: 14px;
        }
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #3a3a3a;
        }
        .tab {
            padding: 12px 24px;
            background: transparent;
            color: #aaa;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        .tab.active {
            color: #2e8b57;
            border-bottom-color: #2e8b57;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .content-card {
            background: #3a3a3a;
            padding: 25px;
            border-radius: 8px;
        }
        .content-card h3 {
            color: #2e8b57;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            color: #ccc;
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            background: #2e2e2e;
            border: 1px solid #555;
            border-radius: 5px;
            color: #fff;
            box-sizing: border-box;
            font-family: inherit;
        }
        .form-group textarea {
            resize: vertical;
        }
        .btn-primary {
            padding: 8px 16px;
            background: #2e8b57;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-danger {
            padding: 8px 16px;
            background: #d32f2f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .thema-checkboxes {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 15px 0;
        }
        .thema-checkboxes label {
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .thema-checkboxes input {
            width: auto;
        }
        .error-list {
            background: #d32f2f;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .partij-display {
            display: grid;
            gap: 20px;
        }
        .partij-header {
            display: flex;
            gap: 20px;
            align-items: start;
        }
        .partij-foto {
            max-width: 200px;
            border-radius: 8px;
        }
        .partij-details {
            flex: 1;
        }
        .partij-details h4 {
            color: #2e8b57;
            margin: 0 0 15px 0;
            font-size: 24px;
        }
        .partij-stat {
            background: #2e2e2e;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .partij-stat strong {
            color: #2e8b57;
            display: block;
            margin-bottom: 5px;
        }
        .partij-stat p {
            color: #ccc;
            margin: 0;
        }
        .partij-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body class="profile-page">
    <?php include 'views/navbar.php'; ?>
    
    <div class="profile-wrapper">
        <div class="profile-header">
            <img src="pfp.png" alt="Profiel">
            <div class="profile-header-info">
                <h2><?= htmlspecialchars($naam . ' ' . ($formData['achternaam'] ?? '')) ?></h2>
                <p><?= htmlspecialchars($formData['email'] ?? '') ?></p>
            </div>
            <a href="logout.php" class="logout-btn">Uitloggen</a>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="error-list">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="tabs">
            <?php if ($userPartij): ?>
                <button class="tab active" onclick="switchTab('partij')">Mijn Partij</button>
                <button class="tab" onclick="switchTab('gegevens')">Persoonlijke Gegevens</button>
            <?php else: ?>
                <button class="tab active" onclick="switchTab('gegevens')">Persoonlijke Gegevens</button>
                <button class="tab" onclick="switchTab('maakpartij')">Maak Partij</button>
            <?php endif; ?>
        </div>
        
        <?php if ($userPartij): ?>
            <div id="partij-tab" class="tab-content active">
                <div class="content-card">
                    <div class="partij-display">
                        <div class="partij-header">
                            <?php if ($partijFoto): ?>
                                <img src="data:<?= htmlspecialchars($partijFoto['bestand_type']) ?>;base64,<?= base64_encode($partijFoto['bestand_data']) ?>" 
                                     alt="Partij foto" class="partij-foto">
                            <?php endif; ?>
                            <div class="partij-details">
                                <h4><?= htmlspecialchars($userPartij['partij_naam']) ?></h4>
                                
                                <div class="partij-stat">
                                    <strong>Ideologie</strong>
                                    <p><?= htmlspecialchars($userPartij['ideologie']) ?></p>
                                </div>
                                
                                <div class="partij-stat">
                                    <strong>Themas</strong>
                                    <p><?= htmlspecialchars($userPartij['themas']) ?></p>
                                </div>
                                
                                <div class="partij-stat">
                                    <strong>Aantal Stemmen</strong>
                                    <p><?= $userPartij['aantalStem'] ?? 0 ?> stemmen</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="partij-actions">
                            <button class="btn-primary" onclick="document.getElementById('editForm').style.display='block'; this.parentElement.style.display='none';">
                                Partij Bewerken
                            </button>
                            <form method="post" style="display:inline;" onsubmit="return confirm('Weet je zeker dat je je partij wilt verwijderen?');">
                                <input type="hidden" name="partij_id" value="<?= $userPartij['partijID'] ?>">
                                <button type="submit" name="verwijder_partij" class="btn-danger">Verwijderen</button>
                            </form>
                        </div>
                        
                        <div id="editForm" style="display: none;">
                            <h3 style="color: #2e8b57; margin-top: 30px;">Partij Bewerken</h3>
                            <form method="post" action="profiel.php" enctype="multipart/form-data">
                                <input type="hidden" name="partij_id" value="<?= $userPartij['partijID'] ?>">
                                
                                <div class="form-group">
                                    <label>Partij naam</label>
                                    <input type="text" name="partij_naam" value="<?= htmlspecialchars($userPartij['partij_naam']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Ideologie</label>
                                    <textarea name="ideologie" rows="4" required><?= htmlspecialchars($userPartij['ideologie']) ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Nieuwe foto (optioneel)</label>
                                    <input type="file" name="partij_foto" accept="image/*">
                                </div>
                                
                                <div class="form-group">
                                    <label>Themas</label>
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
                                </div>

                                <button type="submit" name="update_partij" class="btn-primary">Opslaan</button>
                                <button type="button" class="btn-danger" onclick="document.getElementById('editForm').style.display='none'; document.querySelector('.partij-actions').style.display='flex';">
                                    Annuleren
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div id="gegevens-tab" class="tab-content <?= !$userPartij ? 'active' : '' ?>">
            <div class="content-card">
                <h3>Persoonlijke Gegevens</h3>
                <form method="post" action="profiel.php" novalidate>
                    <div class="form-group">
                        <label for="naam">Naam</label>
                        <input type="text" name="naam" id="naam" value="<?= htmlspecialchars($formData['naam'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="achternaam">Achternaam</label>
                        <input type="text" name="achternaam" id="achternaam" value="<?= htmlspecialchars($formData['achternaam'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">E-mailadres</label>
                        <input type="email" name="email" id="email" value="<?= htmlspecialchars($formData['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="gemeente">Gemeente</label>
                        <select name="gemeente" id="gemeente" required>
                            <option value="">Selecteer je gemeente</option>
                            <?php
                            $gemeentes = json_decode(file_get_contents('gemeentes.json'), true)['gemeentes'];
                            $currentGemeente = $formData['gemeente'] ?? '';
                            foreach ($gemeentes as $gemeente):
                                $selected = ($gemeente === $currentGemeente) ? 'selected' : '';
                            ?>
                                <option value="<?= htmlspecialchars($gemeente) ?>" <?= $selected ?>><?= htmlspecialchars($gemeente) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="wachtwoord">Nieuw wachtwoord (optioneel)</label>
                        <input type="password" name="wachtwoord" id="wachtwoord" placeholder="Laat leeg om niet te wijzigen">
                    </div>

                    <div class="form-group">
                        <label for="herhaalwachtwoord">Herhaal wachtwoord</label>
                        <input type="password" name="herhaalwachtwoord" id="herhaalwachtwoord" placeholder="Herhaal nieuw wachtwoord">
                    </div>

                    <button type="submit" class="btn-primary">Opslaan</button>
                </form>
            </div>
        </div>
        
        <?php if (!$userPartij): ?>
            <div id="maakpartij-tab" class="tab-content">
                <div class="content-card">
                    <h3>Maak een Partij</h3>
                    <form method="post" action="profiel.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="partij_naam">Partij naam</label>
                            <input type="text" name="partij_naam" id="partij_naam" required>
                        </div>

                        <div class="form-group">
                            <label for="ideologie">Ideologie</label>
                            <textarea name="ideologie" id="ideologie" rows="5" placeholder="Beschrijf de ideologie van jouw partij..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="partij_foto">Partij foto</label>
                            <input type="file" name="partij_foto" id="partij_foto" accept="image/*" required>
                        </div>
                        
                        <div id="imagePreview" style="display: none; margin: 10px 0;">
                            <img id="previewImg" src="" alt="Preview" style="max-width: 200px; border-radius: 8px;">
                        </div>

                        <div class="form-group">
                            <label>Selecteer themas</label>
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
                        </div>

                        <button type="submit" name="maak_partij" class="btn-primary">Partij Aanmaken</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');
            event.target.classList.add('active');
        }
    </script>
    <script src="./main.js"></script>
</body>
</html>