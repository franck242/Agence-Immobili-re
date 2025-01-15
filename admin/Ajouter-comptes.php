<?php
require_once("includes/auth.php");
require_once("connexion-bdd.php");
require_once("menu.php");

forcerUtilisateurConnecte();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';
    $status = isset($_POST['status']) ? 1 : 0;

    if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires";
    } else {
        // Vérifier si l'email existe déjà
        $check = $connexion->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetchColumn() > 0) {
            $error = "Cet email est déjà utilisé";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            try {
                $requete = $connexion->prepare("INSERT INTO users (firstname, lastname, email, password, role, status) VALUES (?, ?, ?, ?, ?, ?)");
                $requete->execute([$firstname, $lastname, $email, $hashed_password, $role, $status]);
                $message = "Compte créé avec succès";
                header("refresh:2;url=liste-comptes.php");
            } catch(PDOException $e) {
                $error = "Erreur lors de la création du compte";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un compte - Administration</title>
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
                        <h1>Ajouter un compte</h1>
                        <a href="liste-comptes.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                    </div>

                    <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($message): ?>
                    <div class="alert alert-success">
                        <?php echo $message; ?>
                    </div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstname" class="form-label">Prénom</label>
                                            <input type="text" class="form-control" id="firstname" name="firstname" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="lastname" class="form-label">Nom</label>
                                            <input type="text" class="form-control" id="lastname" name="lastname" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Mot de passe</label>
                                            <input type="password" class="form-control" id="password" name="password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Rôle</label>
                                            <select class="form-select" id="role" name="role">
                                                <option value="user">Utilisateur</option>
                                                <option value="manager">Manager</option>
                                                <option value="admin">Administrateur</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="status" name="status" checked>
                                                <label class="form-check-label" for="status">Compte actif</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-person-plus"></i> Créer le compte
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