<?php
require_once("connexion-bdd.php");
require_once("includes/auth.php");
require_once("menu.php");
forcerUtilisateurConnecte();

if (!isset($_SESSION['admin_id'])) {
    header('Location: connexion.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: liste-comptes.php');
    exit();
}

$id = $_GET['id'];
$requete = $connexion->prepare("SELECT * FROM admin WHERE Id_admin = ?");
$requete->execute([$id]);
$admin = $requete->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    header('Location: liste-comptes.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Compte - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="row">
                <div class="col-12">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h2><i class="bi bi-person-circle"></i> Détails du Compte</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <img src="uploads-images/<?php echo htmlspecialchars($admin['photos']); ?>" 
                                         alt="Photo de profil" 
                                         class="img-fluid rounded-circle mb-3"
                                         style="width: 200px; height: 200px; object-fit: cover;">
                                    <h4><?php echo htmlspecialchars($admin['firstname'] . ' ' . $admin['lastname']); ?></h4>
                                    <span class="badge bg-<?php echo $admin['role'] === 'admin' ? 'danger' : ($admin['role'] === 'manager' ? 'warning' : 'info'); ?>">
                                        <?php echo htmlspecialchars($admin['role']); ?>
                                    </span>
                                </div>
                                <div class="col-md-8">
                                    <h4>Informations du compte</h4>
                                    <table class="table">
                                        <tr>
                                            <th>Email :</th>
                                            <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Téléphone :</th>
                                            <td><?php echo $admin['telephone'] ? htmlspecialchars($admin['telephone']) : 'Non renseigné'; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Statut :</th>
                                            <td>
                                                <span class="text-<?php echo $admin['active'] ? 'success' : 'danger'; ?>">
                                                    <i class="bi bi-circle-fill"></i>
                                                    <?php echo $admin['active'] ? 'Actif' : 'Inactif'; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="mt-4">
                                        <a href="modifier-comptes.php?apt=<?php echo $admin['Id_admin']; ?>" class="btn btn-warning">
                                            <i class="bi bi-pencil"></i> Modifier
                                        </a>
                                        <a href="liste-comptes.php" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left"></i> Retour à la liste
                                        </a>
                                    </div>
                                </div>
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
