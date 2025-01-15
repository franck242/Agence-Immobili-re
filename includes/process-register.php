<?php
session_start();
require_once '../admin/connexion-bdd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Validation
    if (empty($firstname) || empty($lastname)) {
        $_SESSION['error'] = 'Veuillez remplir tous les champs';
        header('Location: ../register.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Email invalide';
        header('Location: ../register.php');
        exit;
    }

    if (strlen($password) < 8) {
        $_SESSION['error'] = 'Le mot de passe doit contenir au moins 8 caractères';
        header('Location: ../register.php');
        exit;
    }

    if ($password !== $password_confirm) {
        $_SESSION['error'] = 'Les mots de passe ne correspondent pas';
        header('Location: ../register.php');
        exit;
    }

    try {
        // Vérifier si l'email existe déjà dans la table users
        $stmt = $connexion->prepare("SELECT Id_users FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        
        if ($stmt->fetch()) {
            $_SESSION['error'] = 'Cet email est déjà utilisé';
            header('Location: ../register.php');
            exit;
        }

        // Vérifier si l'email existe déjà dans la table admin
        $stmt = $connexion->prepare("SELECT Id_admin FROM admin WHERE email = :email");
        $stmt->execute(['email' => $email]);
        
        if ($stmt->fetch()) {
            $_SESSION['error'] = 'Cet email est déjà utilisé';
            header('Location: ../register.php');
            exit;
        }

        // Créer le compte utilisateur
        $stmt = $connexion->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)");
        $stmt->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        $_SESSION['success'] = 'Compte créé avec succès';
        header('Location: ../login.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Une erreur est survenue';
        header('Location: ../register.php');
        exit;
    }
}

header('Location: ../register.php');
exit;
