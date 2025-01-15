<?php
require_once 'connexion-bdd.php';

try {
    $requete = $connexion->prepare("SELECT * FROM appartements WHERE categorie = 'Grand T4 Familial'");
    $requete->execute();
    $appartement = $requete->fetch(PDO::FETCH_ASSOC);
    
    if ($appartement) {
        echo "Image actuelle : " . $appartement['photos'];
    } else {
        echo "Appartement non trouvé";
    }
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
