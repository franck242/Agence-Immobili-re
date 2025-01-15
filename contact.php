<?php
session_start();
require_once 'admin/connexion-bdd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nom = htmlspecialchars(trim($_POST['name']));
        $email = htmlspecialchars(trim($_POST['email']));
        $telephone = htmlspecialchars(trim($_POST['phone']));
        $message = htmlspecialchars(trim($_POST['message']));
        $appartement_id = isset($_POST['apartment_id']) ? intval($_POST['apartment_id']) : null;

        // Déterminer le type de demande en fonction du contenu du message
        $type_demande = 'information';
        if (stripos($message, 'visite') !== false) {
            $type_demande = 'visite';
        } elseif (stripos($message, 'réservation') !== false || stripos($message, 'reservation') !== false) {
            $type_demande = 'autre';
        }

        // Enregistrer dans la table demandes_contact
        $requete = $connexion->prepare("
            INSERT INTO demandes_contact 
            (nom, email, telephone, message, appartement_id, type_demande, statut_suivi) 
            VALUES (?, ?, ?, ?, ?, ?, 'nouveau')
        ");

        $requete->execute([$nom, $email, $telephone, $message, $appartement_id, $type_demande]);

        // Rediriger vers la page de confirmation
        header('Location: confirmation-contact.php');
        exit();

    } catch(PDOException $e) {
        error_log("Erreur SQL : " . $e->getMessage());
        $_SESSION['error_message'] = "Une erreur est survenue lors de l'envoi de votre message : " . $e->getMessage();
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
