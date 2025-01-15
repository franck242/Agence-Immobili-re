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
        $requete = $connexion->prepare("SELECT fichier_path, titre FROM documents WHERE id = :id");
        $requete->execute([':id' => $id]);
        $document = $requete->fetch(PDO::FETCH_ASSOC);
        
        if ($document) {
            $file_path = "documents/" . $document['fichier_path'];
            
            if (file_exists($file_path)) {
                // Définir les headers pour le téléchargement
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $document['fichier_path'] . '"');
                header('Content-Length: ' . filesize($file_path));
                
                // Désactiver la mise en cache
                header('Cache-Control: no-cache, must-revalidate');
                header('Pragma: no-cache');
                
                // Lire et sortir le fichier
                readfile($file_path);
                exit();
            }
        }
    } catch (PDOException $e) {
        // En cas d'erreur, rediriger
    }
}

header('Location: gestion-documents.php?error=1');
