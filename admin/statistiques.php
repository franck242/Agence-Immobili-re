<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

// Récupérer les statistiques des 12 derniers mois
$requete = $connexion->prepare("
    SELECT *
    FROM statistiques_mensuelles
    ORDER BY mois DESC
    LIMIT 12
");
$requete->execute();
$statistiques = $requete->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les métriques par appartement pour le mois en cours
$mois_actuel = date('Y-m-01');
$requete = $connexion->prepare("
    SELECT m.*, a.categorie as appartement_nom
    FROM metriques_appartements m
    JOIN appartements a ON m.appartement_id = a.Id_appartements
    WHERE m.mois = :mois
    ORDER BY m.revenu DESC
");
$requete->execute([':mois' => $mois_actuel]);
$metriques = $requete->fetchAll(PDO::FETCH_ASSOC);

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1><i class="bi bi-graph-up"></i> Statistiques</h1>
                        <p class="text-muted">Analyse des performances</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" onclick="exporterRapport()">
                            <i class="bi bi-download"></i> Exporter le rapport
                        </button>
                    </div>
                </div>

                <!-- KPIs -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Réservations ce mois</h6>
                                <h2 class="card-title">
                                    <?php echo $statistiques[0]['nombre_reservations'] ?? 0; ?>
                                </h2>
                                <?php
                                $diff = ($statistiques[0]['nombre_reservations'] ?? 0) - ($statistiques[1]['nombre_reservations'] ?? 0);
                                $class = $diff >= 0 ? 'text-success' : 'text-danger';
                                $icon = $diff >= 0 ? 'arrow-up' : 'arrow-down';
                                ?>
                                <p class="<?php echo $class; ?> mb-0">
                                    <i class="bi bi-<?php echo $icon; ?>"></i>
                                    <?php echo abs($diff); ?> vs mois précédent
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Revenu mensuel</h6>
                                <h2 class="card-title">
                                    <?php echo number_format($statistiques[0]['revenu_total'] ?? 0, 2); ?> €
                                </h2>
                                <?php
                                $diff = ($statistiques[0]['revenu_total'] ?? 0) - ($statistiques[1]['revenu_total'] ?? 0);
                                $class = $diff >= 0 ? 'text-success' : 'text-danger';
                                $icon = $diff >= 0 ? 'arrow-up' : 'arrow-down';
                                ?>
                                <p class="<?php echo $class; ?> mb-0">
                                    <i class="bi bi-<?php echo $icon; ?>"></i>
                                    <?php echo number_format(abs($diff), 2); ?> € vs mois précédent
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Taux d'occupation</h6>
                                <h2 class="card-title">
                                    <?php echo number_format($statistiques[0]['taux_occupation'] ?? 0, 1); ?>%
                                </h2>
                                <?php
                                $diff = ($statistiques[0]['taux_occupation'] ?? 0) - ($statistiques[1]['taux_occupation'] ?? 0);
                                $class = $diff >= 0 ? 'text-success' : 'text-danger';
                                $icon = $diff >= 0 ? 'arrow-up' : 'arrow-down';
                                ?>
                                <p class="<?php echo $class; ?> mb-0">
                                    <i class="bi bi-<?php echo $icon; ?>"></i>
                                    <?php echo number_format(abs($diff), 1); ?>% vs mois précédent
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Note moyenne</h6>
                                <h2 class="card-title">
                                    <?php echo number_format($statistiques[0]['note_moyenne'] ?? 0, 1); ?>/5
                                </h2>
                                <?php
                                $diff = ($statistiques[0]['note_moyenne'] ?? 0) - ($statistiques[1]['note_moyenne'] ?? 0);
                                $class = $diff >= 0 ? 'text-success' : 'text-danger';
                                $icon = $diff >= 0 ? 'arrow-up' : 'arrow-down';
                                ?>
                                <p class="<?php echo $class; ?> mb-0">
                                    <i class="bi bi-<?php echo $icon; ?>"></i>
                                    <?php echo number_format(abs($diff), 1); ?> vs mois précédent
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Graphique des revenus -->
                    <div class="col-md-8 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Évolution des revenus</h5>
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Graphique des réservations -->
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Répartition des réservations</h5>
                                <canvas id="reservationsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau des performances par appartement -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Performance par appartement (mois en cours)</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Appartement</th>
                                        <th>Revenu</th>
                                        <th>Nuits réservées</th>
                                        <th>Note moyenne</th>
                                        <th>Nombre d'avis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($metriques as $metrique): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($metrique['appartement_nom']); ?></td>
                                        <td><?php echo number_format($metrique['revenu'], 2); ?> €</td>
                                        <td><?php echo $metrique['nombre_nuits']; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php echo number_format($metrique['note_moyenne'], 1); ?>
                                                <div class="ms-2">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="bi bi-star<?php echo $i <= $metrique['note_moyenne'] ? '-fill' : ''; ?> text-warning"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo $metrique['nombre_avis']; ?></td>
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
        // Graphique des revenus
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: <?php 
                    echo json_encode(array_map(function($s) {
                        return date('M Y', strtotime($s['mois']));
                    }, array_reverse($statistiques)));
                ?>,
                datasets: [{
                    label: 'Revenu mensuel (€)',
                    data: <?php 
                        echo json_encode(array_map(function($s) {
                            return $s['revenu_total'];
                        }, array_reverse($statistiques)));
                    ?>,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Graphique des réservations
        const reservationsCtx = document.getElementById('reservationsChart').getContext('2d');
        new Chart(reservationsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Réservations', 'Disponibilités'],
                datasets: [{
                    data: [
                        <?php echo $statistiques[0]['taux_occupation'] ?? 0; ?>,
                        <?php echo 100 - ($statistiques[0]['taux_occupation'] ?? 0); ?>
                    ],
                    backgroundColor: [
                        'rgb(75, 192, 192)',
                        'rgb(255, 99, 132)'
                    ]
                }]
            },
            options: {
                responsive: true
            }
        });

        function exporterRapport() {
            // TODO: Implémenter l'export du rapport en PDF
            alert('Fonctionnalité à venir : export du rapport en PDF');
        }
    </script>
</body>
</html>
