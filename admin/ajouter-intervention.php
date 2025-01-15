<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

$maintenance_id = $_GET['id'] ?? null;

if (!$maintenance_id) {
    header('Location: gestion-maintenance.php');
    exit();
}

// Récupérer les informations de la tâche de maintenance
$requete = $connexion->prepare("
    SELECT m.*, a.categorie as appartement_nom
    FROM maintenance m
    JOIN appartements a ON m.appartement_id = a.Id_appartements
    WHERE m.id = :id
");
$requete->execute([':id' => $maintenance_id]);
$maintenance = $requete->fetch(PDO::FETCH_ASSOC);

if (!$maintenance) {
    header('Location: gestion-maintenance.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $upload_dir = "factures/";
        $facture_path = null;
        
        if (isset($_FILES['facture']) && $_FILES['facture']['error'] === UPLOAD_ERR_OK) {
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $tmp_name = $_FILES['facture']['tmp_name'];
            $name = $_FILES['facture']['name'];
            $facture_path = uniqid() . '_' . $name;
            
            move_uploaded_file($tmp_name, $upload_dir . $facture_path);
        }
        
        $requete = $connexion->prepare("
            INSERT INTO interventions_maintenance (
                maintenance_id,
                intervenant,
                date_intervention,
                description,
                cout,
                facture_path
            ) VALUES (
                :maintenance_id,
                :intervenant,
                :date_intervention,
                :description,
                :cout,
                :facture_path
            )
        ");

        $requete->execute([
            ':maintenance_id' => $maintenance_id,
            ':intervenant' => $_POST['intervenant'],
            ':date_intervention' => $_POST['date_intervention'],
            ':description' => $_POST['description'],
            ':cout' => $_POST['cout'] ?: null,
            ':facture_path' => $facture_path
        ]);

        // Mettre à jour le statut de la tâche si nécessaire
        if (isset($_POST['terminer']) && $_POST['terminer'] === '1') {
            $requete = $connexion->prepare("
                UPDATE maintenance
                SET statut = 'termine',
                    cout_reel = (
                        SELECT SUM(cout)
                        FROM interventions_maintenance
                        WHERE maintenance_id = :id
                    )
                WHERE id = :id
            ");
            $requete->execute([':id' => $maintenance_id]);
        }

        header('Location: gestion-maintenance.php?success=1');
        exit();
    } catch (PDOException $e) {
        $error = "Une erreur est survenue lors de l'enregistrement de l'intervention.";
    }
}

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une intervention - Admin</title>
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
                                    <i class="bi bi-tools"></i> 
                                    Nouvelle intervention
                                </h2>

                                <?php if (isset($error)): ?>
                                <div class="alert alert-danger">
                                    <?php echo $error; ?>
                                </div>
                                <?php endif; ?>

                                <div class="alert alert-info">
                                    <h5>Détails de la tâche</h5>
                                    <p><strong>Titre :</strong> <?php echo htmlspecialchars($maintenance['titre']); ?></p>
                                    <p><strong>Appartement :</strong> <?php echo htmlspecialchars($maintenance['appartement_nom']); ?></p>
                                    <p><strong>Type :</strong> <?php echo ucfirst($maintenance['type']); ?></p>
                                </div>

                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="intervenant" class="form-label">Intervenant</label>
                                        <input type="text" class="form-control" id="intervenant" name="intervenant" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="date_intervention" class="form-label">Date d'intervention</label>
                                        <input type="datetime-local" class="form-control" id="date_intervention" name="date_intervention" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description des travaux</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="cout" class="form-label">Coût (€)</label>
                                        <input type="number" step="0.01" class="form-control" id="cout" name="cout">
                                    </div>

                                    <div class="mb-3">
                                        <label for="facture" class="form-label">Facture (PDF)</label>
                                        <input type="file" class="form-control" id="facture" name="facture" accept=".pdf">
                                    </div>

                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="terminer" name="terminer" value="1">
                                        <label class="form-check-label" for="terminer">
                                            Marquer la tâche comme terminée
                                        </label>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <a href="gestion-maintenance.php" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left"></i> Retour
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save"></i> Enregistrer l'intervention
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
</body>
</html>
