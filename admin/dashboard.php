<?php
session_start();
require_once("connexion-bdd.php");
require_once("../includes/auth.php");

// Vérifier si l'utilisateur est un admin
requireAdmin();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

// Récupérer les statistiques
$stats = [
    'appartements' => $connexion->query("SELECT COUNT(*) FROM appartements")->fetchColumn(),
    'visites' => $connexion->query("SELECT COUNT(*) FROM visites")->fetchColumn(),
    'messages' => $connexion->query("SELECT COUNT(*) FROM demandes_contact")->fetchColumn(),
    'users' => $connexion->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'nouvelles_visites' => $connexion->query("SELECT COUNT(*) FROM visites WHERE statut = 'demandée'")->fetchColumn()
];

// Récupérer les dernières visites
$visites = $connexion->query("
    SELECT v.*, a.categorie, v.firstname, v.lastname, v.email
    FROM visites v
    JOIN appartements a ON v.Id_appartements = a.Id_appartements
    ORDER BY v.Id_visite DESC
    LIMIT 5
")->fetchAll();

// Récupérer les derniers messages
$messages = $connexion->query("
    SELECT dc.*, a.categorie 
    FROM demandes_contact dc
    LEFT JOIN appartements a ON dc.appartement_id = a.Id_appartements
    ORDER BY dc.date_creation DESC
    LIMIT 5
")->fetchAll();

// Récupérer les dernières demandes de visite
$demandes_visite = $connexion->query("
    SELECT v.*, a.categorie 
    FROM visites v
    JOIN appartements a ON v.Id_appartements = a.Id_appartements
    WHERE v.statut = 'demandée'
    ORDER BY v.date_visite DESC
    LIMIT 5
")->fetchAll();

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
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
                        <h1><i class="bi bi-speedometer2"></i> Dashboard Administration</h1>
                        <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['admin_firstname'] . ' ' . $_SESSION['admin_lastname']); ?></p>
                    </div>
                    <div>
                        <a href="../deconnexion.php" class="btn btn-danger btn-ripple">
                            <i class="bi bi-box-arrow-right"></i> Déconnexion
                        </a>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-3 slide-in" style="animation-delay: 0.1s">
                    <div class="card stat-card">
                        <div class="card-body">
                            <h3><i class="bi bi-building text-primary"></i> Appartements</h3>
                            <h2 class="stat-number"><?php echo $stats['appartements']; ?></h2>
                            <a href="liste-appartements.php" class="btn btn-primary btn-ripple mt-2">
                                <i class="bi bi-arrow-right"></i> Gérer
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 slide-in" style="animation-delay: 0.2s">
                    <div class="card stat-card">
                        <div class="card-body">
                            <h3><i class="bi bi-calendar-check text-success"></i> Visites</h3>
                            <h2 class="stat-number"><?php echo $stats['visites']; ?></h2>
                            <a href="gestion-demandes.php" class="btn btn-success btn-ripple mt-2">
                                <i class="bi bi-arrow-right"></i> Gérer
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 slide-in" style="animation-delay: 0.3s">
                    <div class="card stat-card">
                        <div class="card-body">
                            <h3><i class="bi bi-envelope text-info"></i> Messages</h3>
                            <h2 class="stat-number"><?php echo $stats['messages']; ?></h2>
                            <a href="gestion-messages.php" class="btn btn-info btn-ripple mt-2">
                                <i class="bi bi-arrow-right"></i> Voir
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 slide-in" style="animation-delay: 0.4s">
                    <div class="card stat-card">
                        <div class="card-body">
                            <h3><i class="bi bi-people text-warning"></i> Utilisateurs</h3>
                            <h2 class="stat-number"><?php echo $stats['users']; ?></h2>
                            <a href="liste-comptes.php" class="btn btn-warning btn-ripple mt-2">
                                <i class="bi bi-arrow-right"></i> Gérer
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6 slide-in" style="animation-delay: 0.5s">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Dernières Visites</h3>
                            <a href="gestion-demandes.php" class="btn btn-sm btn-primary">Voir tout</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Client</th>
                                            <th>Appartement</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($visites as $visite): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y', strtotime($visite['date_visite'])); ?></td>
                                            <td><?php echo htmlspecialchars($visite['firstname'] . ' ' . $visite['lastname']); ?></td>
                                            <td><?php echo htmlspecialchars($visite['categorie']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $visite['statut'] === 'demandée' ? 'warning' : 'success'; ?>">
                                                    <?php echo htmlspecialchars($visite['statut']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 slide-in" style="animation-delay: 0.6s">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Derniers Messages</h3>
                            <a href="gestion-messages.php" class="btn btn-sm btn-primary">Voir tout</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>De</th>
                                            <th>Sujet</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($messages as $message): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y', strtotime($message['date_creation'])); ?></td>
                                            <td><?php echo htmlspecialchars($message['nom']); ?></td>
                                            <td><?php echo htmlspecialchars($message['message']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $message['statut_suivi'] === 'nouveau' ? 'warning' : 'success'; ?>">
                                                    <?php echo htmlspecialchars($message['statut_suivi']); ?>
                                                </span>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="dashbord.js"></script>
</body>
</html>
