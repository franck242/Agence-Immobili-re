<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

// Récupérer tous les favoris avec les détails des appartements et des utilisateurs
$requete = $connexion->prepare("
    SELECT f.*, 
           u.firstname as user_firstname, 
           u.lastname as user_lastname,
           u.email as user_email,
           a.categorie as apartment_categorie,
           a.photos as apartment_photos,
           a.prix as apartment_prix
    FROM favoris f 
    JOIN users u ON f.user_id = u.Id_users
    JOIN appartements a ON f.apartment_id = a.Id_appartements 
    ORDER BY f.created_at DESC
");
$requete->execute();
$favoris = $requete->fetchAll(PDO::FETCH_ASSOC);

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Favoris - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1><i class="bi bi-heart"></i> Liste des Favoris</h1>
                        <p class="text-muted">Appartements favoris des utilisateurs</p>
                    </div>
                </div>

                <div class="card mt-4 slide-in">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Appartement</th>
                                        <th>Utilisateur</th>
                                        <th>Prix</th>
                                        <th>Date d'ajout</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($favoris)): ?>
                                        <?php foreach ($favoris as $favori): ?>
                                            <tr class="fade-in">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="uploads-images/<?php echo htmlspecialchars($favori['apartment_photos']); ?>" 
                                                             alt="Appartement" 
                                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                                        <?php echo htmlspecialchars($favori['apartment_categorie']); ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($favori['user_firstname']); ?>
                                                    <?php echo htmlspecialchars($favori['user_lastname']); ?>
                                                    <br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($favori['user_email']); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($favori['apartment_prix']); ?> €</td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($favori['created_at'])); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer ce favori ?')) window.location.href='supprimer-favori.php?id=<?php echo $favori['id']; ?>'">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Aucun favori trouvé</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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
