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
$requete = $connexion->prepare("SELECT * FROM appartements ORDER BY Id_appartements DESC");
$requete->execute();
$resultat = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Appartements - Admin</title>
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
                    <div>
                        <h1><i class="bi bi-grid"></i> Liste des Appartements</h1>
                        <p>Vue complète de tous les appartements disponibles</p>
                    </div>
                    <div>
                        <a href="dashboard.php" class="btn btn-secondary me-2">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <a href="ajouter-appartement.php" class="btn btn-primary btn-ripple">
                            <i class="bi bi-plus-circle"></i> Ajouter un appartement
                        </a>
                    </div>
                </div>

                <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> L'appartement a été ajouté avec succès !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <div class="card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Photos</th>
                                        <th>Catégorie</th>
                                        <th>Superficie</th>
                                        <th>Lits</th>
                                        <th>Salle de bains</th>
                                        <th>Status</th>
                                        <th>Prix</th>
                                        <th>Ville</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($resultat as $elt): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($elt['photos'])): ?>
                                                    <img src="../img/<?php echo htmlspecialchars($elt['photos']); ?>" 
                                                         alt="Appartement" 
                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                                <?php else: ?>
                                                    <img src="../assets/img/default-apartment.jpg" 
                                                         alt="Image par défaut" 
                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($elt['categorie']); ?></td>
                                            <td><?php echo htmlspecialchars($elt['superficie']); ?> m²</td>
                                            <td><?php echo htmlspecialchars($elt['lits']); ?></td>
                                            <td><?php echo htmlspecialchars($elt['salle_de_bains']); ?></td>
                                            <td>
                                                <?php if ($elt['status'] == 0): ?>
                                                    <span class="badge bg-success">Disponible</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Non disponible</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($elt['prix']); ?> €</td>
                                            <td><?php echo htmlspecialchars($elt['ville']); ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="voir-appartement.php?id=<?php echo $elt['Id_appartements']; ?>" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="modifier-appartement.php?id=<?php echo $elt['Id_appartements']; ?>" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="supprimer-appartement.php?id=<?php echo $elt['Id_appartements']; ?>" 
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet appartement ?');">
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
