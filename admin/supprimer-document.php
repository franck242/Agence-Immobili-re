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
    
    try {
        // Récupérer le chemin du fichier
        $requete = $connexion->prepare("SELECT fichier_path FROM documents WHERE id = :id");
        $requete->execute([':id' => $id]);
        $document = $requete->fetch(PDO::FETCH_ASSOC);
        
        if ($document) {
            $file_path = "documents/" . $document['fichier_path'];
            
            // Supprimer le fichier physique
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            
            // Supprimer l'entrée dans la base de données
            $requete = $connexion->prepare("DELETE FROM documents WHERE id = :id");
            $requete->execute([':id' => $id]);
            
            header('Location: gestion-documents.php?success=1');
        } else {
            throw new Exception("Document non trouvé");
        }
    } catch (Exception $e) {
        header('Location: gestion-documents.php?error=1');
    }
} else {
    header('Location: gestion-documents.php');
}
