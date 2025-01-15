<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: liste-appartements.php');
    exit();
}

$id = $_GET['id'];
$requete = $connexion->prepare("SELECT * FROM appartements WHERE Id_appartements = ?");
$requete->execute([$id]);
$appartement = $requete->fetch(PDO::FETCH_ASSOC);

if (!$appartement) {
    header('Location: liste-appartements.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voir Appartement - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <?php include_once("menu.php"); ?>
    
    <div id="main">
        <div class="container-fluid">
            <div class="welcome-section">
                <div class="d-flex justify-content-between align-items-center">
                    <h1><i class="bi bi-building"></i> Détails de l'Appartement</h1>
                    <a href="liste-appartements.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="../img/<?php echo htmlspecialchars($appartement['photos']); ?>" 
                                 alt="<?php echo htmlspecialchars($appartement['categorie']); ?>"
                                 class="img-fluid rounded">
                        </div>
                        <div class="col-md-6">
                            <h2><?php echo htmlspecialchars($appartement['categorie']); ?></h2>
                            <p class="text-muted">Ville: <?php echo htmlspecialchars($appartement['ville']); ?></p>
                            
                            <div class="row mt-4">
                                <div class="col-6">
                                    <h5>Caractéristiques</h5>
                                    <ul class="list-unstyled">
                                        <li><i class="bi bi-rulers"></i> Superficie: <?php echo htmlspecialchars($appartement['superficie']); ?> m²</li>
                                        <li><i class="bi bi-door-open"></i> Chambres: <?php echo htmlspecialchars($appartement['lits']); ?></li>
                                        <li><i class="bi bi-droplet"></i> Salles de bain: <?php echo htmlspecialchars($appartement['salle_de_bains']); ?></li>
                                    </ul>
                                </div>
                                <div class="col-6">
                                    <h5>Informations</h5>
                                    <ul class="list-unstyled">
                                        <li><i class="bi bi-tag"></i> Prix: <?php echo htmlspecialchars($appartement['prix']); ?> €/mois</li>
                                        <li><i class="bi bi-circle"></i> Status: 
                                            <?php if ($appartement['status'] == 0): ?>
                                                <span class="badge bg-success">Disponible</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Non disponible</span>
                                            <?php endif; ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h5>Description</h5>
                                <p><?php echo nl2br(htmlspecialchars($appartement['description'])); ?></p>
                            </div>

                            <div class="mt-4">
                                <a href="modifier-appartement.php?id=<?php echo $appartement['Id_appartements']; ?>" 
                                   class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
