<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

// Debug - Afficher les erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';
$type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Récupération des données du formulaire
        $categorie = $_POST['titre']; // Le champ s'appelle titre dans le formulaire mais categorie dans la BDD
        $description = $_POST['description'];
        $prix = $_POST['prix'];
        $superficie = $_POST['superficie'];
        $lits = $_POST['lits'];
        $salle_de_bains = $_POST['salle_de_bains'];
        $status = $_POST['status'];
        $ville = $_POST['ville'];
        $adresse = $_POST['adresse'];
        $pays = $_POST['pays'];
        
        // Insertion dans la base de données
        $stmt = $connexion->prepare("INSERT INTO appartements (categorie, description, prix, superficie, lits, salle_de_bains, status, ville, adresse, pays) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$categorie, $description, $prix, $superficie, $lits, $salle_de_bains, $status, $ville, $adresse, $pays])) {
            $appart_id = $connexion->lastInsertId();
            
            // Traitement des images
            if (isset($_FILES['photos']) && !empty($_FILES['photos']['name'])) {
                $uploadDir = 'uploads-images/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $file_name = $_FILES['photos']['name'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $new_name = uniqid() . '.' . $file_ext;
                
                if (move_uploaded_file($_FILES['photos']['tmp_name'], $uploadDir . $new_name)) {
                    $stmt = $connexion->prepare("UPDATE appartements SET photos = ? WHERE Id_appartements = ?");
                    if ($stmt->execute([$new_name, $appart_id])) {
                        header('Location: liste-appartements.php?success=1');
                        exit();
                    }
                }
            } else {
                header('Location: liste-appartements.php?success=1');
                exit();
            }
        }
    } catch(PDOException $e) {
        $message = "Erreur lors de l'ajout de l'appartement: " . $e->getMessage();
        $type = "error";
    }
}

include_once("menu.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Appartement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/ajouter-appartements.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
    <style>
        .form-group {
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
            transform: translateY(-2px);
        }
        .card {
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .btn-ripple {
            position: relative;
            overflow: hidden;
            transform: translate3d(0, 0, 0);
        }
        .btn-ripple:after {
            content: "";
            display: block;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            background-image: radial-gradient(circle, #fff 10%, transparent 10.01%);
            background-repeat: no-repeat;
            background-position: 50%;
            transform: scale(10, 10);
            opacity: 0;
            transition: transform .5s, opacity 1s;
        }
        .btn-ripple:active:after {
            transform: scale(0, 0);
            opacity: .3;
            transition: 0s;
        }
    </style>
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1><i class="bi bi-plus-circle"></i> Ajouter un Appartement</h1>
                        <p class="text-muted">Ajoutez un nouvel appartement à votre catalogue</p>
                    </div>
                </div>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $type === 'success' ? 'success' : 'danger'; ?> fade-in">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <div class="card mt-4 slide-in">
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="photos" class="form-label">
                                            <i class="bi bi-image"></i> Photo de l'appartement
                                        </label>
                                        <input type="file" class="form-control" id="photos" name="photos" accept="image/png, image/jpeg" required>
                                        <div id="imagePreview" class="mt-3 row g-3"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="titre" class="form-label">Titre de l'appartement</label>
                                        <input type="text" class="form-control" id="titre" name="titre" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="prix" class="form-label">
                                            <i class="bi bi-currency-euro"></i> Prix
                                        </label>
                                        <input type="number" class="form-control" id="prix" name="prix" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description" class="form-label">
                                            <i class="bi bi-text-paragraph"></i> Description
                                        </label>
                                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="superficie" class="form-label">
                                            <i class="bi bi-arrows-angle-expand"></i> Superficie (m²)
                                        </label>
                                        <input type="number" class="form-control" id="superficie" name="superficie" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="lits" class="form-label">
                                            <i class="bi bi-person-workspace"></i> Nombre de lits
                                        </label>
                                        <input type="number" class="form-control" id="lits" name="lits" min="1" max="5" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="salle_de_bains" class="form-label">
                                            <i class="bi bi-water"></i> Salles de bain
                                        </label>
                                        <input type="number" class="form-control" id="salle_de_bains" name="salle_de_bains" min="1" max="5" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="status" class="form-label">
                                            <i class="bi bi-tag"></i> Statut
                                        </label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="0">Libre</option>
                                            <option value="1">Loué</option>
                                            <option value="2">En travaux</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="adresse" class="form-label">
                                            <i class="bi bi-geo-alt"></i> Adresse
                                        </label>
                                        <input type="text" class="form-control" id="adresse" name="adresse" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ville" class="form-label">
                                            <i class="bi bi-building"></i> Ville
                                        </label>
                                        <input type="text" class="form-control" id="ville" name="ville" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="pays" class="form-label">
                                            <i class="bi bi-globe"></i> Pays
                                        </label>
                                        <input type="text" class="form-control" id="pays" name="pays" required>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-ripple">
                                    <i class="bi bi-plus-circle"></i> Ajouter l'appartement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
    <script>
        // Prévisualisation des images
        document.getElementById('photos').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-4';
                    col.innerHTML = `
                        <div class="card">
                            <img src="${e.target.result}" class="card-img-top" alt="Aperçu" style="width: 50px; height: 50px;">
                            <div class="card-body">
                                <button type="button" class="btn btn-danger btn-sm w-100">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    `;
                    preview.appendChild(col);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>