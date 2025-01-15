<?php
session_start();
require_once("connexion-bdd.php");
require_once("authentification.php");

$message = '';
$type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        if ($_POST['new_password'] === $_POST['confirm_password']) {
            $new_password = sha1($_POST['new_password']);
            
            try {
                $stmt = $connexion->prepare("UPDATE admin SET password = ? WHERE Id_admin = ?");
                if ($stmt->execute([$new_password, $_SESSION['admin_id']])) {
                    $message = "Mot de passe modifié avec succès !";
                    $type = "success";
                } else {
                    $message = "Erreur lors de la modification du mot de passe.";
                    $type = "danger";
                }
            } catch (PDOException $e) {
                $message = "Une erreur est survenue.";
                $type = "danger";
            }
        } else {
            $message = "Les mots de passe ne correspondent pas.";
            $type = "danger";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
        $type = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer le mot de passe admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <?php include_once('menu.php'); ?>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Changer le mot de passe</h2>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?php echo $type; ?> mb-3">
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control" name="new_password" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" name="confirm_password" required>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
