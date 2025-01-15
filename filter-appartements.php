<?php
require("admin/connexion-bdd.php");

// Récupération des paramètres de recherche
$prix_max = isset($_GET['prix_max']) && !empty($_GET['prix_max']) ? intval($_GET['prix_max']) : null;
$surface_min = isset($_GET['surface_min']) && !empty($_GET['surface_min']) ? intval($_GET['surface_min']) : null;
$type = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : null;
$quartier = isset($_GET['quartier']) && !empty($_GET['quartier']) ? $_GET['quartier'] : null;

// Construction de la requête SQL de base
$sql = "SELECT * FROM appartements WHERE status = 0";
$params = array();

// Ajout des conditions de filtrage
if ($prix_max) {
    $sql .= " AND prix <= :prix_max";
    $params[':prix_max'] = $prix_max;
}

if ($surface_min) {
    $sql .= " AND superficie >= :surface_min";
    $params[':surface_min'] = $surface_min;
}

if ($type) {
    $sql .= " AND categorie = :type";
    $params[':type'] = $type;
}

if ($quartier) {
    $sql .= " AND quartier = :quartier";
    $params[':quartier'] = $quartier;
}

try {
    $requete = $connexion->prepare($sql);
    $requete->execute($params);
    $appartements = $requete->fetchAll(PDO::FETCH_ASSOC);
    $delay = 0;

    foreach ($appartements as $appartement) {
        echo '<div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="' . $delay . '">';
        echo '<div class="property-card">';
        echo '<div class="property-image">';
        echo '<img src="img/' . htmlspecialchars($appartement['photos']) . '" alt="' . htmlspecialchars($appartement['categorie']) . '">';
        echo '<div class="property-price">' . htmlspecialchars($appartement['prix']) . ' €/mois</div>';
        echo '</div>';
        echo '<div class="property-content">';
        echo '<h3 class="property-title">' . htmlspecialchars($appartement['categorie']) . '</h3>';
        echo '<div class="property-details">';
        echo '<span class="detail-item"><i class="fas fa-ruler-combined"></i> ' . htmlspecialchars($appartement['superficie']) . ' m²</span>';
        echo '<span class="detail-item"><i class="fas fa-bed"></i> ' . htmlspecialchars($appartement['lits']) . ' ch.</span>';
        echo '<span class="detail-item"><i class="fas fa-bath"></i> ' . htmlspecialchars($appartement['salle_de_bains']) . ' SDB</span>';
        echo '</div>';
        echo '<p class="property-description">' . htmlspecialchars($appartement['description']) . '</p>';
        echo '<a href="detail-appartements.php?apt=' . $appartement['Id_appartements'] . '" class="btn btn-primary w-100">Voir détails</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        $delay += 100;
    }
} catch (Exception $e) {
    echo '<div class="col-12"><div class="alert alert-danger">Une erreur est survenue lors de la recherche.</div></div>';
}
?>
