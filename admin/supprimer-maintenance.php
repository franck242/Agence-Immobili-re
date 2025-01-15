<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Récupérer les factures liées aux interventions
        $requete = $connexion->prepare("
            SELECT facture_path 
            FROM interventions_maintenance 
            WHERE maintenance_id = :id 
            AND facture_path IS NOT NULL
        ");
        $requete->execute([':id' => $id]);
        $factures = $requete->fetchAll(PDO::FETCH_COLUMN);
        
        // Supprimer les fichiers de factures
        foreach ($factures as $facture) {
            $file_path = "factures/" . $facture;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        // Supprimer la tâche (les interventions seront supprimées automatiquement grâce à la contrainte ON DELETE CASCADE)
        $requete = $connexion->prepare("DELETE FROM maintenance WHERE id = :id");
        $requete->execute([':id' => $id]);
        
        header('Location: gestion-maintenance.php?success=1');
    } catch (PDOException $e) {
        header('Location: gestion-maintenance.php?error=1');
    }
} else {
    header('Location: gestion-maintenance.php');
}
