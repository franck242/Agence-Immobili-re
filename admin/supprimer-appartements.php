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
        // Vérifier si l'appartement existe
        $stmt = $connexion->prepare("SELECT Id_appartements FROM appartements WHERE Id_appartements = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            // Supprimer l'appartement
            $stmt = $connexion->prepare("DELETE FROM appartements WHERE Id_appartements = ?");
            $stmt->execute([$id]);
            
            $_SESSION['message'] = "L'appartement a été supprimé avec succès.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Appartement non trouvé.";
            $_SESSION['message_type'] = "danger";
        }
    } catch(PDOException $e) {
        $_SESSION['message'] = "Erreur lors de la suppression: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
}

header('Location: gestion-appartements.php');
exit();
?>
