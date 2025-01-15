<?php
require_once 'connexion-bdd.php';

try {
    $requete = $connexion->query("SELECT photos FROM appartements");
    $images = $requete->fetchAll(PDO::FETCH_COLUMN);
    echo "Images utilisées : \n";
    print_r($images);
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
