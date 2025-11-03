<?php
$servername = "127.0.0.1";
$username = "stemwijs";
$password = "stemwijzer1";

try {
  $conn = new PDO("mysql:host=$servername;dbname=stemwijs", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}


