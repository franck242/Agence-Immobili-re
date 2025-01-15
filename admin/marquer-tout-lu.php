<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

try {
    $requete = $connexion->prepare("UPDATE notifications SET lu = 1 WHERE lu = 0 AND (user_id = :user_id OR user_id IS NULL)");
    $requete->execute(['user_id' => $_SESSION['admin_id']]);
    
    header('Location: notifications.php?success=1');
} catch(PDOException $e) {
    header('Location: notifications.php?error=1');
}
exit();
