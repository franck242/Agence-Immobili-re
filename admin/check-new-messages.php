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

if (isset($_GET['conversation_id']) && isset($_GET['last_id'])) {
    $conversation_id = $_GET['conversation_id'];
    $last_id = $_GET['last_id'];
    
    try {
        // Récupérer les nouveaux messages
        $requete = $connexion->prepare("
            SELECT m.*, u.firstname, u.lastname
            FROM messages m
            JOIN users u ON m.sender_id = u.Id_users
            WHERE m.conversation_id = :conversation_id
            AND m.id > :last_id
            ORDER BY m.created_at ASC
        ");
        $requete->execute([
            ':conversation_id' => $conversation_id,
            ':last_id' => $last_id
        ]);
        $messages = $requete->fetchAll(PDO::FETCH_ASSOC);
        
        // Marquer les messages comme lus
        if (!empty($messages)) {
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
        }
        
        header('Content-Type: application/json');
        echo json_encode($messages);
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Erreur lors de la récupération des messages']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Paramètres manquants']);
}
