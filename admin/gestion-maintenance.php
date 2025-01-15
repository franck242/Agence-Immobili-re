<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

// Récupérer toutes les tâches de maintenance avec les détails
$requete = $connexion->prepare("
    SELECT m.*,
           a.categorie as appartement_nom,
           a.photos as appartement_photo,
           u.firstname as responsable_prenom,
           u.lastname as responsable_nom,
           (
               SELECT COUNT(*)
               FROM interventions_maintenance
               WHERE maintenance_id = m.id
           ) as nombre_interventions
    FROM maintenance m
    LEFT JOIN appartements a ON m.appartement_id = a.Id_appartements
    LEFT JOIN users u ON m.responsable_id = u.Id_users
    ORDER BY 
        CASE m.priorite
            WHEN 'urgente' THEN 1
            WHEN 'haute' THEN 2
            WHEN 'moyenne' THEN 3
            WHEN 'basse' THEN 4
        END,
        m.date_creation DESC
");
$requete->execute();
$taches_maintenance = $requete->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les équipements qui nécessitent une maintenance
$requete = $connexion->prepare("
    SELECT e.*,
           a.categorie as appartement_nom
    FROM equipements e
    JOIN appartements a ON e.appartement_id = a.Id_appartements
    WHERE e.statut = 'a_verifier'
    OR (
        e.frequence_maintenance IS NOT NULL
        AND e.date_derniere_maintenance IS NOT NULL
        AND DATE_ADD(e.date_derniere_maintenance, INTERVAL e.frequence_maintenance DAY) <= CURRENT_DATE
    )
    ORDER BY e.date_derniere_maintenance ASC
");
$requete->execute();
$equipements_maintenance = $requete->fetchAll(PDO::FETCH_ASSOC);

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de la Maintenance - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
    <style>
        .priority-badge {
            width: 100px;
        }
        .task-card {
            transition: transform 0.2s;
        }
        .task-card:hover {
            transform: translateY(-5px);
        }
        .equipment-alert {
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1><i class="bi bi-tools"></i> Gestion de la Maintenance</h1>
                        <p class="text-muted">Suivi des tâches de maintenance et des équipements</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                            <i class="bi bi-plus-lg"></i> Nouvelle tâche
                        </button>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                            <i class="bi bi-plus-lg"></i> Nouvel équipement
                        </button>
                    </div>
                </div>

                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> Opération réussie !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <!-- Alertes équipements -->
                <?php if (!empty($equipements_maintenance)): ?>
                <div class="alert alert-warning equipment-alert mb-4">
                    <h5><i class="bi bi-exclamation-triangle"></i> Équipements nécessitant une maintenance</h5>
                    <div class="row">
                        <?php foreach ($equipements_maintenance as $equip): ?>
                        <div class="col-md-4 mb-2">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-gear me-2"></i>
                                <div>
                                    <strong><?php echo htmlspecialchars($equip['nom']); ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <?php echo htmlspecialchars($equip['appartement_nom']); ?>
                                        -
                                        Dernière maintenance : 
                                        <?php echo $equip['date_derniere_maintenance'] ? date('d/m/Y', strtotime($equip['date_derniere_maintenance'])) : 'Jamais'; ?>
                                    </small>
                                </div>
                                <button class="btn btn-sm btn-outline-primary ms-auto"
                                        onclick="planifierMaintenance(<?php echo $equip['id']; ?>)">
                                    Planifier
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Filtres -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="filter-form" class="row g-3">
                            <div class="col-md-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" id="type">
                                    <option value="">Tous</option>
                                    <option value="reparation">Réparation</option>
                                    <option value="nettoyage">Nettoyage</option>
                                    <option value="renovation">Rénovation</option>
                                    <option value="inspection">Inspection</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="priorite" class="form-label">Priorité</label>
                                <select class="form-select" id="priorite">
                                    <option value="">Toutes</option>
                                    <option value="urgente">Urgente</option>
                                    <option value="haute">Haute</option>
                                    <option value="moyenne">Moyenne</option>
                                    <option value="basse">Basse</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="statut" class="form-label">Statut</label>
                                <select class="form-select" id="statut">
                                    <option value="">Tous</option>
                                    <option value="a_faire">À faire</option>
                                    <option value="en_cours">En cours</option>
                                    <option value="termine">Terminé</option>
                                    <option value="annule">Annulé</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search"></i> Filtrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Liste des tâches -->
                <div class="row">
                    <?php foreach ($taches_maintenance as $tache): ?>
                    <div class="col-md-6 mb-4 task-item" 
                         data-type="<?php echo $tache['type']; ?>"
                         data-priorite="<?php echo $tache['priorite']; ?>"
                         data-statut="<?php echo $tache['statut']; ?>">
                        <div class="card h-100 task-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($tache['titre']); ?></h5>
                                    <?php
                                    $priority_class = '';
                                    switch ($tache['priorite']) {
                                        case 'urgente':
                                            $priority_class = 'bg-danger';
                                            break;
                                        case 'haute':
                                            $priority_class = 'bg-warning';
                                            break;
                                        case 'moyenne':
                                            $priority_class = 'bg-info';
                                            break;
                                        case 'basse':
                                            $priority_class = 'bg-success';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $priority_class; ?> priority-badge">
                                        <?php echo ucfirst($tache['priorite']); ?>
                                    </span>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="uploads-images/<?php echo htmlspecialchars($tache['appartement_photo']); ?>" 
                                             alt="Appartement"
                                             class="me-2"
                                             style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                        <div>
                                            <strong><?php echo htmlspecialchars($tache['appartement_nom']); ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                Type : <?php echo ucfirst($tache['type']); ?>
                                            </small>
                                        </div>
                                    </div>
                                    <p class="card-text"><?php echo nl2br(htmlspecialchars($tache['description'])); ?></p>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">
                                            Responsable : 
                                            <?php 
                                            echo $tache['responsable_prenom'] ? 
                                                htmlspecialchars($tache['responsable_prenom'] . ' ' . $tache['responsable_nom']) : 
                                                'Non assigné';
                                            ?>
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            Interventions : <?php echo $tache['nombre_interventions']; ?>
                                        </small>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="ajouterIntervention(<?php echo $tache['id']; ?>)">
                                            <i class="bi bi-plus-lg"></i> Intervention
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                onclick="changerStatut(<?php echo $tache['id']; ?>)">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="supprimerTache(<?php echo $tache['id']; ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nouvelle Tâche -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouvelle tâche de maintenance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="ajouter-maintenance.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="titre" name="titre" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="appartement_id" class="form-label">Appartement</label>
                            <select class="form-select" id="appartement_id" name="appartement_id" required>
                                <?php
                                $requete = $connexion->query("SELECT Id_appartements, categorie FROM appartements ORDER BY categorie");
                                while ($appart = $requete->fetch()) {
                                    echo '<option value="' . $appart['Id_appartements'] . '">' . 
                                         htmlspecialchars($appart['categorie']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="reparation">Réparation</option>
                                        <option value="nettoyage">Nettoyage</option>
                                        <option value="renovation">Rénovation</option>
                                        <option value="inspection">Inspection</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priorite" class="form-label">Priorité</label>
                                    <select class="form-select" id="priorite" name="priorite" required>
                                        <option value="basse">Basse</option>
                                        <option value="moyenne">Moyenne</option>
                                        <option value="haute">Haute</option>
                                        <option value="urgente">Urgente</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_debut" class="form-label">Date de début</label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_fin" class="form-label">Date de fin prévue</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="cout_estime" class="form-label">Coût estimé (€)</label>
                            <input type="number" step="0.01" class="form-control" id="cout_estime" name="cout_estime">
                        </div>
                        <div class="mb-3">
                            <label for="responsable_id" class="form-label">Responsable</label>
                            <select class="form-select" id="responsable_id" name="responsable_id">
                                <option value="">Sélectionner un responsable</option>
                                <?php
                                $requete = $connexion->query("SELECT Id_users, firstname, lastname FROM users WHERE role = 'admin' ORDER BY lastname, firstname");
                                while ($user = $requete->fetch()) {
                                    echo '<option value="' . $user['Id_users'] . '">' . 
                                         htmlspecialchars($user['lastname'] . ' ' . $user['firstname']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filtrage des tâches
        document.getElementById('filter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const type = document.getElementById('type').value;
            const priorite = document.getElementById('priorite').value;
            const statut = document.getElementById('statut').value;
            
            document.querySelectorAll('.task-item').forEach(item => {
                let show = true;
                
                if (type && item.dataset.type !== type) {
                    show = false;
                }
                if (priorite && item.dataset.priorite !== priorite) {
                    show = false;
                }
                if (statut && item.dataset.statut !== statut) {
                    show = false;
                }
                
                item.style.display = show ? 'block' : 'none';
            });
        });

        function ajouterIntervention(id) {
            window.location.href = 'ajouter-intervention.php?id=' + id;
        }

        function changerStatut(id) {
            window.location.href = 'changer-statut-maintenance.php?id=' + id;
        }

        function supprimerTache(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) {
                window.location.href = 'supprimer-maintenance.php?id=' + id;
            }
        }

        function planifierMaintenance(equipement_id) {
            window.location.href = 'planifier-maintenance.php?equipement_id=' + equipement_id;
        }
    </script>
</body>
</html>
