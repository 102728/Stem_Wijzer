<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partijen - StemWijzer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="partijBody">
    <?php include 'views/navbar.php'; ?>
    
    <div id="toast" class="toast"></div>
    
    <div class="partijen-container">
        <h1>Alle Partijen</h1>
        
        <?php if (isset($error)): ?>
            <div class="error-message">
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (empty($partijen)): ?>
            <div class="no-parties">
                <p>Er zijn nog geen partijen aangemaakt.</p>
            </div>
        <?php else: ?>
            <div class="partijen-grid">
                <?php foreach ($partijen as $partij): ?>
                    <div class="partij-card">
                        <?php if ($partij['bestand_data']): ?>
                            <img src="data:<?= htmlspecialchars($partij['bestand_type']) ?>;base64,<?= base64_encode($partij['bestand_data']) ?>" 
                                 alt="<?= htmlspecialchars($partij['partij_naam']) ?>" 
                                 class="partij-card-foto">
                        <?php else: ?>
                            <div class="partij-card-no-foto">Geen foto</div>
                        <?php endif; ?>
                        
                        <div class="partij-card-content">
                            <h2><?= htmlspecialchars($partij['partij_naam']) ?></h2>
                            <p class="partij-voorzitter"><strong>Voorzitter:</strong> <?= htmlspecialchars($partij['voorzitter']) ?></p>
                            <p class="partij-ideologie"><?= htmlspecialchars($partij['ideologie']) ?></p>
                            <p class="partij-themas"><strong>Themas:</strong> <?= htmlspecialchars($partij['themas']) ?></p>
                            <div class="partij-stemmen">
                                <span class="stem-count"><?= $partij['aantalStem'] ?? 0 ?> stemmen</span>
                                <?php if ($userVoorzitterNaam && $partij['voorzitter'] === $userVoorzitterNaam): ?>
                                    <a href="profiel.php" class="stem-button" style="text-decoration: none; display: inline-block; text-align: center;">Aanpassen</a>
                                <?php else: ?>
                                    <a href="partij_detail.php?id=<?= $partij['partijID'] ?>" class="stem-button" style="text-decoration: none; display: inline-block; text-align: center;">Bekijk</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="./main.js"></script>
    
    <?php if (isset($vote_success)): ?>
        <script>
            showToast('<?= addslashes($vote_success) ?>', 'success');
        </script>
    <?php endif; ?>

    <?php if (isset($vote_error)): ?>
        <script>
            showToast('<?= addslashes($vote_error) ?>', 'error');
        </script>
    <?php endif; ?>
</body>
</html>