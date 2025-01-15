<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

if (isset($_GET['id']) && isset($_GET['appartement_id'])) {
    $image_id = $_GET['id'];
    $appartement_id = $_GET['appartement_id'];
    
    try {
        // Commencer une transaction
        $connexion->beginTransaction();
        
        // Récupérer le chemin de la nouvelle image principale
        $requete = $connexion->prepare("SELECT image_path FROM images_appartements WHERE id = :id");
        $requete->execute([':id' => $image_id]);
        $image = $requete->fetch(PDO::FETCH_ASSOC);
        
        if ($image) {
            // Mettre à jour toutes les images comme non principales
            $requete = $connexion->prepare("
                UPDATE images_appartements 
                SET is_primary = FALSE 
                WHERE appartement_id = :appartement_id
            ");
            $requete->execute([':appartement_id' => $appartement_id]);
            
            // Définir la nouvelle image principale
            $requete = $connexion->prepare("
                UPDATE images_appartements 
                SET is_primary = TRUE 
                WHERE id = :id
            ");
            $requete->execute([':id' => $image_id]);
            
            // Mettre à jour l'image principale dans la table appartements
            $requete = $connexion->prepare("
                UPDATE appartements 
                SET photos = :photos 
                WHERE Id_appartements = :id
            ");
            $requete->execute([
                ':photos' => $image['image_path'],
                ':id' => $appartement_id
            ]);
            
            $connexion->commit();
            header('Location: gerer-images.php?id=' . $appartement_id . '&success=1');
        } else {
            throw new Exception("Image non trouvée");
        }
    } catch (Exception $e) {
        $connexion->rollBack();
        header('Location: gerer-images.php?id=' . $appartement_id . '&error=1');
    }
} else {
    header('Location: liste-appartements.php');
}
