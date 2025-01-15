<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

// Récupérer toutes les visites avec les détails des appartements
$requete = $connexion->prepare("
    SELECT v.*, a.categorie, a.photos, a.reference 
    FROM visites v 
    JOIN appartements a ON v.Id_appartements = a.Id_appartements 
    ORDER BY v.date_visite DESC
");
$requete->execute();
$visites = $requete->fetchAll(PDO::FETCH_ASSOC);

// Récupérer toutes les demandes de contact
$requete2 = $connexion->prepare("
    SELECT d.*, a.categorie, a.photos, a.reference 
    FROM demandes_contact d 
    JOIN appartements a ON d.appartement_id = a.Id_appartements 
    ORDER BY d.date_creation DESC
");
$requete2->execute();
$demandes = $requete2->fetchAll(PDO::FETCH_ASSOC);

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Demandes - Admin</title>
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
                        <h1><i class="bi bi-calendar-check"></i> Gestion des Demandes</h1>
                        <p class="text-muted">Suivi des demandes de visites et d'informations</p>
                    </div>
                </div>

                <div class="card mt-4 slide-in">
                    <div class="card-body">
                        <h3>Demandes de Visites</h3>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Référence</th>
                                        <th>Client</th>
                                        <th>Date de visite</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($visites as $visite): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($visite['reference']); ?></td>
                                        <td><?php echo htmlspecialchars($visite['firstname'] . ' ' . $visite['lastname']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($visite['date_visite'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $visite['statut'] === 'confirmée' ? 'success' : 
                                                    ($visite['statut'] === 'demandée' ? 'warning' : 
                                                    ($visite['statut'] === 'effectuée' ? 'info' : 'danger')); 
                                            ?>">
                                                <?php echo htmlspecialchars($visite['statut']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="voir-visite.php?id=<?php echo $visite['Id_visite']; ?>" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Voir les détails">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <h3 class="mt-4">Demandes d'Information</h3>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Référence</th>
                                        <th>Client</th>
                                        <th>Date de demande</th>
                                        <th>Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($demandes as $demande): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($demande['reference']); ?></td>
                                        <td><?php echo htmlspecialchars($demande['nom']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($demande['date_creation'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $demande['type_demande'] === 'visite' ? 'primary' : 
                                                    ($demande['type_demande'] === 'information' ? 'info' : 'secondary'); 
                                            ?>">
                                                <?php echo htmlspecialchars($demande['type_demande']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="voir-demande.php?id=<?php echo $demande['id']; ?>" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Voir les détails">
                                                    <i class="bi bi-eye"></i>
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
    <script src="js/admin.js"></script>
</body>
</html>
