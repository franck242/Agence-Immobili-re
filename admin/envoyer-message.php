<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Non autorisé']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['conversation_id']) && isset($_POST['message'])) {
    $conversation_id = $_POST['conversation_id'];
    $message = trim($_POST['message']);
    
    if (empty($message)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Message vide']);
        exit();
    }
    
    try {
        // Vérifier que la conversation existe
        $requete = $connexion->prepare("
            SELECT id FROM conversations 
            WHERE id = :id AND admin_id = :admin_id
        ");
        $requete->execute([
            ':id' => $conversation_id,
            ':admin_id' => $_SESSION['admin_id']
        ]);
        
        if ($requete->rowCount() === 0) {
            throw new Exception('Conversation non trouvée');
        }
        
        // Insérer le message
        $requete = $connexion->prepare("
            INSERT INTO messages (conversation_id, sender_id, message)
            VALUES (:conversation_id, :sender_id, :message)
        ");
        $requete->execute([
            ':conversation_id' => $conversation_id,
            ':sender_id' => $_SESSION['admin_id'],
            ':message' => $message
        ]);
        
        // Mettre à jour la date de la conversation
        $requete = $connexion->prepare("
            UPDATE conversations 
            SET updated_at = CURRENT_TIMESTAMP 
            WHERE id = :id
        ");
        $requete->execute([':id' => $conversation_id]);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Données manquantes']);
}
