<?php
session_start();

// Fonction qui vérifie si l'utilisateur est connecté
function estConnecte(): bool
{
    return isset($_SESSION['connecte']) && $_SESSION['connecte'] === true;
}

// Fonction qui force l'utilisateur à se connecter pour accéder à la page admin
function forcerUtilisateurConnecte(): void
{
    if (!estConnecte()) {
        header('Location:Agence-immobilliere/index.php');
        exit();
    }
}

// Fonction qui vérifie l'existence d'un compte utilisateur dans la base de données
function verifierCompteUtilisateur($account): bool
{
    require_once("./admin/connexion-bdd.php");

    try {
        $email = $account['email'];
        $password = $account['password'];

        // Utilisation de requêtes préparées pour éviter les injections SQL
        $requete = $connexion->prepare("SELECT * FROM users WHERE email = ? AND password = SHA1(?)");
        $requete->execute([$email, $password]);
        $resultat = $requete->fetch(PDO::FETCH_ASSOC);

        if (is_array($resultat)) {
            $_SESSION['connecte'] = true;
            $_SESSION['users'] = $resultat;
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo 'Echec de la connexion : ' . $e->getMessage();
        return false;
    }
}

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si les champs du formulaire ne sont pas vides
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        // Appeler la fonction verifierCompteUtilisateur avec les données du formulaire
        if (verifierCompteUtilisateur($_POST)) {
            header('Location:./admin/liste-appartements.php');
            exit();
        } else {
            $erreur = "Identifiants incorrects";
            var_dump($erreur);
        }
    }
}
?>
