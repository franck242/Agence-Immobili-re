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
        // Vérifier si la réservation existe
        $stmt = $connexion->prepare("SELECT Id_reservation FROM reservation WHERE Id_reservation = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            // Supprimer la réservation
            $stmt = $connexion->prepare("DELETE FROM reservation WHERE Id_reservation = ?");
            $stmt->execute([$id]);
            
            $_SESSION['message'] = "La réservation a été supprimée avec succès.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Réservation non trouvée.";
            $_SESSION['message_type'] = "danger";
        }
    } catch(PDOException $e) {
        $_SESSION['message'] = "Erreur lors de la suppression: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
}

header('Location: gestion-reservations.php');
exit();
