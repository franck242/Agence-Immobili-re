<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

// Récupérer tous les avis avec les détails des appartements et utilisateurs
$requete = $connexion->prepare("
    SELECT a.*, 
           ap.categorie as appartement_nom,
           ap.photos as appartement_photo,
           u.firstname as user_firstname,
           u.lastname as user_lastname,
           u.email as user_email
    FROM avis a
    JOIN appartements ap ON a.appartement_id = ap.Id_appartements
    JOIN users u ON a.user_id = u.Id_users
    ORDER BY a.created_at DESC
");
$requete->execute();
$avis = $requete->fetchAll(PDO::FETCH_ASSOC);

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Avis - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
    <style>
        .star-rating {
            color: #ffc107;
        }
        .review-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1><i class="bi bi-star"></i> Gérer les Avis</h1>
                        <p class="text-muted">Modération des avis clients</p>
                    </div>
                </div>

                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> L'avis a été mis à jour avec succès !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <div class="card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Appartement</th>
                                        <th>Client</th>
                                        <th>Note</th>
                                        <th>Commentaire</th>
                                        <th>Date du séjour</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($avis as $avis_item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="uploads-images/<?php echo htmlspecialchars($avis_item['appartement_photo']); ?>" 
                                                     alt="Appartement"
                                                     class="review-image me-2">
                                                <span><?php echo htmlspecialchars($avis_item['appartement_nom']); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($avis_item['user_firstname'] . ' ' . $avis_item['user_lastname']); ?>
                                            <br>
                                            <small class="text-muted"><?php echo htmlspecialchars($avis_item['user_email']); ?></small>
                                        </td>
                                        <td>
                                            <div class="star-rating">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="bi bi-star<?php echo $i <= $avis_item['note'] ? '-fill' : ''; ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </td>
                                        <td><?php echo nl2br(htmlspecialchars($avis_item['commentaire'])); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($avis_item['date_sejour'])); ?></td>
                                        <td>
                                            <?php if ($avis_item['is_approved']): ?>
                                                <span class="badge bg-success">Approuvé</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">En attente</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!$avis_item['is_approved']): ?>
                                            <button class="btn btn-sm btn-success" onclick="approuverAvis(<?php echo $avis_item['id']; ?>)">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-danger" onclick="supprimerAvis(<?php echo $avis_item['id']; ?>)">
                                                <i class="bi bi-trash"></i>
                                            </button>
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
    <script>
        function approuverAvis(id) {
            if (confirm('Approuver cet avis ?')) {
                window.location.href = 'approuver-avis.php?id=' + id;
            }
        }

        function supprimerAvis(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')) {
                window.location.href = 'supprimer-avis.php?id=' + id;
            }
        }
    </script>
</body>
</html>
