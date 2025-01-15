<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: liste-appartements.php');
    exit();
}

$id = $_GET['id'];
$requete = $connexion->prepare("SELECT * FROM appartements WHERE Id_appartements = ?");
$requete->execute([$id]);
$appartement = $requete->fetch(PDO::FETCH_ASSOC);

if (!$appartement) {
    header('Location: liste-appartements.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categorie = $_POST['categorie'];
    $superficie = $_POST['superficie'];
    $lits = $_POST['lits'];
    $salle_de_bains = $_POST['salle_de_bains'];
    $prix = $_POST['prix'];
    $ville = $_POST['ville'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Gestion de l'upload de la nouvelle image
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photo = $_FILES['photo'];
        $extension = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($extension, $allowed)) {
            $nouveau_nom = uniqid() . '.' . $extension;
            if (move_uploaded_file($photo['tmp_name'], "../img/" . $nouveau_nom)) {
                // Supprimer l'ancienne image si elle existe
                if (!empty($appartement['photos'])) {
                    $ancien_fichier = "../img/" . $appartement['photos'];
                    if (file_exists($ancien_fichier)) {
                        unlink($ancien_fichier);
                    }
                }
                $photos = $nouveau_nom;
            }
        }
    } else {
        $photos = $appartement['photos']; // Garder l'ancienne image
    }

    $requete = $connexion->prepare("
        UPDATE appartements 
        SET categorie = ?, superficie = ?, lits = ?, salle_de_bains = ?, 
            prix = ?, ville = ?, description = ?, status = ?, photos = ?
        WHERE Id_appartements = ?
    ");
    
    if ($requete->execute([
        $categorie, $superficie, $lits, $salle_de_bains, 
        $prix, $ville, $description, $status, $photos, $id
    ])) {
        header('Location: liste-appartements.php?success=1');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Appartement - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <?php include_once("menu.php"); ?>
    
    <div id="main">
        <div class="container-fluid">
            <div class="welcome-section">
                <div class="d-flex justify-content-between align-items-center">
                    <h1><i class="bi bi-pencil-square"></i> Modifier l'Appartement</h1>
                    <a href="liste-appartements.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="categorie" class="form-label">Catégorie</label>
                                    <input type="text" class="form-control" id="categorie" name="categorie" 
                                           value="<?php echo htmlspecialchars($appartement['categorie']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="superficie" class="form-label">Superficie (m²)</label>
                                    <input type="number" class="form-control" id="superficie" name="superficie" 
                                           value="<?php echo htmlspecialchars($appartement['superficie']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="lits" class="form-label">Nombre de chambres</label>
                                    <input type="number" class="form-control" id="lits" name="lits" 
                                           value="<?php echo htmlspecialchars($appartement['lits']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="salle_de_bains" class="form-label">Nombre de salles de bain</label>
                                    <input type="number" class="form-control" id="salle_de_bains" name="salle_de_bains" 
                                           value="<?php echo htmlspecialchars($appartement['salle_de_bains']); ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="prix" class="form-label">Prix (€/mois)</label>
                                    <input type="number" class="form-control" id="prix" name="prix" 
                                           value="<?php echo htmlspecialchars($appartement['prix']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="ville" class="form-label">Ville</label>
                                    <input type="text" class="form-control" id="ville" name="ville" 
                                           value="<?php echo htmlspecialchars($appartement['ville']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="0" <?php echo $appartement['status'] == 0 ? 'selected' : ''; ?>>Disponible</option>
                                        <option value="1" <?php echo $appartement['status'] == 1 ? 'selected' : ''; ?>>Non disponible</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="photo" class="form-label">Photo</label>
                                    <input type="file" class="form-control" id="photo" name="photo">
                                    <?php if (!empty($appartement['photos'])): ?>
                                        <div class="mt-2">
                                            <img src="../img/<?php echo htmlspecialchars($appartement['photos']); ?>" 
                                                 alt="Photo actuelle" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($appartement['description']); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
