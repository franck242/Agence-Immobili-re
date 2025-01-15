<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

if (isset($_GET['id']) && isset($_GET['appartement_id'])) {
    $id = $_GET['id'];
    $appartement_id = $_GET['appartement_id'];
    
    try {
        $requete = $connexion->prepare("DELETE FROM disponibilites WHERE id = :id");
        $requete->execute([':id' => $id]);
        
        header('Location: calendrier-disponibilites.php?id=' . $appartement_id . '&success=1');
    } catch (PDOException $e) {
        header('Location: calendrier-disponibilites.php?id=' . $appartement_id . '&error=1');
    }
} else {
    header('Location: liste-appartements.php');
}
