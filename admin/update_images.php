<?php
require_once 'connexion-bdd.php';

try {
    // Tableau associatif des appartements avec leurs nouvelles images
    $updates = [
        'Loft Design Part-Dieu' => 'pexels-curtis-adams-1694007-11438372.jpg',
        'Appartement T3 Prestige' => 'pexels-curtis-adams-1694007-12119390.jpg',
        'Studio Design Centre' => 'pexels-heyho-6782346.jpg',
        'Penthouse Vue Fourvière' => 'pexels-curtis-adams-1694007-16501662.jpg',
        'Duplex Canut Rénové' => 'pexels-curtis-adams-1694007-4940605.jpg',
        'Loft Confluence Design' => 'pexels-heyho-6969876.jpg',
        'T5 Foch Prestige' => 'pexels-itsterrymag-2988860.jpg',
        'Studio Premium Opéra' => 'pexels-pixabay-259580.jpg'
    ];

    foreach ($updates as $categorie => $photo) {
        $stmt = $connexion->prepare("UPDATE appartements SET photos = ?, status = 0 WHERE categorie = ?");
        $stmt->execute([$photo, $categorie]);
    }

    echo "Les images des appartements ont été mises à jour avec succès.";

} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
