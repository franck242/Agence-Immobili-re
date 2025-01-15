<?php

echo "<pre>";
print_r($_FILES);
echo "</pre>";



/* traitement upload image recuperer nom du fichier*/

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
  if (is_uploaded_file($_FILES['photos']['tmp_name'])) 
  { 
  	//Tout d'abord, validez le nom du fichier
  	if(empty($_FILES['photos']['name']))
  	{
  		echo " Le nom du fichier est vide ! ";
  		exit;
  	}

  	$upload_file_name = $_FILES['photos']['name'];
  	//Nom de fichier trop long ?
  	if(strlen ($upload_file_name)>100)
  	{
  		echo "nom de fichier trop long";
  		exit;
  	}

  	//remplacer tous les caractères non alphanumériques dans le nom du fichier
  	$upload_file_name = preg_replace("/[^A-Za-z0-9 \.\-_]/", '', $upload_file_name);

	
	// ce qui m'interesse pour la requête d'insertion sera stokcké dans $upload_file_name

  	//fixer une limite à la taille de téléchargement de fichier
  	if ($_FILES['photos']['size'] > 1000000) 
  	{
		echo " trop gros fichier ";
  		exit;        
    }

    //Enregistrez le fichier
    $dest=__DIR__.'/uploads-images/'.$upload_file_name;
    if (move_uploaded_file($_FILES['photos']['tmp_name'], $dest)) 
    {
    	echo 'File Has Been Uploaded !';
    }
  }
}



?>