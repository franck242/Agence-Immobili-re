<?php
session_start();
require_once("admin/connexion-bdd.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = sha1($_POST['password']);

    try {
        // Vérifier d'abord si c'est un admin
        $admin_query = $connexion->prepare("SELECT * FROM admin WHERE email = ? AND password = ?");
        $admin_query->execute([$email, $password]);
        $admin = $admin_query->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            // C'est un admin
            $_SESSION['connecte'] = true;
            $_SESSION['admin'] = true;
            $_SESSION['admin_id'] = $admin['Id_admin'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_firstname'] = $admin['firstname'];
            $_SESSION['admin_lastname'] = $admin['lastname'];
            $_SESSION['role'] = $admin['role'];
            
            header('Location: admin/dashboard.php');
            exit();
        }

        // Si ce n'est pas un admin, vérifier si c'est un utilisateur normal
        $user_query = $connexion->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $user_query->execute([$email, $password]);
        $user = $user_query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['connecte'] = true;
            $_SESSION['user_id'] = $user['Id_users'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_firstname'] = $user['firstname'];
            $_SESSION['user_lastname'] = $user['lastname'];
            $_SESSION['role'] = 'user';
            
            header('Location: user/dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = "Email ou mot de passe incorrect";
            header('Location: connexion.php');
            exit();
        }
    } catch(PDOException $e) {
        error_log("Erreur PDO: " . $e->getMessage());
        $_SESSION['error'] = "Une erreur est survenue";
        header('Location: connexion.php');
        exit();
    }
}

include('includes/header.php');
?>

<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">Connexion</h2>
                </div>
                <div class="card-body">
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="connexion.php" autocomplete="off">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required autocomplete="new-email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Se connecter</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <p>Pas encore de compte ? <a href="inscription.php">S'inscrire</a></p>
                        <p><a href="reset-password.php">Mot de passe oublié ?</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
