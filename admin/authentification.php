<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et est un admin
if (!isset($_SESSION['connecte']) || !isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // Rediriger vers la page de connexion si non connecté ou non admin
    header('Location: ../connexion.php');
    exit();
}
?>
