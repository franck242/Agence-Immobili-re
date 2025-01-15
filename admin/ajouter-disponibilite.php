<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appartement_id = $_POST['appartement_id'] ?? null;
    $date_debut = $_POST['date_debut'] ?? null;
    $date_fin = $_POST['date_fin'] ?? null;
    $statut = $_POST['statut'] ?? null;
    
    if (!$appartement_id || !$date_debut || !$date_fin || !$statut) {
        header('Location: calendrier-disponibilites.php?id=' . $appartement_id . '&error=1');
        exit();
    }
    
    try {
        // Vérifier les chevauchements
        $requete = $connexion->prepare("
            SELECT COUNT(*) FROM disponibilites
            WHERE appartement_id = :appartement_id
            AND (
                (date_debut BETWEEN :date_debut AND :date_fin)
                OR (date_fin BETWEEN :date_debut AND :date_fin)
                OR (:date_debut BETWEEN date_debut AND date_fin)
                OR (:date_fin BETWEEN date_debut AND date_fin)
            )
        ");
        $requete->execute([
            ':appartement_id' => $appartement_id,
            ':date_debut' => $date_debut,
            ':date_fin' => $date_fin
        ]);
        
        if ($requete->fetchColumn() > 0) {
            header('Location: calendrier-disponibilites.php?id=' . $appartement_id . '&error=2');
            exit();
        }
        
        // Ajouter la période
        $requete = $connexion->prepare("
            INSERT INTO disponibilites (appartement_id, date_debut, date_fin, statut)
            VALUES (:appartement_id, :date_debut, :date_fin, :statut)
        ");
        $requete->execute([
            ':appartement_id' => $appartement_id,
            ':date_debut' => $date_debut,
            ':date_fin' => $date_fin,
            ':statut' => $statut
        ]);
        
        header('Location: calendrier-disponibilites.php?id=' . $appartement_id . '&success=1');
    } catch (PDOException $e) {
        header('Location: calendrier-disponibilites.php?id=' . $appartement_id . '&error=1');
    }
} else {
    header('Location: liste-appartements.php');
}
