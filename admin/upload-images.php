<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appartement_id'])) {
    $appartement_id = $_POST['appartement_id'];
    $upload_dir = "uploads-images/";
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB

    // Vérifier si des fichiers ont été uploadés
    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $file_type = $_FILES['images']['type'][$key];
            $file_size = $_FILES['images']['size'][$key];
            $file_error = $_FILES['images']['error'][$key];

            // Vérifications de base
            if ($file_error !== UPLOAD_ERR_OK) {
                continue;
            }
            if (!in_array($file_type, $allowed_types)) {
                continue;
            }
            if ($file_size > $max_size) {
                continue;
            }

            // Générer un nom unique pour l'image
            $file_extension = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;

            // Déplacer le fichier
            if (move_uploaded_file($tmp_name, $upload_dir . $new_filename)) {
                // Vérifier s'il y a déjà une image principale
                $requete = $connexion->prepare("SELECT COUNT(*) FROM images_appartements WHERE appartement_id = :appartement_id");
                $requete->execute([':appartement_id' => $appartement_id]);
                $count = $requete->fetchColumn();

                // Insérer dans la base de données
                $requete = $connexion->prepare("
                    INSERT INTO images_appartements (appartement_id, image_path, is_primary) 
                    VALUES (:appartement_id, :image_path, :is_primary)
                ");
                $requete->execute([
                    ':appartement_id' => $appartement_id,
                    ':image_path' => $new_filename,
                    ':is_primary' => ($count == 0) ? true : false
                ]);

                // Si c'est la première image, mettre à jour l'image principale dans la table appartements
                if ($count == 0) {
                    $requete = $connexion->prepare("
                        UPDATE appartements 
                        SET photos = :photos 
                        WHERE Id_appartements = :id
                    ");
                    $requete->execute([
                        ':photos' => $new_filename,
                        ':id' => $appartement_id
                    ]);
                }
            }
        }
    }

    header('Location: gerer-images.php?id=' . $appartement_id . '&success=1');
    exit();
} else {
    header('Location: liste-appartements.php');
    exit();
}
