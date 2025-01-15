<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

$id_appartement = $_GET['id'] ?? null;

if (!$id_appartement) {
    header('Location: liste-appartements.php');
    exit();
}

// Récupérer les informations de l'appartement
$requete = $connexion->prepare("SELECT * FROM appartements WHERE Id_appartements = :id");
$requete->execute([':id' => $id_appartement]);
$appartement = $requete->fetch(PDO::FETCH_ASSOC);

// Récupérer les disponibilités
$requete = $connexion->prepare("
    SELECT * FROM disponibilites 
    WHERE appartement_id = :id 
    ORDER BY date_debut ASC
");
$requete->execute([':id' => $id_appartement]);
$disponibilites = $requete->fetchAll(PDO::FETCH_ASSOC);

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier des Disponibilités - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1><i class="bi bi-calendar3"></i> Calendrier des Disponibilités</h1>
                        <p class="text-muted">Appartement : <?php echo htmlspecialchars($appartement['categorie']); ?></p>
                    </div>
                    <div>
                        <a href="liste-appartements.php" class="btn btn-outline-primary me-2">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                            <i class="bi bi-plus-lg"></i> Ajouter une période
                        </button>
                    </div>
                </div>

                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> Opération réussie !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <div class="card mt-4">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'ajout de période -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une période</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="ajouter-disponibilite.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="appartement_id" value="<?php echo $id_appartement; ?>">
                        <div class="mb-3">
                            <label for="date_debut" class="form-label">Date de début</label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_fin" class="form-label">Date de fin</label>
                            <input type="date" class="form-control" id="date_fin" name="date_fin" required>
                        </div>
                        <div class="mb-3">
                            <label for="statut" class="form-label">Statut</label>
                            <select class="form-select" id="statut" name="statut" required>
                                <option value="disponible">Disponible</option>
                                <option value="reserve">Réservé</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: <?php 
                    $events = array_map(function($d) {
                        $color = '';
                        switch($d['statut']) {
                            case 'disponible':
                                $color = '#28a745';
                                break;
                            case 'reserve':
                                $color = '#dc3545';
                                break;
                            case 'maintenance':
                                $color = '#ffc107';
                                break;
                        }
                        return [
                            'title' => ucfirst($d['statut']),
                            'start' => $d['date_debut'],
                            'end' => date('Y-m-d', strtotime($d['date_fin'] . ' +1 day')),
                            'backgroundColor' => $color,
                            'borderColor' => $color,
                            'id' => $d['id']
                        ];
                    }, $disponibilites);
                    echo json_encode($events);
                ?>,
                eventClick: function(info) {
                    if (confirm('Voulez-vous supprimer cette période ?')) {
                        window.location.href = 'supprimer-disponibilite.php?id=' + info.event.id + '&appartement_id=<?php echo $id_appartement; ?>';
                    }
                }
            });
            calendar.render();
        });
    </script>
</body>
</html>
