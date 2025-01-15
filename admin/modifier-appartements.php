<!Doctype HTML>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'Appartement - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
</head>

<body>
    <?php
    require_once("menu.php");
    require_once("connexion-bdd.php");
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['admin_id'])) {
        header('Location: ../connexion.php');
        exit();
    }

    // Vérifier si l'ID de l'appartement est fourni dans l'URL
    if (!isset($_GET['apt'])) {
        // Si pas d'ID fourni, rediriger vers la liste des appartements
        header('Location: gestion-appartements.php');
        exit();
    }

    $id_appartement = $_GET['apt'];
    $requete = $connexion->prepare("SELECT * FROM appartements WHERE Id_appartements = ?");
    $requete->execute([$id_appartement]);
    $resultat = $requete->fetch(PDO::FETCH_ASSOC);

    if (!$resultat) {
        // Si l'appartement n'existe pas, rediriger avec un message d'erreur
        $_SESSION['error'] = "L'appartement demandé n'existe pas.";
        header('Location: gestion-appartements.php');
        exit();
    }

    // Assigner les valeurs
    $photos = $resultat['photos'];
    $categorie = $resultat['categorie'];
    $superficie = $resultat['superficie'];
    $lits = $resultat['lits'];
    $salle_de_bains = $resultat['salle_de_bains'];
    $status = $resultat['status'];
    $prix = $resultat['prix'];
    $adresse = $resultat['adresse'];
    $ville = $resultat['ville'];
    $pays = $resultat['pays'];
    ?>

    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1><i class="bi bi-building"></i> Modifier l'Appartement</h1>
                        <p class="text-muted">Modifiez les informations de l'appartement</p>
                    </div>
                    <div>
                        <a href="gestion-appartements.php" class="btn btn-outline-primary btn-ripple">
                            <i class="bi bi-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-4 slide-in">
                <div class="card-body">
                    <form action="traitement-modifier-appartements.php?apt=<?php echo $id_appartement; ?>" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="photos" class="form-label">Photo</label>
                                <input type="file" class="form-control" id="photos" name="photos" accept="image/png, image/jpeg" value="<?php echo isset($photos) ? $photos : ''; ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="categorie" class="form-label">Catégorie</label>
                                <input type="text" class="form-control" id="categorie" name="categorie" value="<?php echo isset($categorie) ? htmlspecialchars($categorie) : ''; ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="superficie" class="form-label">Superficie</label>
                                <input type="text" class="form-control" id="superficie" name="superficie" value="<?php echo isset($superficie) ? htmlspecialchars($superficie) : ''; ?>" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="lits" class="form-label">Nombre de lits</label>
                                <input type="number" class="form-control" id="lits" name="lits" min="1" max="5" value="<?php echo isset($lits) ? htmlspecialchars($lits) : ''; ?>" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="salle_de_bain" class="form-label">Salles de bains</label>
                                <input type="number" class="form-control" id="salle_de_bain" name="salle_de_bains" min="1" max="5" value="<?php echo isset($salle_de_bains) ? htmlspecialchars($salle_de_bains) : ''; ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Statut</label>
                                <input type="text" class="form-control" id="status" name="status" value="<?php echo isset($status) ? htmlspecialchars($status) : ''; ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="prix" class="form-label">Prix</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="prix" name="prix" value="<?php echo isset($prix) ? htmlspecialchars($prix) : ''; ?>" required>
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="adresse" class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo isset($adresse) ? htmlspecialchars($adresse) : ''; ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ville" class="form-label">Ville</label>
                                <input type="text" class="form-control" id="ville" name="ville" value="<?php echo isset($ville) ? htmlspecialchars($ville) : ''; ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="pays" class="form-label">Pays</label>
                                <input type="text" class="form-control" id="pays" name="pays" value="<?php echo isset($pays) ? htmlspecialchars($pays) : ''; ?>" required>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary btn-ripple">
                                <i class="bi bi-check-lg"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
    <script>
        // Activation de la validation des formulaires Bootstrap
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>

</html>