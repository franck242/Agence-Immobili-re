<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $requete = $connexion->prepare("UPDATE notifications SET is_read = TRUE WHERE id = :id");
        $requete->execute([':id' => $id]);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'ID non fourni']);
}
