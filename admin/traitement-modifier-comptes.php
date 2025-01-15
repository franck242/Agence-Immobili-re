

<?php

require_once("connexion-bdd.php");
require_once ("traitement-upload-images.php");

// Vérifier si l'ID de l'appartement est fourni dans l'URL
if (isset($_GET['apt'])) {
    $id_admin = $_GET['apt'];

    if (isset($_POST['firstname'])) {

        $firstname = $_POST['firstname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        $requete = $connexion->prepare("UPDATE `admin` SET firstname = ?, email = ?, `password` = ?, `role` = ? WHERE Id_admin = ?");
        $requete->execute([ $firstname, $email, $password, $role, $id_admin]);

    
                //   requête sql pour la colonne photos
                $requete = $connexion->prepare("UPDATE `admin` SET photos = ? WHERE Id_admin = ?");
                $upload_file_name = basename($_FILES["photos"]["name"]);
                $requete->execute  ([$upload_file_name, $id_admin]);


        header("location:liste-comptes.php");
        exit();
    }
}






?>




