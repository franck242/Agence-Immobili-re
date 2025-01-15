<?php
session_start();
require_once 'includes/header.php';
?>

<div class="container mt-5 pt-5">
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Message envoyé avec succès!</h4>
        <p>Votre demande a bien été enregistrée. Nous vous contacterons rapidement.</p>
        <hr>
        <div class="text-center">
            <a href="index.php" class="btn btn-primary">Retourner à l'accueil</a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
