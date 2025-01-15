<?php
session_start();
require_once("admin/connexion-bdd.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $security_question = htmlspecialchars(trim($_POST['security_question']));
    $security_answer = htmlspecialchars(trim($_POST['security_answer']));

    // Validation des données
    $errors = [];
    
    if (strlen($firstname) < 2) {
        $errors[] = "Le prénom doit contenir au moins 2 caractères";
    }
    
    if (strlen($lastname) < 2) {
        $errors[] = "Le nom doit contenir au moins 2 caractères";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email n'est pas valide";
    }
    
    if (strlen($password) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas";
    }

    if (empty($security_question)) {
        $errors[] = "La question de sécurité est requise";
    }

    if (empty($security_answer)) {
        $errors[] = "La réponse à la question de sécurité est requise";
    }

    // Vérifier si l'email existe déjà
    try {
        $check_email = $connexion->prepare("SELECT email FROM users WHERE email = ?");
        $check_email->execute([$email]);
        if ($check_email->rowCount() > 0) {
            $errors[] = "Cette adresse email est déjà utilisée";
        }

        $check_admin_email = $connexion->prepare("SELECT email FROM admin WHERE email = ?");
        $check_admin_email->execute([$email]);
        if ($check_admin_email->rowCount() > 0) {
            $errors[] = "Cette adresse email est déjà utilisée";
        }
    } catch(PDOException $e) {
        $errors[] = "Une erreur est survenue lors de la vérification de l'email";
    }

    // Si pas d'erreurs, procéder à l'inscription
    if (empty($errors)) {
        try {
            $hashed_password = sha1($password);
            $hashed_answer = sha1(strtolower(trim($security_answer))); // On hash aussi la réponse pour la sécurité
            $insert = $connexion->prepare("INSERT INTO users (firstname, lastname, email, password, security_question, security_answer) VALUES (?, ?, ?, ?, ?, ?)");
            $insert->execute([$firstname, $lastname, $email, $hashed_password, $security_question, $hashed_answer]);

            $_SESSION['success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter";
            header('Location: connexion.php');
            exit();
        } catch(PDOException $e) {
            $errors[] = "Une erreur est survenue lors de l'inscription";
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
                    <h2 class="text-center">Inscription</h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="inscription.php" autocomplete="off">
                        <div class="mb-3">
                            <label for="firstname" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" required autocomplete="off" value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" required autocomplete="off" value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required autocomplete="new-email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password">
                            <small class="form-text text-muted">Le mot de passe doit contenir au moins 8 caractères</small>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required autocomplete="new-password">
                        </div>
                        <div class="mb-3">
                            <label for="security_question" class="form-label">Question de sécurité personnelle</label>
                            <input type="text" class="form-control" id="security_question" name="security_question" required autocomplete="off" placeholder="Exemple : Quel est le nom de mon premier animal de compagnie ?" value="<?php echo isset($_POST['security_question']) ? htmlspecialchars($_POST['security_question']) : ''; ?>">
                            <small class="form-text text-muted">Choisissez une question dont vous vous souviendrez facilement de la réponse</small>
                        </div>
                        <div class="mb-3">
                            <label for="security_answer" class="form-label">Réponse à la question de sécurité</label>
                            <input type="text" class="form-control" id="security_answer" name="security_answer" required autocomplete="off" value="<?php echo isset($_POST['security_answer']) ? htmlspecialchars($_POST['security_answer']) : ''; ?>">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">S'inscrire</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <p>Déjà inscrit ? <a href="connexion.php">Se connecter</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
