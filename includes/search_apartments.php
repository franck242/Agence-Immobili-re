<?php
require_once '../admin/connexion-bdd.php';

header('Content-Type: application/json');

try {
    $conditions = [];
    $params = [];

    // Prix maximum
    if (!empty($_POST['prix_max'])) {
        $conditions[] = "CAST(REPLACE(prix, ' euros', '') AS DECIMAL) <= :prix_max";
        $params[':prix_max'] = floatval($_POST['prix_max']);
    }

    // Surface minimum
    if (!empty($_POST['surface_min'])) {
        $conditions[] = "CAST(REPLACE(REPLACE(superficie, 'm²', ''), ' ', '') AS DECIMAL) >= :surface_min";
        $params[':surface_min'] = floatval($_POST['surface_min']);
    }

    // Nombre de chambres
    if (!empty($_POST['chambres'])) {
        $conditions[] = "lits >= :chambres";
        $params[':chambres'] = intval($_POST['chambres']);
    }

    // Ville
    if (!empty($_POST['ville'])) {
        $conditions[] = "ville LIKE :ville";
        $params[':ville'] = '%' . $_POST['ville'] . '%';
    }

    // Status disponible
    $conditions[] = "status = 0";

    // Construire la requête
    $sql = "SELECT * FROM appartements";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $requete = $connexion->prepare($sql);
    $requete->execute($params);
    $appartements = $requete->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $appartements]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
