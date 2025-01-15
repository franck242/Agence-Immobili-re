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

// Récupérer les détails de la demande
$requete = $connexion->prepare("
    SELECT d.*, a.categorie, a.photos, a.reference, a.prix, a.adresse, a.ville
    FROM demandes_contact d
    JOIN appartements a ON d.appartement_id = a.Id_appartements 
    WHERE d.id = ?
");
$requete->execute([$id]);
$demande = $requete->fetch(PDO::FETCH_ASSOC);

if (!$demande) {
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
    <title>Détails de la Demande - Admin</title>
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
                            <h2><i class="bi bi-envelope"></i> Détails de la Demande</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Informations sur le bien</h4>
                                    <p><strong>Référence :</strong> <?php echo htmlspecialchars($demande['reference']); ?></p>
                                    <p><strong>Catégorie :</strong> <?php echo htmlspecialchars($demande['categorie']); ?></p>
                                    <p><strong>Prix :</strong> <?php echo htmlspecialchars($demande['prix']); ?> €</p>
                                    <p><strong>Adresse :</strong> <?php echo htmlspecialchars($demande['adresse'] . ', ' . $demande['ville']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <h4>Informations sur le contact</h4>
                                    <p><strong>Nom :</strong> <?php echo htmlspecialchars($demande['nom']); ?></p>
                                    <p><strong>Email :</strong> <?php echo htmlspecialchars($demande['email']); ?></p>
                                    <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($demande['telephone']); ?></p>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h4>Détails de la demande</h4>
                                    <p><strong>Type de demande :</strong> <?php echo htmlspecialchars($demande['type_demande']); ?></p>
                                    <p><strong>Date de création :</strong> <?php echo date('d/m/Y H:i', strtotime($demande['date_creation'])); ?></p>
                                    <p><strong>Statut du suivi :</strong> <?php echo htmlspecialchars($demande['statut_suivi']); ?></p>
                                    <p><strong>Message :</strong></p>
                                    <div class="card">
                                        <div class="card-body">
                                            <?php echo nl2br(htmlspecialchars($demande['message'])); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="gestion-demandes.php" class="btn btn-secondary">Retour à la liste</a>
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
