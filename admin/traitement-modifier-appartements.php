<?php

require_once("connexion-bdd.php");
require_once("file-upload.php");

// Vérifier si l'ID de l'appartement est fourni dans l'URL
if (isset($_GET['apt'])) {
    $id_appartement = $_GET['apt'];

    if (isset($_POST['categorie'])) {
        $categorie = $_POST['categorie'];
        $superficie = $_POST['superficie'];
        $lits = $_POST['lits'];
        $salle_de_bains = $_POST['salle_de_bains'];
        $status = $_POST['status'];
        $prix = $_POST['prix'];
        $adresse = $_POST['adresse'];
        $ville = $_POST['ville'];
        $pays = $_POST['pays'];

        $requete = $connexion->prepare("UPDATE appartements SET categorie = ?, superficie = ?, lits = ?, salle_de_bains = ?, `status` = ?, prix = ?, adresse = ?, ville = ?, pays = ? WHERE Id_appartements = ?");
        $requete->execute([$categorie, $superficie, $lits, $salle_de_bains, $status, $prix, $adresse, $ville, $pays, $id_appartement]);
       
 $upload_file_name = upload_image("photos","uploads-images");

/**/ 
if($upload_file_name!= false){
//   requête sql pour la colonne photos
$requete = $connexion->prepare("UPDATE appartements SET photos = ? WHERE Id_appartements = ?");
$requete->execute  ([$upload_file_name, $id_appartement]);

} 
   


      
         header("location:liste-appartements.php");
         exit();
    }
}






?>