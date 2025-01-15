<?php
session_start();
require_once("admin/connexion-bdd.php");

$step = isset($_GET['step']) ? $_GET['step'] : '1';
$email = isset($_SESSION['reset_email']) ? $_SESSION['reset_email'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($step == '1' && isset($_POST['email'])) {
        $email = htmlspecialchars(trim($_POST['email']));
        
        try {
            $query = $connexion->prepare("SELECT * FROM users WHERE email = ?");
            $query->execute([$email]);
            $user = $query->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $_SESSION['reset_email'] = $email;
                $_SESSION['security_question'] = $user['security_question'];
                header('Location: reset-password.php?step=2');
                exit();
            } else {
                $_SESSION['error'] = "Aucun compte trouvé avec cette adresse email";
            }
        } catch(PDOException $e) {
            $_SESSION['error'] = "Une erreur est survenue";
        }
    } 
    else if ($step == '2' && isset($_POST['security_answer'])) {
        $security_answer = sha1(strtolower(trim($_POST['security_answer'])));
        
        try {
            $query = $connexion->prepare("SELECT * FROM users WHERE email = ? AND security_answer = ?");
            $query->execute([$_SESSION['reset_email'], $security_answer]);
            $user = $query->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $_SESSION['reset_authorized'] = true;
                header('Location: reset-password.php?step=3');
                exit();
            } else {
                $_SESSION['error'] = "La réponse à la question de sécurité est incorrecte";
            }
        } catch(PDOException $e) {
            $_SESSION['error'] = "Une erreur est survenue";
        }
    }
    else if ($step == '3' && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        if (!isset($_SESSION['reset_authorized']) || !$_SESSION['reset_authorized']) {
            header('Location: reset-password.php');
            exit();
        }

        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (strlen($new_password) < 8) {
            $_SESSION['error'] = "Le mot de passe doit contenir au moins 8 caractères";
        }
        else if ($new_password !== $confirm_password) {
            $_SESSION['error'] = "Les mots de passe ne correspondent pas";
        }
        else {
            try {
                $hashed_password = sha1($new_password);
                $query = $connexion->prepare("UPDATE users SET password = ? WHERE email = ?");
                $query->execute([$hashed_password, $_SESSION['reset_email']]);

                // Nettoyage des variables de session
                unset($_SESSION['reset_email']);
                unset($_SESSION['security_question']);
                unset($_SESSION['reset_authorized']);

                $_SESSION['success'] = "Votre mot de passe a été réinitialisé avec succès";
                header('Location: connexion.php');
                exit();
            } catch(PDOException $e) {
                $_SESSION['error'] = "Une erreur est survenue lors de la réinitialisation du mot de passe";
            }
        }
    }
}

include('includes/header.php');
?>

<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">Réinitialisation du mot de passe</h2>
                </div>
                <div class="card-body">
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if($step == '1'): ?>
                        <form method="POST" action="reset-password.php?step=1" autocomplete="off">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required autocomplete="new-email" value="<?php echo htmlspecialchars($email); ?>">
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Continuer</button>
                            </div>
                        </form>

                    <?php elseif($step == '2' && isset($_SESSION['security_question'])): ?>
                        <p class="mb-3">Question de sécurité :</p>
                        <p class="fw-bold mb-4"><?php echo htmlspecialchars($_SESSION['security_question']); ?></p>
                        <form method="POST" action="reset-password.php?step=2" autocomplete="off">
                            <div class="mb-3">
                                <label for="security_answer" class="form-label">Votre réponse</label>
                                <input type="text" class="form-control" id="security_answer" name="security_answer" required autocomplete="off">
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Vérifier</button>
                            </div>
                        </form>

                    <?php elseif($step == '3' && isset($_SESSION['reset_authorized']) && $_SESSION['reset_authorized']): ?>
                        <form method="POST" action="reset-password.php?step=3" autocomplete="off">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required autocomplete="new-password">
                                <small class="form-text text-muted">Le mot de passe doit contenir au moins 8 caractères</small>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required autocomplete="new-password">
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Réinitialiser le mot de passe</button>
                            </div>
                        </form>

                    <?php else: ?>
                        <?php
                        // Nettoyage des variables de session en cas d'accès incorrect
                        unset($_SESSION['reset_email']);
                        unset($_SESSION['security_question']);
                        unset($_SESSION['reset_authorized']);
                        header('Location: reset-password.php');
                        exit();
                        ?>
                    <?php endif; ?>

                    <div class="text-center mt-3">
                        <p><a href="connexion.php">Retour à la connexion</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
