<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $connexion->prepare("SELECT * FROM demandes_contact WHERE id = ?");
    $stmt->execute([$id]);
    $message = $stmt->fetch();

    if (!$message) {
        header('Location: gestion-messages.php');
        exit();
    }
} else {
    header('Location: gestion-messages.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $message_text = $_POST['message'];
    
    try {
        $stmt = $connexion->prepare("UPDATE demandes_contact SET email = ?, message = ? WHERE id = ?");
        $stmt->execute([$email, $message_text, $id]);
        
        header('Location: gestion-messages.php?success=1');
        exit();
    } catch(PDOException $e) {
        $error = "Une erreur est survenue lors de la modification.";
    }
}

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Message - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <h1><i class="bi bi-pencil"></i> Modifier le Message</h1>
                <p>Modifier les informations du message</p>
            </div>

            <div class="card mt-4 slide-in">
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" class="fade-in">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($message['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" 
                                      rows="5" required><?php echo htmlspecialchars($message['message']); ?></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="gestion-messages.php" class="btn btn-secondary btn-ripple">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary btn-ripple">
                                <i class="bi bi-save"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
</body>
</html>
