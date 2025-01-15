<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("admin/connexion-bdd.php");

try {
    $requete = $connexion->prepare("SELECT Id_appartements, photos FROM appartements");
    $requete->execute();
    $appartements = $requete->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h1>Test de la base de données</h1>";
    echo "<h2>Contenu de la table appartements :</h2>";
    echo "<pre>";
    print_r($appartements);
    echo "</pre>";
    
    echo "<h2>Vérification des images :</h2>";
    foreach ($appartements as $appartement) {
        $imagePath = __DIR__ . '/img/' . $appartement['photos'];
        echo "ID: " . $appartement['Id_appartements'] . "<br>";
        echo "Nom de l'image: " . $appartement['photos'] . "<br>";
        echo "Chemin complet: " . $imagePath . "<br>";
        echo "L'image existe ? : " . (file_exists($imagePath) ? 'Oui' : 'Non') . "<br>";
        echo "<hr>";
    }
    
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
