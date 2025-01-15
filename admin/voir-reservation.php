<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $connexion->prepare("
        SELECT r.*, a.categorie, a.prix
        FROM reservation r
        JOIN appartements a ON r.Id_appartements = a.Id_appartements
        WHERE r.Id_reservation = ?
    ");
    $stmt->execute([$id]);
    $reservation = $stmt->fetch();

    if (!$reservation) {
        header('Location: gestion-reservations.php');
        exit();
    }
} else {
    header('Location: gestion-reservations.php');
    exit();
}

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Réservation - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <h1><i class="bi bi-calendar-check"></i> Détails de la Réservation</h1>
                <p>Informations complètes sur la réservation</p>
            </div>

            <div class="card mt-4 slide-in">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informations du client</h5>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($reservation['email']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h5>Informations de l'appartement</h5>
                            <p><strong>Catégorie:</strong> <?php echo htmlspecialchars($reservation['categorie']); ?></p>
                            <p><strong>Prix:</strong> <?php echo number_format($reservation['prix'], 2, ',', ' '); ?> €</p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Détails de la réservation</h5>
                            <p><strong>Date d'arrivée:</strong> <?php echo date('d/m/Y', strtotime($reservation['date_depart'])); ?></p>
                            <p><strong>Date de départ:</strong> <?php echo date('d/m/Y', strtotime($reservation['date_retour'])); ?></p>
                            <p><strong>Statut:</strong> <span class="badge bg-success">Confirmée</span></p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="gestion-reservations.php" class="btn btn-secondary btn-ripple">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <a href="modifier-reservation.php?id=<?php echo $reservation['Id_reservation']; ?>" 
                           class="btn btn-warning btn-ripple">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <a href="javascript:void(0)" 
                           onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')) window.location.href='supprimer-reservation.php?id=<?php echo $reservation['Id_reservation']; ?>'" 
                           class="btn btn-danger btn-ripple">
                            <i class="bi bi-trash"></i> Supprimer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
</body>
</html>
