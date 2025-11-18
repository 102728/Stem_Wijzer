<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StemWijzer - Jouw Stem, Jouw Keuze</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        .intro-page {
            min-height: 100vh;
            background: #1a1a1a;
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .intro-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 60px;
            background: rgba(26, 26, 26, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1000;
        }
        
        .intro-navbar .brand {
            font-size: 20px;
            font-weight: 600;
            color: #2e8b57;
            text-decoration: none;
            letter-spacing: -0.5px;
        }
        
        .intro-navbar .login-btn {
            padding: 10px 20px;
            background: #2e8b57;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .intro-navbar .login-btn:hover {
            background: #25704a;
        }
        
        .intro-navbar .profile-link {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            transition: opacity 0.2s;
        }
        
        .intro-navbar .profile-link:hover {
            opacity: 0.8;
        }
        
        .intro-navbar .profile-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #2e8b57;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 16px;
        }
        
        .hero-section {
            padding: 180px 60px 120px;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }
        
        .hero-badge {
            display: inline-block;
            padding: 6px 14px;
            background: rgba(46, 139, 87, 0.1);
            border: 1px solid rgba(46, 139, 87, 0.2);
            border-radius: 20px;
            font-size: 13px;
            color: #2e8b57;
            margin-bottom: 30px;
            font-weight: 500;
        }
        
        .hero-section h1 {
            font-size: 72px;
            font-weight: 700;
            letter-spacing: -2px;
            line-height: 1.1;
            margin-bottom: 24px;
            color: #fff;
        }
        
        .hero-section h1 .highlight {
            color: #2e8b57;
        }
        
        .hero-section p {
            font-size: 20px;
            color: #999;
            max-width: 600px;
            margin: 0 auto 40px;
            line-height: 1.6;
        }
        
        .hero-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            align-items: center;
        }
        
        .btn-primary {
            padding: 14px 28px;
            background: #2e8b57;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        
        .btn-primary:hover {
            background: #25704a;
        }
        
        .btn-secondary {
            padding: 14px 28px;
            background: transparent;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.2s;
        }
        
        .btn-secondary:hover {
            border-color: rgba(46, 139, 87, 0.5);
            background: rgba(46, 139, 87, 0.05);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            max-width: 800px;
            margin: 80px auto;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }
        
        .stat-item {
            padding: 40px;
            background: #2e2e2e;
            text-align: center;
        }
        
        .stat-item .number {
            font-size: 40px;
            font-weight: 700;
            color: #2e8b57;
            margin-bottom: 8px;
            letter-spacing: -1px;
        }
        
        .stat-item .label {
            font-size: 14px;
            color: #888;
            font-weight: 500;
        }
        
        .content-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 120px 60px;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 80px;
        }
        
        .section-header h2 {
            font-size: 48px;
            font-weight: 700;
            letter-spacing: -1px;
            margin-bottom: 16px;
        }
        
        .section-header p {
            font-size: 18px;
            color: #888;
        }
        
        .features-content {
            max-width: 800px;
            margin: 0 auto 100px;
        }
        
        .feature-block {
            margin-bottom: 60px;
            padding-bottom: 60px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .feature-block:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .feature-block h3 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #2e8b57;
        }
        
        .feature-block p {
            font-size: 18px;
            line-height: 1.8;
            color: #aaa;
        }
        
        .parties-container {
            background: #2e2e2e;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 60px;
        }
        
        .parties-header {
            text-align: center;
            margin-bottom: 48px;
        }
        
        .parties-header h3 {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -1px;
            margin-bottom: 12px;
        }
        
        .parties-header p {
            font-size: 16px;
            color: #888;
        }
        
        .party-grid {
            display: flex;
            flex-direction: column;
            gap: 1px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }
        
        .party-row {
            display: grid;
            grid-template-columns: 60px 1fr 120px;
            align-items: center;
            padding: 28px 36px;
            background: #3a3a3a;
            gap: 24px;
            transition: background 0.2s;
        }
        
        .party-row:hover {
            background: #404040;
        }
        
        .party-rank {
            font-size: 24px;
            font-weight: 700;
            color: #2e8b57;
        }
        
        .party-content h4 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #fff;
        }
        
        .party-content p {
            font-size: 14px;
            color: #888;
            line-height: 1.5;
        }
        
        .party-votes {
            text-align: right;
        }
        
        .party-votes .number {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -1px;
        }
        
        .party-votes .label {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }
        
        .cta-section {
            max-width: 700px;
            margin: 120px auto 80px;
            padding: 0 60px;
            text-align: center;
        }
        
        .cta-section h2 {
            font-size: 42px;
            font-weight: 700;
            letter-spacing: -1px;
            margin-bottom: 20px;
        }
        
        .cta-section p {
            font-size: 18px;
            color: #888;
            margin-bottom: 32px;
        }
        
        @media (max-width: 1024px) {
            .hero-section h1 {
                font-size: 48px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .intro-navbar {
                padding: 20px 30px;
            }
            
            .hero-section,
            .content-section {
                padding-left: 30px;
                padding-right: 30px;
            }
        }
    </style>
</head>
<body class="intro-page">
    <nav class="intro-navbar">
        <a class="brand" href="intropage.php">StemWijzer</a>
        <?php if ($isLoggedIn): ?>
            <a href="profiel.php" class="profile-link">
                <div class="profile-icon"><?= strtoupper(substr($userName, 0, 1)) ?></div>
            </a>
        <?php else: ?>
            <a href="index.php" class="login-btn">Inloggen</a>
        <?php endif; ?>
    </nav>
    
    <section class="hero-section">
        <h1>Leer politiek.<br>Ervaar <span class="highlight">democratie</span>.</h1>
        <p>Richt je eigen partij op, voer campagne en stem. Een realistische simulatie van het Nederlandse verkiezingssysteem.</p>
        <div class="hero-buttons">
            <?php if ($isLoggedIn): ?>
                <a href="profiel.php" class="btn-primary">Ga naar profiel</a>
            <?php else: ?>
                <a href="index.php" class="btn-primary">Begin nu</a>
            <?php endif; ?>
            <a href="#features" class="btn-secondary">Meer info</a>
        </div>
    </section>
    
    <div class="stats-grid">
        <div class="stat-item">
            <div class="number"><?= $totaalStemmen ?></div>
            <div class="label">Stemmen</div>
        </div>
        <div class="stat-item">
            <div class="number"><?= $totaalPartijen ?></div>
            <div class="label">Partijen</div>
        </div>
        <div class="stat-item">
            <div class="number">100+</div>
            <div class="label">Gebruikers</div>
        </div>
    </div>
    
    <section class="content-section" id="features">
        <div class="section-header">
            <h2>Hoe werkt het</h2>
            <p>Ervaar het volledige verkiezingsproces in drie stappen</p>
        </div>
        
        <div class="features-content">
            <div class="feature-block">
                <h3>Maak je partij</h3>
                <p>Kies een naam, schrijf je ideologie en bepaal je standpunten. Net als echte politieke partijen bouw je een programma waar anderen op kunnen stemmen. Upload campagnemateriaal en laat zien waar jouw partij voor staat.</p>
            </div>
            
            <div class="feature-block">
                <h3>Volg de statistieken</h3>
                <p>Bekijk realtime waar stemmen vandaan komen en hoe partijen scoren per gemeente. De kaart toont live welke regio's actief zijn en welke partijen populair zijn. Volg de ontwikkelingen en zie hoe jouw partij presteert.</p>
            </div>
            
            <div class="feature-block">
                <h3>Stem anoniem</h3>
                <p>Kies de partij die bij jou past. Jouw stem telt mee in de einduitslag en blijft volledig anoniem. Het systeem werkt volgens het Nederlandse proportionele kiesstelsel, net als echte verkiezingen.</p>
            </div>
        </div>
        
        <div class="parties-container">
            <div class="parties-header">
                <h3>Meest populaire partijen</h3>
                <p>De top 5 gebaseerd op aantal stemmen</p>
            </div>
            
            <div class="party-grid">
                <?php foreach ($topPartijen as $index => $partij): ?>
                    <div class="party-row">
                        <div class="party-rank">#<?= $index + 1 ?></div>
                        <div class="party-content">
                            <h4><?= htmlspecialchars($partij['partij_naam']) ?></h4>
                            <p><?= htmlspecialchars(substr($partij['ideologie'], 0, 90)) ?>...</p>
                        </div>
                        <div class="party-votes">
                            <div class="number"><?= $partij['aantalStem'] ?></div>
                            <div class="label">Stemmen</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <section class="cta-section">
        <h2>Klaar om mee te doen?</h2>
        <p>Maak een account en ervaar zelf hoe verkiezingen in Nederland werken.</p>
        <?php if ($isLoggedIn): ?>
            <a href="profiel.php" class="btn-primary">Ga naar profiel</a>
        <?php else: ?>
            <a href="index.php" class="btn-primary">Maak account</a>
        <?php endif; ?>
    </section>
</body>
</html>