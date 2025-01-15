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
        SELECT dc.*, a.categorie 
        FROM demandes_contact dc
        LEFT JOIN appartements a ON dc.appartement_id = a.Id_appartements
        WHERE dc.id = ?
    ");
    $stmt->execute([$id]);
    $message = $stmt->fetch();

    if (!$message) {
        header('Location: gestion-messages.php');
        exit();
    }
} else {
    header('Location: gestion-messages.php');
    exit();
}

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voir le Message - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <h1><i class="bi bi-envelope"></i> Détails du Message</h1>
                <p>Visualisation complète du message</p>
            </div>

            <div class="card mt-4 slide-in">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">Informations du contact</h5>
                            <p><strong>Email :</strong> <?php echo htmlspecialchars($message['email']); ?></p>
                            <p><strong>Date :</strong> <?php echo date('d/m/Y H:i', strtotime($message['date_creation'])); ?></p>
                            <?php if ($message['categorie']): ?>
                            <p><strong>Appartement concerné :</strong> <?php echo htmlspecialchars($message['categorie']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <a href="mailto:<?php echo $message['email']; ?>" class="btn btn-primary btn-ripple">
                                <i class="bi bi-reply"></i> Répondre
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5 class="card-title">Message</h5>
                        <div class="p-3 bg-light rounded">
                            <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="gestion-messages.php" class="btn btn-secondary btn-ripple">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <div>
                            <a href="modifier-message.php?id=<?php echo $message['id']; ?>" 
                               class="btn btn-warning btn-ripple">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                            <a href="javascript:void(0)" 
                               onclick="if(confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) window.location.href='supprimer-message.php?id=<?php echo $message['id']; ?>'" 
                               class="btn btn-danger btn-ripple">
                                <i class="bi bi-trash"></i> Supprimer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
</body>
</html>
