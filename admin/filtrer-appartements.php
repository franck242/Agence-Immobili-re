<?php
require_once("connexion-bdd.php");

// Récupération des paramètres de filtrage
$prix_min = $_GET['prix_min'] ?? null;
$prix_max = $_GET['prix_max'] ?? null;
$superficie_min = $_GET['superficie_min'] ?? null;
$superficie_max = $_GET['superficie_max'] ?? null;
$lits = $_GET['lits'] ?? null;
$salle_de_bains = $_GET['salle_de_bains'] ?? null;
$ville = $_GET['ville'] ?? null;
$status = $_GET['status'] ?? null;

// Construction de la requête SQL
$sql = "SELECT * FROM appartements WHERE 1=1";
$params = [];

if ($prix_min !== null && $prix_min !== '') {
    $sql .= " AND CAST(prix AS DECIMAL) >= :prix_min";
    $params[':prix_min'] = $prix_min;
}

if ($prix_max !== null && $prix_max !== '') {
    $sql .= " AND CAST(prix AS DECIMAL) <= :prix_max";
    $params[':prix_max'] = $prix_max;
}

if ($superficie_min !== null && $superficie_min !== '') {
    $sql .= " AND CAST(REPLACE(superficie, 'm²', '') AS DECIMAL) >= :superficie_min";
    $params[':superficie_min'] = $superficie_min;
}

if ($superficie_max !== null && $superficie_max !== '') {
    $sql .= " AND CAST(REPLACE(superficie, 'm²', '') AS DECIMAL) <= :superficie_max";
    $params[':superficie_max'] = $superficie_max;
}

if ($lits !== null && $lits !== '') {
    $sql .= " AND lits = :lits";
    $params[':lits'] = $lits;
}

if ($salle_de_bains !== null && $salle_de_bains !== '') {
    $sql .= " AND salle_de_bains = :salle_de_bains";
    $params[':salle_de_bains'] = $salle_de_bains;
}

if ($ville !== null && $ville !== '') {
    $sql .= " AND ville LIKE :ville";
    $params[':ville'] = "%$ville%";
}

if ($status !== null && $status !== '') {
    $sql .= " AND status = :status";
    $params[':status'] = $status;
}

$sql .= " ORDER BY Id_appartements DESC";

// Exécution de la requête
$requete = $connexion->prepare($sql);
$requete->execute($params);
$appartements = $requete->fetchAll(PDO::FETCH_ASSOC);

// Retour des résultats en JSON
header('Content-Type: application/json');
echo json_encode($appartements);
?>
