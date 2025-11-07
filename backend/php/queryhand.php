<?php
require "conn.php";

try {
    // Drop table if it exists
    $conn->exec("DROP TABLE IF EXISTS partij_materiaal");
    
    // Create table with foreign key
    $sql = "CREATE TABLE partij_materiaal (
        materiaalID INT(11) AUTO_INCREMENT PRIMARY KEY,
        partijID INT(11) NOT NULL,
        type VARCHAR(50) NOT NULL,
        bestand_naam VARCHAR(255) NOT NULL,
        bestand_type VARCHAR(100) NOT NULL,
        bestand_data LONGBLOB NOT NULL,
        grootte INT NOT NULL,
        geupload_op DATETIME DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (partijID) REFERENCES partij(partijID) ON DELETE CASCADE,
        INDEX idx_type (type),
        INDEX idx_partij (partijID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $conn->exec($sql);
    echo "Tabel partij_materiaal succesvol aangemaakt met foreign key!";
    
} catch (PDOException $e) {
    echo "Fout: " . $e->getMessage();
}
?>