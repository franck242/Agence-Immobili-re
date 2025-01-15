<?php
require_once("connexion-bdd.php");
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: gestion-utilisateurs.php');
    exit();
}

$id = $_GET['id'];
$message = '';

// Récupérer les informations de l'utilisateur
$requete = $connexion->prepare("SELECT * FROM users WHERE Id_users = ?");
$requete->execute([$id]);
$utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    header('Location: gestion-utilisateurs.php');
    exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = isset($_POST['status']) ? 1 : 0;

    $requete = $connexion->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, role = ?, status = ? WHERE Id_users = ?");
    if ($requete->execute([$firstname, $lastname, $email, $role, $status, $id])) {
        $message = "Modifications enregistrées avec succès";
        // Recharger les informations
        $requete = $connexion->prepare("SELECT * FROM users WHERE Id_users = ?");
        $requete->execute([$id]);
        $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);
    } else {
        $message = "Erreur lors de la modification";
    }
}

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un compte - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div id="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1>Modifier un compte</h1>
                        <a href="gestion-utilisateurs.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                    </div>

                    <?php if ($message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstname" class="form-label">Prénom</label>
                                            <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($utilisateur['firstname']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="lastname" class="form-label">Nom</label>
                                            <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($utilisateur['lastname']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($utilisateur['email']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Rôle</label>
                                            <select class="form-select" id="role" name="role">
                                                <option value="user" <?php echo (isset($utilisateur['role']) && $utilisateur['role'] == 'user') ? 'selected' : ''; ?>>Utilisateur</option>
                                                <option value="manager" <?php echo (isset($utilisateur['role']) && $utilisateur['role'] == 'manager') ? 'selected' : ''; ?>>Manager</option>
                                                <option value="admin" <?php echo (isset($utilisateur['role']) && $utilisateur['role'] == 'admin') ? 'selected' : ''; ?>>Administrateur</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="status" name="status" <?php echo (isset($utilisateur['status']) && $utilisateur['status']) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="status">Compte actif</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>