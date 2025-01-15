<?php
session_start();
require_once 'includes/header.php';

if (!isset($_SESSION['success'])) {
    header('Location: index.php');
    exit();
}

$message = $_SESSION['success'];
unset($_SESSION['success']); // On supprime le message après l'avoir récupéré
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-8 mx-auto text-center">
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    <h2 class="mt-3 mb-4">Réservation confirmée !</h2>
                    <p class="lead"><?php echo htmlspecialchars($message); ?></p>
                    <p>Un email de confirmation vous sera envoyé prochainement avec tous les détails de votre réservation.</p>
                    <div class="mt-4">
                        <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
