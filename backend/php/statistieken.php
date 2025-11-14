<?php
session_start();
include ("conn.php");

// Haal alle partijen op met hun stemmen
$stmt = $conn->prepare("SELECT partijID, partij_naam, aantalStem FROM partij ORDER BY aantalStem DESC");
$stmt->execute();
$partijen = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Bereken totaal aantal stemme
$totaalStemmen = array_sum(array_column($partijen, 'aantalStem'));

include ("views/statistieken_view.php");