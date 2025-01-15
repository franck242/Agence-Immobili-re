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
        // Commencer une transaction
        $connexion->beginTransaction();
        
        // D'abord supprimer les entrées liées dans suivi_commercial
        $stmt = $connexion->prepare("DELETE FROM suivi_commercial WHERE contact_id = ?");
        $stmt->execute([$id]);
        
        // Ensuite supprimer le message
        $stmt = $connexion->prepare("DELETE FROM demandes_contact WHERE id = ?");
        $stmt->execute([$id]);
        
        // Valider la transaction
        $connexion->commit();
        
        header('Location: gestion-messages.php?success=1');
    } catch(PDOException $e) {
        // En cas d'erreur, annuler la transaction
        $connexion->rollBack();
        header('Location: gestion-messages.php?error=1');
    }
    exit();
} else {
    header('Location: gestion-messages.php');
    exit();
}
