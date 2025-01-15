<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Non autorisé']);
    exit();
}

if (isset($_GET['conversation_id'])) {
    $conversation_id = $_GET['conversation_id'];
    
    try {
        // Marquer les messages comme lus
        $requete = $connexion->prepare("
            UPDATE messages 
            SET is_read = TRUE 
            WHERE conversation_id = :conversation_id 
            AND sender_id != :admin_id
            AND is_read = FALSE
        ");
        $requete->execute([
            ':conversation_id' => $conversation_id,
            ':admin_id' => $_SESSION['admin_id']
        ]);
        
        // Récupérer les messages
        $requete = $connexion->prepare("
            SELECT m.*, u.firstname, u.lastname
            FROM messages m
            JOIN users u ON m.sender_id = u.Id_users
            WHERE m.conversation_id = :conversation_id
            ORDER BY m.created_at ASC
        ");
        $requete->execute([':conversation_id' => $conversation_id]);
        $messages = $requete->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode($messages);
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Erreur lors de la récupération des messages']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'ID de conversation non fourni']);
}
