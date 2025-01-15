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

// Récupérer toutes les images de l'appartement
$requete = $connexion->prepare("SELECT * FROM images_appartements WHERE appartement_id = :id ORDER BY is_primary DESC");
$requete->execute([':id' => $id_appartement]);
$images = $requete->fetchAll(PDO::FETCH_ASSOC);

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Images - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
    <style>
        .image-container {
            position: relative;
            margin-bottom: 20px;
        }
        .image-container img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        .image-actions {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            padding: 5px;
            border-radius: 4px;
        }
        .primary-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(40,167,69,0.9);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1><i class="bi bi-images"></i> Gérer les Images</h1>
                        <p class="text-muted">Appartement : <?php echo htmlspecialchars($appartement['categorie']); ?></p>
                    </div>
                    <div>
                        <a href="liste-appartements.php" class="btn btn-outline-primary me-2">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="bi bi-upload"></i> Ajouter des images
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
                        <div class="row">
                            <?php foreach ($images as $image): ?>
                            <div class="col-md-4">
                                <div class="image-container">
                                    <img src="uploads-images/<?php echo htmlspecialchars($image['image_path']); ?>" 
                                         alt="Image appartement">
                                    <?php if ($image['is_primary']): ?>
                                    <div class="primary-badge">
                                        <i class="bi bi-star-fill"></i> Image principale
                                    </div>
                                    <?php endif; ?>
                                    <div class="image-actions">
                                        <?php if (!$image['is_primary']): ?>
                                        <button class="btn btn-sm btn-success" onclick="definirPrincipale(<?php echo $image['id']; ?>)">
                                            <i class="bi bi-star"></i>
                                        </button>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-danger" onclick="supprimerImage(<?php echo $image['id']; ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'upload -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter des images</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="upload-images.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="appartement_id" value="<?php echo $id_appartement; ?>">
                        <div class="mb-3">
                            <label for="images" class="form-label">Sélectionner des images</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Uploader</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function definirPrincipale(imageId) {
            if (confirm('Définir cette image comme image principale ?')) {
                window.location.href = 'definir-image-principale.php?id=' + imageId + '&appartement_id=<?php echo $id_appartement; ?>';
            }
        }

        function supprimerImage(imageId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
                window.location.href = 'supprimer-image.php?id=' + imageId + '&appartement_id=<?php echo $id_appartement; ?>';
            }
        }
    </script>
</body>
</html>
