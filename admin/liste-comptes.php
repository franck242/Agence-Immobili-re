<?php 
require_once("connexion-bdd.php");
require_once("includes/auth.php");
require_once("menu.php");
forcerUtilisateurConnecte();

if (!isset($_SESSION['admin_id'])) {
    header('Location: connexion.php');
    exit();
}

// Récupérer les statistiques
$stats = [
    'total_admins' => $connexion->query("SELECT COUNT(*) FROM admin")->fetchColumn(),
    'active_admins' => $connexion->query("SELECT COUNT(*) FROM admin WHERE active = 1")->fetchColumn(),
    'managers' => $connexion->query("SELECT COUNT(*) FROM admin WHERE role = 'manager'")->fetchColumn(),
    'admins' => $connexion->query("SELECT COUNT(*) FROM admin WHERE role = 'admin'")->fetchColumn()
];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Comptes - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1><i class="bi bi-people"></i> Liste des Comptes</h1>
                        <p class="text-muted">Gestion des comptes administrateurs</p>
                    </div>
                    <div>
                        <a href="dashboard.php" class="btn btn-secondary me-2">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <a href="ajouter-comptes.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Ajouter un compte
                        </a>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="row mb-4">
                    <div class="col-md-3 slide-in" style="animation-delay: 0.1s">
                        <div class="card stat-card">
                            <div class="card-body">
                                <h3><i class="bi bi-people-fill text-primary"></i> Total Admins</h3>
                                <h2 class="stat-number"><?php echo $stats['total_admins']; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 slide-in" style="animation-delay: 0.2s">
                        <div class="card stat-card">
                            <div class="card-body">
                                <h3><i class="bi bi-person-check text-success"></i> Actifs</h3>
                                <h2 class="stat-number"><?php echo $stats['active_admins']; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 slide-in" style="animation-delay: 0.3s">
                        <div class="card stat-card">
                            <div class="card-body">
                                <h3><i class="bi bi-person-gear text-info"></i> Managers</h3>
                                <h2 class="stat-number"><?php echo $stats['managers']; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 slide-in" style="animation-delay: 0.4s">
                        <div class="card stat-card">
                            <div class="card-body">
                                <h3><i class="bi bi-person-lock text-danger"></i> Admins</h3>
                                <h2 class="stat-number"><?php echo $stats['admins']; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des comptes -->
                <div class="card mt-4 slide-in">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Prénom</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $requete = $connexion->prepare("SELECT * FROM admin ORDER BY firstname");
                                    $requete->execute();
                                    $admins = $requete->fetchAll(PDO::FETCH_ASSOC);

                                    if (!empty($admins)) {
                                        foreach ($admins as $admin) {
                                            $statusClass = $admin['active'] ? 'text-success' : 'text-danger';
                                            $statusText = $admin['active'] ? 'Actif' : 'Inactif';
                                            ?>
                                            <tr>
                                                <td>
                                                    <img src="uploads-images/<?php echo htmlspecialchars($admin['photos']); ?>" 
                                                         alt="Photo de profil" 
                                                         class="rounded-circle"
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                </td>
                                                <td><?php echo htmlspecialchars($admin['firstname']); ?></td>
                                                <td><?php echo htmlspecialchars($admin['lastname']); ?></td>
                                                <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $admin['role'] === 'admin' ? 'danger' : ($admin['role'] === 'manager' ? 'warning' : 'info'); ?>">
                                                        <?php echo htmlspecialchars($admin['role']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="<?php echo $statusClass; ?>">
                                                        <i class="bi bi-circle-fill"></i> <?php echo $statusText; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="voir-compte.php?id=<?php echo $admin['Id_admin']; ?>" 
                                                           class="btn btn-sm btn-info" 
                                                           title="Voir les détails">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="modifier-comptes.php?apt=<?php echo $admin['Id_admin']; ?>" 
                                                           class="btn btn-sm btn-warning" 
                                                           title="Modifier">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <?php if ($admin['Id_admin'] != $_SESSION['admin_id']): ?>
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-danger" 
                                                                    onclick="confirmerSuppression(<?php echo $admin['Id_admin']; ?>)"
                                                                    title="Supprimer">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="7" class="text-center">Aucun compte administrateur trouvé</td></tr>';
                                    }
                                    ?>
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
    <script>
    function confirmerSuppression(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce compte ?')) {
            window.location.href = 'supprimer-comptes.php?id=' + id;
        }
    }
    </script>
</body>
</html>