<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

$equipement_id = $_GET['equipement_id'] ?? null;

if (!$equipement_id) {
    header('Location: gestion-maintenance.php');
    exit();
}

// Récupérer les informations de l'équipement
$requete = $connexion->prepare("
    SELECT e.*, a.categorie as appartement_nom
    FROM equipements e
    JOIN appartements a ON e.appartement_id = a.Id_appartements
    WHERE e.id = :id
");
$requete->execute([':id' => $equipement_id]);
$equipement = $requete->fetch(PDO::FETCH_ASSOC);

if (!$equipement) {
    header('Location: gestion-maintenance.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Créer une nouvelle tâche de maintenance
        $requete = $connexion->prepare("
            INSERT INTO maintenance (
                appartement_id,
                titre,
                description,
                type,
                priorite,
                date_debut,
                date_fin,
                cout_estime,
                responsable_id
            ) VALUES (
                :appartement_id,
                :titre,
                :description,
                'inspection',
                :priorite,
                :date_debut,
                :date_fin,
                :cout_estime,
                :responsable_id
            )
        ");

        $requete->execute([
            ':appartement_id' => $equipement['appartement_id'],
            ':titre' => "Maintenance " . $equipement['type'] . " - " . $equipement['nom'],
            ':description' => $_POST['description'],
            ':priorite' => $_POST['priorite'],
            ':date_debut' => $_POST['date_debut'],
            ':date_fin' => $_POST['date_fin'],
            ':cout_estime' => $_POST['cout_estime'] ?: null,
            ':responsable_id' => $_POST['responsable_id'] ?: null
        ]);

        // Mettre à jour le statut de l'équipement
        $requete = $connexion->prepare("
            UPDATE equipements
            SET statut = 'fonctionnel'
            WHERE id = :id
        ");
        $requete->execute([':id' => $equipement_id]);

        header('Location: gestion-maintenance.php?success=1');
        exit();
    } catch (PDOException $e) {
        $error = "Une erreur est survenue lors de la planification de la maintenance.";
    }
}

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planifier une maintenance - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title mb-4">
                                    <i class="bi bi-calendar-check"></i> 
                                    Planifier une maintenance
                                </h2>

                                <?php if (isset($error)): ?>
                                <div class="alert alert-danger">
                                    <?php echo $error; ?>
                                </div>
                                <?php endif; ?>

                                <div class="alert alert-info">
                                    <h5>Détails de l'équipement</h5>
                                    <p><strong>Nom :</strong> <?php echo htmlspecialchars($equipement['nom']); ?></p>
                                    <p><strong>Type :</strong> <?php echo ucfirst($equipement['type']); ?></p>
                                    <p><strong>Appartement :</strong> <?php echo htmlspecialchars($equipement['appartement_nom']); ?></p>
                                    <?php if ($equipement['date_derniere_maintenance']): ?>
                                    <p><strong>Dernière maintenance :</strong> 
                                        <?php echo date('d/m/Y', strtotime($equipement['date_derniere_maintenance'])); ?>
                                    </p>
                                    <?php endif; ?>
                                </div>

                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description des travaux à effectuer</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="date_debut" class="form-label">Date de début</label>
                                                <input type="date" class="form-control" id="date_debut" name="date_debut" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="date_fin" class="form-label">Date de fin prévue</label>
                                                <input type="date" class="form-control" id="date_fin" name="date_fin" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="priorite" class="form-label">Priorité</label>
                                        <select class="form-select" id="priorite" name="priorite" required>
                                            <option value="basse">Basse</option>
                                            <option value="moyenne" selected>Moyenne</option>
                                            <option value="haute">Haute</option>
                                            <option value="urgente">Urgente</option>
                                        </select>
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
                                            $requete = $connexion->query("
                                                SELECT Id_users, firstname, lastname 
                                                FROM users 
                                                WHERE role = 'admin' 
                                                ORDER BY lastname, firstname
                                            ");
                                            while ($user = $requete->fetch()) {
                                                echo '<option value="' . $user['Id_users'] . '">' . 
                                                     htmlspecialchars($user['lastname'] . ' ' . $user['firstname']) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <a href="gestion-maintenance.php" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left"></i> Retour
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-calendar-check"></i> Planifier la maintenance
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Définir la date minimale à aujourd'hui
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date_debut').min = today;
        document.getElementById('date_fin').min = today;
        
        // S'assurer que la date de fin est après la date de début
        document.getElementById('date_debut').addEventListener('change', function() {
            document.getElementById('date_fin').min = this.value;
        });
    </script>
</body>
</html>
