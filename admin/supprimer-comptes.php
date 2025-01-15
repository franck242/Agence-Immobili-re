<?php
require_once("connexion-bdd.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: gestion-utilisateurs.php');
    exit();
}

$id = $_GET['id'];

// Vérifier si l'utilisateur n'essaie pas de supprimer son propre compte
if ($id == $_SESSION['admin_id']) {
    $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte.";
    header('Location: gestion-utilisateurs.php');
    exit();
}

// Vérifier si le compte existe
$requete = $connexion->prepare("SELECT * FROM users WHERE Id_users = ?");
$requete->execute([$id]);
$compte = $requete->fetch(PDO::FETCH_ASSOC);

if ($compte) {
    try {
        // Supprimer le compte
        $requete = $connexion->prepare("DELETE FROM users WHERE Id_users = ?");
        $requete->execute([$id]);
        $_SESSION['success'] = "Le compte a été supprimé avec succès.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Une erreur est survenue lors de la suppression du compte.";
    }
} else {
    $_SESSION['error'] = "Ce compte n'existe pas.";
}

header('Location: gestion-utilisateurs.php');
exit();
?>
