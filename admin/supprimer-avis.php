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
        $requete = $connexion->prepare("DELETE FROM avis WHERE id = :id");
        $requete->execute([':id' => $id]);
        
        header('Location: gerer-avis.php?success=1');
    } catch (PDOException $e) {
        header('Location: gerer-avis.php?error=1');
    }
} else {
    header('Location: gerer-avis.php');
}
