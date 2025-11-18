<?php
session_start();
include ("conn.php");

// Haal alle partijen op met hun stemmen
$stmt = $conn->prepare("SELECT partijID, partij_naam, aantalStem, ideologie FROM partij ORDER BY aantalStem DESC");
$stmt->execute();
$partijen = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Bereken totaal aantal stemme
$totaalStemmen = array_sum(array_column($partijen, 'aantalStem'));

// Top 3 partijen
$topPartijen = array_slice($partijen, 0, 3);

// Actieve gemeentes (met stemmen)
$stmtActieveGemeentes = $conn->prepare("SELECT COUNT(DISTINCT gemeente) as aantal FROM stemmer");
$stmtActieveGemeentes->execute();
$actieveGemeentes = $stmtActieveGemeentes->fetch(PDO::FETCH_ASSOC)['aantal'];

// Haal stemmen per gemeente op
$stmtGemeente = $conn->prepare("SELECT gemeente, COUNT(*) as aantalStemmen FROM stemmer GROUP BY gemeente ORDER BY aantalStemmen DESC");
$stmtGemeente->execute();
$gemeenteStemmen = $stmtGemeente->fetchAll(PDO::FETCH_ASSOC);

// Top gemeente
$topGemeente = $gemeenteStemmen[0] ?? null;

// Haal stemmen per partij per gemeente op
$stmtPartijGemeente = $conn->prepare("
    SELECT p.partij_naam, s.gemeente, COUNT(*) as aantalStemmen 
    FROM stemmer s 
    JOIN partij p ON s.partijID = p.partijID 
    GROUP BY p.partij_naam, s.gemeente
");
$stmtPartijGemeente->execute();
$partijGemeenteStemmen = $stmtPartijGemeente->fetchAll(PDO::FETCH_ASSOC);

include ("views/statistieken_view.php");