<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: gestion-utilisateurs.php');
    exit();
}

$id = $_GET['id'];

// Récupérer les détails de l'utilisateur avec tous les champs nécessaires
$requete = $connexion->prepare("SELECT * FROM users WHERE Id_users = ?");
$requete->execute([$id]);
$utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    header('Location: gestion-utilisateurs.php');
    exit();
}

// Définir des valeurs par défaut si les champs sont NULL
$utilisateur['role'] = $utilisateur['role'] ?? 'user';
$utilisateur['status'] = isset($utilisateur['status']) ? (int)$utilisateur['status'] : 0;

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'Utilisateur - Admin</title>
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
                        <h1><i class="bi bi-person"></i> Détails de l'Utilisateur</h1>
                        <p class="text-muted">Consultation des informations de l'utilisateur</p>
                    </div>
                    <a href="gestion-utilisateurs.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Retour à la liste
                    </a>
                </div>

                <div class="card mt-4 slide-in">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Informations personnelles</h4>
                                <table class="table">
                                    <tr>
                                        <th>Nom :</th>
                                        <td><?php echo htmlspecialchars($utilisateur['firstname'] . ' ' . $utilisateur['lastname']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email :</th>
                                        <td><?php echo htmlspecialchars($utilisateur['email']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Rôle :</th>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $utilisateur['role'] === 'admin' ? 'danger' : 
                                                    ($utilisateur['role'] === 'manager' ? 'warning' : 'info'); 
                                            ?>">
                                                <?php echo htmlspecialchars(ucfirst($utilisateur['role'])); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Statut :</th>
                                        <td>
                                            <span class="badge bg-<?php echo $utilisateur['status'] ? 'success' : 'danger'; ?>">
                                                <?php echo $utilisateur['status'] ? 'Actif' : 'Inactif'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h4>Photo de profil</h4>
                                <?php if (!empty($utilisateur['photos'])): ?>
                                    <img src="<?php echo htmlspecialchars($utilisateur['photos']); ?>" alt="Photo de profil" class="img-fluid rounded" style="max-width: 200px;">
                                <?php else: ?>
                                    <div class="text-muted">Aucune photo de profil</div>
                                <?php endif; ?>
                            </div>
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
