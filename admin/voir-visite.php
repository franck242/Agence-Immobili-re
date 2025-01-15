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
    header('Location: gestion-demandes.php');
    exit();
}

$id = $_GET['id'];

// Récupérer les détails de la visite
$requete = $connexion->prepare("
    SELECT v.*, a.categorie, a.photos, a.reference, a.prix, a.adresse, a.ville
    FROM visites v 
    JOIN appartements a ON v.Id_appartements = a.Id_appartements 
    WHERE v.Id_visite = ?
");
$requete->execute([$id]);
$visite = $requete->fetch(PDO::FETCH_ASSOC);

if (!$visite) {
    header('Location: gestion-demandes.php');
    exit();
}

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Visite - Admin</title>
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
                        <h1><i class="bi bi-calendar-check"></i> Détails de la Visite</h1>
                        <p class="text-muted">Consultation des informations de la visite</p>
                    </div>
                    <a href="gestion-demandes.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Retour à la liste
                    </a>
                </div>

                <div class="card mt-4 slide-in">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Informations sur le bien</h4>
                                <table class="table">
                                    <tr>
                                        <th>Référence :</th>
                                        <td><?php echo htmlspecialchars($visite['reference']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Catégorie :</th>
                                        <td><?php echo htmlspecialchars($visite['categorie']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Prix :</th>
                                        <td><?php echo number_format($visite['prix'], 0, ',', ' '); ?> €</td>
                                    </tr>
                                    <tr>
                                        <th>Adresse :</th>
                                        <td><?php echo htmlspecialchars($visite['adresse'] . ', ' . $visite['ville']); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h4>Informations sur le visiteur</h4>
                                <table class="table">
                                    <tr>
                                        <th>Nom :</th>
                                        <td><?php echo htmlspecialchars($visite['civilite'] . ' ' . $visite['firstname'] . ' ' . $visite['lastname']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email :</th>
                                        <td><?php echo htmlspecialchars($visite['email']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Téléphone :</th>
                                        <td><?php echo htmlspecialchars($visite['telephone']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Adresse :</th>
                                        <td><?php echo htmlspecialchars($visite['adresse'] . ', ' . $visite['code_postal'] . ' ' . $visite['ville']); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4>Détails de la visite</h4>
                                <table class="table">
                                    <tr>
                                        <th>Date de visite :</th>
                                        <td><?php echo date('d/m/Y H:i', strtotime($visite['date_visite'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Statut :</th>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $visite['statut'] === 'confirmée' ? 'success' : 
                                                    ($visite['statut'] === 'demandée' ? 'warning' : 
                                                    ($visite['statut'] === 'effectuée' ? 'info' : 'danger')); 
                                            ?>">
                                                <?php echo htmlspecialchars($visite['statut']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php if ($visite['commentaire_agent']): ?>
                                    <tr>
                                        <th>Commentaire de l'agent :</th>
                                        <td><?php echo nl2br(htmlspecialchars($visite['commentaire_agent'])); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
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
