<?php
session_start();
require_once 'admin/connexion-bdd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $telephone = htmlspecialchars(trim($_POST['phone']));
    $message = htmlspecialchars(trim($_POST['message']));
    $appartement_id = isset($_POST['apartment_id']) ? intval($_POST['apartment_id']) : null;

    try {
        // Vérifier si l'email correspond à un compte utilisateur existant
        $stmt = $connexion->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $utilisateur = $stmt->fetch();
        $utilisateur_id = $utilisateur ? $utilisateur['id'] : null;

        // Enregistrer dans la table demandes_contact
        $requete = $connexion->prepare("
            INSERT INTO demandes_contact 
            (nom, email, telephone, message, appartement_id, type_demande, statut_suivi, utilisateur_id) 
            VALUES (?, ?, ?, ?, ?, ?, 'nouveau', ?)
        ");
        
        // Déterminer le type de demande en fonction du contenu du message
        $type_demande = 'information';
        if (stripos($message, 'visite') !== false) {
            $type_demande = 'visite';
        } elseif (stripos($message, 'réservation') !== false || stripos($message, 'reservation') !== false) {
            $type_demande = 'reservation';
        }
        
        $requete->execute([$nom, $email, $telephone, $message, $appartement_id, $type_demande, $utilisateur_id]);

        // Si c'est une visite et qu'un utilisateur est associé, l'ajouter à la table visites
        if ($type_demande === 'visite' && $utilisateur_id) {
            $requete_visite = $connexion->prepare("
                INSERT INTO visites (utilisateur_id, appartement_id, date_demande, statut)
                VALUES (?, ?, NOW(), 'en_attente')
            ");
            $requete_visite->execute([$utilisateur_id, $appartement_id]);
        }

        // Afficher la page de confirmation
        require_once 'includes/header.php';
        ?>
        <div class="container mt-5 pt-5">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Message envoyé avec succès!</h4>
                <p>Votre demande a bien été enregistrée. Nous vous contacterons rapidement.</p>
                <?php if ($utilisateur_id && $type_demande === 'visite'): ?>
                    <p>Votre demande de visite est également visible dans votre espace personnel.</p>
                <?php endif; ?>
                <hr>
                <div class="text-center">
                    <a href="index.php" class="btn btn-primary">Retourner à l'accueil</a>
                </div>
            </div>
        </div>
        <?php
        require_once 'includes/footer.php';
        exit();

    } catch(PDOException $e) {
        $_SESSION['error_message'] = "Une erreur est survenue lors de l'envoi de votre message.";
        if ($appartement_id) {
            header("Location: detail-appartements.php?apt=$appartement_id");
        } else {
            header('Location: index.php');
        }
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
?>
