<?php
session_start();
require_once '../admin/connexion-bdd.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Utilisateur non connecté']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$apartment_id = $data['apartment_id'] ?? null;

if (!$apartment_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID d\'appartement manquant']);
    exit;
}

try {
    // Vérifier si l'appartement est déjà en favori
    $check = $connexion->prepare("SELECT * FROM favoris WHERE user_id = :user_id AND apartment_id = :apartment_id");
    $check->execute([
        ':user_id' => $_SESSION['user_id'],
        ':apartment_id' => $apartment_id
    ]);

    if ($check->rowCount() > 0) {
        // Supprimer des favoris
        $stmt = $connexion->prepare("DELETE FROM favoris WHERE user_id = :user_id AND apartment_id = :apartment_id");
        $message = 'Retiré des favoris';
    } else {
        // Ajouter aux favoris
        $stmt = $connexion->prepare("INSERT INTO favoris (user_id, apartment_id) VALUES (:user_id, :apartment_id)");
        $message = 'Ajouté aux favoris';
    }

    $stmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':apartment_id' => $apartment_id
    ]);

    echo json_encode(['success' => true, 'message' => $message]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
