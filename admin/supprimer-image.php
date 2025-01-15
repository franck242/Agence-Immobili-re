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
        
        // Récupérer les informations de l'image
        $requete = $connexion->prepare("
            SELECT image_path, is_primary 
            FROM images_appartements 
            WHERE id = :id
        ");
        $requete->execute([':id' => $image_id]);
        $image = $requete->fetch(PDO::FETCH_ASSOC);
        
        if ($image) {
            // Supprimer le fichier physique
            $file_path = "uploads-images/" . $image['image_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            
            // Si c'était l'image principale, définir une autre image comme principale
            if ($image['is_primary']) {
                // Trouver une autre image pour cet appartement
                $requete = $connexion->prepare("
                    SELECT id, image_path 
                    FROM images_appartements 
                    WHERE appartement_id = :appartement_id AND id != :id 
                    LIMIT 1
                ");
                $requete->execute([
                    ':appartement_id' => $appartement_id,
                    ':id' => $image_id
                ]);
                $nouvelle_image = $requete->fetch(PDO::FETCH_ASSOC);
                
                if ($nouvelle_image) {
                    // Définir la nouvelle image comme principale
                    $requete = $connexion->prepare("
                        UPDATE images_appartements 
                        SET is_primary = TRUE 
                        WHERE id = :id
                    ");
                    $requete->execute([':id' => $nouvelle_image['id']]);
                    
                    // Mettre à jour l'image principale dans la table appartements
                    $requete = $connexion->prepare("
                        UPDATE appartements 
                        SET photos = :photos 
                        WHERE Id_appartements = :id
                    ");
                    $requete->execute([
                        ':photos' => $nouvelle_image['image_path'],
                        ':id' => $appartement_id
                    ]);
                } else {
                    // S'il n'y a plus d'images, mettre une image par défaut
                    $requete = $connexion->prepare("
                        UPDATE appartements 
                        SET photos = 'default.jpg' 
                        WHERE Id_appartements = :id
                    ");
                    $requete->execute([':id' => $appartement_id]);
                }
            }
            
            // Supprimer l'image de la base de données
            $requete = $connexion->prepare("DELETE FROM images_appartements WHERE id = :id");
            $requete->execute([':id' => $image_id]);
            
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
