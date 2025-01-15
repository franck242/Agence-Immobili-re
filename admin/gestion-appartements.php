<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

// Récupérer tous les appartements
$appartements = $connexion->query("SELECT * FROM appartements ORDER BY Id_appartements DESC")->fetchAll();

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Appartements - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1><i class="bi bi-building"></i> Gestion des Appartements</h1>
                        <p>Gérez votre catalogue d'appartements</p>
                    </div>
                    <div>
                        <a href="ajouter-appartement.php" class="btn btn-primary btn-ripple">
                            <i class="bi bi-plus-circle"></i> Ajouter un appartement
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-4 slide-in">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Catégorie</th>
                                    <th>Prix</th>
                                    <th>Disponibilité</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appartements as $appartement): ?>
                                <tr class="fade-in">
                                    <td><?php echo $appartement['Id_appartements']; ?></td>
                                    <td><?php echo htmlspecialchars($appartement['categorie']); ?></td>
                                    <td><?php echo number_format($appartement['prix'], 2, ',', ' '); ?> €</td>
                                    <td>
                                        <span class="badge bg-success">
                                            Disponible
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="voir-appartement.php?id=<?php echo $appartement['Id_appartements']; ?>" 
                                               class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="modifier-appartement.php?id=<?php echo $appartement['Id_appartements']; ?>" 
                                               class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="javascript:void(0)" 
                                               onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet appartement ?')) window.location.href='supprimer-appartement.php?id=<?php echo $appartement['Id_appartements']; ?>'" 
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
</html>
