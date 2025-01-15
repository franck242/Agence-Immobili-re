<!-- <?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

// Récupérer toutes les réservations avec les détails
$reservations = $connexion->query("
    SELECT r.*, a.categorie 
    FROM reservation r
    JOIN appartements a ON r.Id_appartements = a.Id_appartements
    ORDER BY r.date_depart DESC
")->fetchAll();

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réservations - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <h1><i class="bi bi-calendar-check"></i> Gestion des Réservations</h1>
                <p>Gérez les réservations des appartements</p>
            </div>

            <div class="card mt-4 slide-in">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Appartement</th>
                                    <th>Dates</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservations as $reservation): ?>
                                <tr class="fade-in">
                                    <td><?php echo htmlspecialchars($reservation['email']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['categorie']); ?></td>
                                    <td>
                                        <?php 
                                        echo date('d/m/Y', strtotime($reservation['date_depart'])) . ' - ' . 
                                             date('d/m/Y', strtotime($reservation['date_retour']));
                                        ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            Confirmée
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="voir-reservation.php?id=<?php echo $reservation['Id_reservation']; ?>" 
                                               class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="modifier-reservation.php?id=<?php echo $reservation['Id_reservation']; ?>" 
                                               class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="javascript:void(0)" 
                                               onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')) window.location.href='supprimer-reservation.php?id=<?php echo $reservation['Id_reservation']; ?>'" 
                                               class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
</body>
</html> -->
