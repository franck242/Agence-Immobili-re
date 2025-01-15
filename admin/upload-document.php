<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $type = $_POST['type'] ?? '';
    $appartement_id = $_POST['appartement_id'] ?: null;
    $user_id = $_POST['user_id'] ?: null;
    $notes = $_POST['notes'] ?? '';
    
    $upload_dir = "documents/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['document']['tmp_name'];
        $name = $_FILES['document']['name'];
        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        
        // Vérifier l'extension
        $allowed_extensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
        if (!in_array($extension, $allowed_extensions)) {
            header('Location: gestion-documents.php?error=1');
            exit();
        }
        
        // Générer un nom unique
        $new_filename = uniqid() . '_' . $name;
        
        if (move_uploaded_file($tmp_name, $upload_dir . $new_filename)) {
            try {
                $requete = $connexion->prepare("
                    INSERT INTO documents (type, titre, fichier_path, appartement_id, user_id, notes)
                    VALUES (:type, :titre, :fichier_path, :appartement_id, :user_id, :notes)
                ");
                
                $requete->execute([
                    ':type' => $type,
                    ':titre' => $titre,
                    ':fichier_path' => $new_filename,
                    ':appartement_id' => $appartement_id,
                    ':user_id' => $user_id,
                    ':notes' => $notes
                ]);
                
                header('Location: gestion-documents.php?success=1');
            } catch (PDOException $e) {
                unlink($upload_dir . $new_filename);
                header('Location: gestion-documents.php?error=2');
            }
        } else {
            header('Location: gestion-documents.php?error=3');
        }
    } else {
        header('Location: gestion-documents.php?error=4');
    }
} else {
    header('Location: gestion-documents.php');
}
