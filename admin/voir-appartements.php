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
    $stmt = $connexion->prepare("SELECT * FROM appartements WHERE Id_appartements = ?");
    $stmt->execute([$id]);
    $appartement = $stmt->fetch();

    if (!$appartement) {
        header('Location: gestion-appartements.php');
        exit();
    }
} else {
    header('Location: gestion-appartements.php');
    exit();
}

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'Appartement - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <h1><i class="bi bi-building"></i> Détails de l'Appartement</h1>
                <p>Informations complètes sur l'appartement</p>
            </div>

            <div class="card mt-4 slide-in">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informations générales</h5>
                            <p><strong>Catégorie:</strong> <?php echo htmlspecialchars($appartement['categorie']); ?></p>
                            <p><strong>Prix:</strong> <?php echo number_format($appartement['prix'], 2, ',', ' '); ?> €</p>
                            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($appartement['description'])); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h5>Caractéristiques</h5>
                            <p><strong>Ville:</strong> <?php echo htmlspecialchars($appartement['ville']); ?></p>
                            <p><strong>Adresse:</strong> <?php echo htmlspecialchars($appartement['adresse']); ?></p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="gestion-appartements.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <a href="modifier-appartements.php?id=<?php echo $appartement['Id_appartements']; ?>" 
                           class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <a href="javascript:void(0)" 
                           onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet appartement ?')) window.location.href='supprimer-appartements.php?id=<?php echo $appartement['Id_appartements']; ?>'" 
                           class="btn btn-danger">
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
