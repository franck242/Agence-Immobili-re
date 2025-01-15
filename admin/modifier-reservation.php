<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

$message = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $connexion->prepare("
        SELECT r.*, a.categorie
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_depart = $_POST['date_depart'];
    $date_retour = $_POST['date_retour'];
    $email = $_POST['email'];

    try {
        $stmt = $connexion->prepare("UPDATE reservation SET date_depart = ?, date_retour = ?, email = ? WHERE Id_reservation = ?");
        $stmt->execute([$date_depart, $date_retour, $email, $id]);
        $message = '<div class="alert alert-success">La réservation a été modifiée avec succès!</div>';
    } catch(PDOException $e) {
        $message = '<div class="alert alert-danger">Erreur lors de la modification: ' . $e->getMessage() . '</div>';
    }
}

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Réservation - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <h1><i class="bi bi-calendar-check"></i> Modifier la Réservation</h1>
                <p>Modifiez les informations de la réservation</p>
            </div>

            <?php if ($message) echo $message; ?>

            <div class="card mt-4 slide-in">
                <div class="card-body">
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Appartement</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($reservation['categorie']); ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email du client</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($reservation['email']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="date_depart" class="form-label">Date d'arrivée</label>
                            <input type="date" class="form-control" id="date_depart" name="date_depart" 
                                   value="<?php echo $reservation['date_depart']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="date_retour" class="form-label">Date de départ</label>
                            <input type="date" class="form-control" id="date_retour" name="date_retour" 
                                   value="<?php echo $reservation['date_retour']; ?>" required>
                        </div>

                        <div class="mt-4">
                            <a href="gestion-reservations.php" class="btn btn-secondary btn-ripple">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary btn-ripple">
                                <i class="bi bi-check-lg"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
</body>
</html>
