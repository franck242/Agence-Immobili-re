<?php
session_start();
require_once("admin/connexion-bdd.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Vérification si les mots de passe correspondent
    if ($password !== $password_confirm) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas";
    } else {
        try {
            // Vérifier si l'email existe déjà
            $check = $connexion->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $check->execute([$email]);
            if ($check->fetchColumn() > 0) {
                $_SESSION['error'] = "Cet email est déjà utilisé";
            } else {
                // Insérer le nouvel utilisateur
                $requete = $connexion->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
                $success = $requete->execute([$firstname, $lastname, $email, sha1($password)]);

                if ($success) {
                    $_SESSION['success'] = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
                    header('Location: connexion.php');
                    exit();
                } else {
                    $_SESSION['error'] = "Une erreur est survenue lors de l'inscription";
                }
            }
        } catch(PDOException $e) {
            $_SESSION['error'] = "Une erreur est survenue";
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="text-center">Créer un compte</h2>
                </div>
                <div class="card-body p-5">
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" autocomplete="off">
                        <div class="mb-3">
                            <label for="firstname" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password">
                            <div class="form-text">Le mot de passe doit contenir au moins 8 caractères.</div>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required autocomplete="new-password">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">J'accepte les conditions d'utilisation</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                    </form>

                    <div class="text-center mt-4">
                        <p>Déjà un compte ? <a href="connexion.php">Se connecter</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
