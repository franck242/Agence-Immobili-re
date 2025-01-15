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
        $requete = $connexion->prepare("
            UPDATE documents 
            SET statut = 'final' 
            WHERE id = :id AND statut = 'brouillon'
        ");
        $requete->execute([':id' => $id]);
        
        header('Location: gestion-documents.php?success=1');
    } catch (PDOException $e) {
        header('Location: gestion-documents.php?error=1');
    }
} else {
    header('Location: gestion-documents.php');
}
