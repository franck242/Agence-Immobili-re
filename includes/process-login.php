<?php
session_start();
require_once '../admin/connexion-bdd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier d'abord si c'est un admin
    $requete = $connexion->prepare("SELECT * FROM admin WHERE email = ? AND password = SHA1(?)");
    $requete->execute([$email, $password]);
    $admin = $requete->fetch();

    if ($admin) {
        $_SESSION['connecte'] = true;
        $_SESSION['admin'] = $admin;
        $_SESSION['admin_id'] = $admin['Id_admin'];
        header('Location: ../admin/dashboard.php');
        exit();
    }

    // Si ce n'est pas un admin, vérifier si c'est un utilisateur normal
    $requete = $connexion->prepare("SELECT * FROM users WHERE email = ? AND password = SHA1(?)");
    $requete->execute([$email, $password]);
    $user = $requete->fetch();

    if ($user) {
        $_SESSION['connecte'] = true;
        $_SESSION['user_id'] = $user['Id_users'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user'] = $user;
        header('Location: ../user/dashboard.php');
        exit();
    }

    // Si aucune correspondance
    $_SESSION['error'] = "Email ou mot de passe incorrect";
    header('Location: ../connexion.php');
    exit();
} else {
    header('Location: ../connexion.php');
    exit();
}
?>
