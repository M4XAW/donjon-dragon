<?php 
    try {
        $host = "localhost";
        $user = "root";
        $password = "1Aqzsedrf:";
        $database = "jeux_donjon";

        $db = new PDO("mysql:host=$host;dbname=$database", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données";
        die();
    }
?>