<?php
require_once("connexion-bdd.php");
require_once("file-upload.php");
// require_once ("traitement-upload-images.php");

// traitement des données du formulaire
$categorie = $_POST['categorie'];
$superficie = $_POST['superficie'];
$lits= $_POST['lits'];
$salle_de_bains = $_POST['salle_de_bains'];
$status = $_POST['status'];
$description =$_POST['description'];
$prix = $_POST['prix'];
$adresse = $_POST['adresse'];
$ville = $_POST['ville'];
$pays = $_POST['pays'];





// requête insertion dans la table appartements
/* Les symboles "?" sont utilisés comme des paramètres
de liaison pour des valeurs dynamiques qui seront fournies
 lors de l'exécution de la requête.*/

 /* En utilisant des paramètres de liaison, nous séparons la requête SQL de ses valeurs réelles,
  ce qui permet de prévenir les attaques par injection SQL et d'assurer une exécution sécurisée
  de la requête en utilisant des valeurs fournies dynamiquement */
  $upload_file_name = upload_image("photos","uploads-images");

$requete = $connexion->prepare("INSERT INTO appartements (photos, categorie,superficie,
 lits, salle_de_bains, `status`,`description`, prix, adresse, ville ,pays) values (?,?,?,?,?,?,?,?,?,?,?)");
$requete->execute([ $upload_file_name, $categorie , $superficie, $lits,
 $salle_de_bains, $status,$description, $prix, $adresse, $ville, $pays]);




       
header("location:/Agence-immobilliere/admin/liste-appartements.php");
?>