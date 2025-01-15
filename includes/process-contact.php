<?php
session_start();
require_once '../admin/connexion-bdd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    
    // Validation des champs
    if (empty($_POST['name'])) {
        $errors[] = "Le nom est requis";
    }
    
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email est invalide";
    }
    
    if (empty($_POST['phone'])) {
        $errors[] = "Le téléphone est requis";
    }
    
    if (empty($_POST['message'])) {
        $errors[] = "Le message est requis";
    }
    
    if (empty($errors)) {
        try {
            // Préparation de la requête
            $requete = $connexion->prepare("
                INSERT INTO contacts (
                    name, email, phone, message, apartment_id, created_at
                ) VALUES (
                    :name, :email, :phone, :message, :apartment_id, NOW()
                )
            ");
            
            // Exécution de la requête
            $success = $requete->execute([
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'message' => $_POST['message'],
                'apartment_id' => isset($_POST['apartment_id']) ? $_POST['apartment_id'] : null
            ]);
            
            if ($success) {
                $_SESSION['success'] = "Votre message a été envoyé avec succès. Nous vous contacterons bientôt.";
                // Attendre 2 secondes avant la redirection
                header('Refresh: 2; URL=../index.php');
                // Afficher la page de confirmation
                echo '<!DOCTYPE html>
                    <html>
                    <head>
                        <title>Message envoyé</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    </head>
                    <body>
                        <div class="container mt-5">
                            <div class="alert alert-success text-center">
                                <h4>Message envoyé avec succès !</h4>
                                <p>Vous allez être redirigé vers l\'accueil dans quelques secondes...</p>
                            </div>
                        </div>
                    </body>
                    </html>';
                exit();
            } else {
                throw new Exception("Erreur lors de l'envoi du message");
            }
        } catch (Exception $e) {
            $errors[] = "Une erreur est survenue lors de l'envoi du message";
        }
    }
    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: ../contact.php');
        exit();
    }
} else {
    header('Location: ../contact.php');
    exit();
}
