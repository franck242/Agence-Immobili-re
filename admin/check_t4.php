<?php
require_once 'connexion-bdd.php';

try {
    $requete = $connexion->prepare("SELECT photos, photos2, photos3 FROM appartements WHERE categorie = 'Grand T4 Familial'");
    $requete->execute();
    $appartement = $requete->fetch(PDO::FETCH_ASSOC);
    var_dump($appartement);
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
