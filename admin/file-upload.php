<?php 

function upload_image($input_name, $target_directory) {
    
    if (!isset($_FILES[$input_name])) { return false; }

    $target_file = $target_directory . basename($_FILES[$input_name]["name"]); // Chemin + nom du fichier (à l'arrivée)

    $uploadOk = 1; // Servira à garder trace d'éventuelles erreurs

    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); // Extension

    $is_image = getimagesize($_FILES[$input_name]["tmp_name"]);
    
    if($is_image !== false) {
        $uploadOk = 1; // erreur
    } else {
        $uploadOk = 0;
    }
        
    // Limiter le poids
    if ($_FILES[$input_name]["size"] > 2000000) { // 2Mo
        $uploadOk = 0;
    }
    
    // Filtrer les formats acceptés
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $uploadOk = 0;
    }
     
    if ($uploadOk == 1) { // Si tout est conforme, on tente d'uploader le fichier
        
        // On remplace le nom final par un timestamp (manière rapide d'éviter les doublons)
        
        $final_file_name = time() . "." . $imageFileType;
        
        $target_file = $target_directory . "/" . $final_file_name;
        
        if (move_uploaded_file($_FILES[$input_name]["tmp_name"], $target_file)) {
            
            // En cas de réussite : on retourne le nom du fichier uploadé (et la fonction s'arrête ici)
            
            return basename( $final_file_name);
        }
            
    }
  
    // Si l'upload n'a pas fonctionné
    return false;
    
}



?>