<?php
require_once 'connexion-bdd.php';

try {
    $requete = $connexion->prepare("UPDATE appartements SET photos = 'pexels-john-tekeridis-1428348.jpg' WHERE categorie = 'Grand T4 Familial'");
    if($requete->execute()) {
        echo "Image mise à jour avec succès";
    } else {
        echo "Échec de la mise à jour";
    }
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
