<?php
require_once(__DIR__ . '/../includes/functions.php');

try {
    $serveur = "localhost";
    $login = "root";
    $passwordbdd = "";   
    
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
        PDO::ATTR_EMULATE_PREPARES => false
    );
    
    $connexion = new PDO("mysql:host=$serveur;dbname=agence_immobilliere;charset=utf8mb4", $login, $passwordbdd, $options);
    
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>