<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

// Récupérer tous les utilisateurs
$users = $connexion->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Admin</title>
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
                        <h1><i class="bi bi-people"></i> Gestion des Utilisateurs</h1>
                        <p>Gérez les comptes utilisateurs</p>
                    </div>
                    <div>
                        <a href="Ajouter-comptes.php" class="btn btn-primary btn-ripple">
                            <i class="bi bi-person-plus"></i> Ajouter un utilisateur
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
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Date d'inscription</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr class="fade-in">
                                    <td>
                                        <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <span class="badge bg-success">Actif</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="voir-utilisateur.php?id=<?php echo $user['Id_users']; ?>" 
                                               class="btn btn-sm btn-info btn-ripple">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="modifier-comptes.php?id=<?php echo $user['Id_users']; ?>" 
                                               class="btn btn-sm btn-warning btn-ripple">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="javascript:void(0)" 
                                               onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) window.location.href='supprimer-comptes.php?id=<?php echo $user['Id_users']; ?>'" 
                                               class="btn btn-sm btn-danger btn-ripple">
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
