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
<body>
    <nav id="navbar" role="navigation" aria-label="Hoofdnavigatie">
        <a class="brand" href="#">StemWijzer</a>
        <div class="nav-links">
            <a href="#">Partijen</a>
            <a href="#">Standen</a>
            <a href="profiel.php">Profiel</a>
        </div>
        <img src="scale_logo.png" alt="logo">
    </nav>

    <div class="container">
        <h1>Hallo <?= $gebruikersnaam ?>!</h1>
        <p>Welkom op je profielpagina.</p>
    </div>
</body>
</html>