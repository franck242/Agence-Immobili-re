<?php
session_start();
require_once 'connexion-bdd.php';
require_once 'authentification.php';

// Récupérer les types d'appartements
$query = "SELECT * FROM type_appartements";
$stmt = $connexion->query($query);
$types = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = '';
$type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Récupération des données du formulaire
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $prix = $_POST['prix'];
        $surface = $_POST['surface'];
        $chambres = $_POST['chambres'];
        $ville = $_POST['ville'];
        $adresse = $_POST['adresse'];
        $statut = $_POST['statut'];
        
        // Insertion dans la base de données
        $stmt = $connexion->prepare("INSERT INTO appartements (titre, description, prix, surface, chambres, ville, adresse, statut) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$titre, $description, $prix, $surface, $chambres, $ville, $adresse, $statut])) {
            $appart_id = $connexion->lastInsertId();
            
            // Traitement des images
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $uploadDir = '../uploads/appartements/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    $file_name = $_FILES['images']['name'][$key];
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $new_name = uniqid() . '.' . $file_ext;
                    
                    if (move_uploaded_file($tmp_name, $uploadDir . $new_name)) {
                        $stmt = $connexion->prepare("INSERT INTO images_appartements (appartement_id, image_url) VALUES (?, ?)");
                        $stmt->execute([$appart_id, $new_name]);
                    }
                }
            }
            
            $message = "Appartement ajouté avec succès !";
            $type = "success";
        }
    } catch(PDOException $e) {
        $message = "Erreur lors de l'ajout de l'appartement.";
        $type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Appartement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/ajouter-appartement.css">
</head>
<body>
    <?php include_once 'menu.php'; ?>

    <div class="content-wrapper">
        <div class="form-container">
            <h1 class="form-title">Ajouter un Appartement</h1>
            
            <?php if (!empty($message)): ?>
                <div class="<?php echo $type ?>-message">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-section">
                    <h3>Informations Principales</h3>
                    <div class="input-group">
                        <label for="titre">Titre de l'annonce</label>
                        <input type="text" id="titre" name="titre" required>
                    </div>

                    <div class="input-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <label for="prix">Prix (€)</label>
                                <input type="number" id="prix" name="prix" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <label for="surface">Surface (m²)</label>
                                <input type="number" id="surface" name="surface" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Caractéristiques</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <label for="chambres">Nombre de chambres</label>
                                <input type="number" id="chambres" name="chambres" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <label for="statut">Statut</label>
                                <select id="statut" name="statut" required>
                                    <option value="disponible">Disponible</option>
                                    <option value="vendu">Vendu</option>
                                    <option value="reserve">Réservé</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Localisation</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <label for="ville">Ville</label>
                                <input type="text" id="ville" name="ville" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <label for="adresse">Adresse</label>
                                <input type="text" id="adresse" name="adresse" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Images</h3>
                    <div class="input-group">
                        <label for="images">Sélectionner des images</label>
                        <input type="file" id="images" name="images[]" multiple accept="image/*" class="form-control">
                    </div>
                    <div class="image-preview" id="imagePreview"></div>
                </div>

                <button type="submit" class="submit-btn">Ajouter l'appartement</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Prévisualisation des images
        document.getElementById('images').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            
            [...e.target.files].forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'preview-item';
                    div.innerHTML = `
                        <img src="${e.target.result}">
                        <button type="button" class="remove-image">&times;</button>
                    `;
                    preview.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        });

        // Animation des sections au scroll
        const sections = document.querySelectorAll('.form-section');
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        });

        sections.forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            section.style.transition = 'all 0.5s ease-out';
            observer.observe(section);
        });
    </script>
</body>
</html>
