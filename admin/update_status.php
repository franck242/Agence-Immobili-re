<?php
require_once 'connexion-bdd.php';

try {
    // Liste des titres des appartements à marquer comme supprimés
    $appartements = [
        'Loft Design Part-Dieu',
        'Appartement T3 Prestige',
        'Studio Design Centre',
        'Penthouse Vue Fourvière',
        'Duplex Canut Rénové',
        'Loft Confluence Design',
        'T5 Foch Prestige',
        'Studio Premium Opéra'
    ];

    // Préparer et exécuter la requête
    $sql = "UPDATE appartements SET status = 1 WHERE categorie IN (" . str_repeat('?,', count($appartements)-1) . '?)';
    $stmt = $connexion->prepare($sql);
    $stmt->execute($appartements);

    echo "Les appartements ont été marqués comme supprimés avec succès.";

} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
