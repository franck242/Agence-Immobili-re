<?php
require("admin/connexion-bdd.php"); // Inclut le fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] === "POST") { // Vérifie si la méthode de requête est POST

    // Récupère et filtre les données reçues par le formulaire
    $id_appartement = isset($_POST['apt']) ? $_POST['apt'] : '';
    $date_depart = isset($_POST['date_depart']) ? $_POST['date_depart'] : '';
    $date_retour = isset($_POST['date_retour']) ? $_POST['date_retour'] : '';
    $civilite = isset($_POST['civilite']) ? $_POST['civilite'] : '';
    $firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
    $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $adresse = isset($_POST['adresse']) ? $_POST['adresse'] : '';
    $code_postal = isset($_POST['code_postal']) ? $_POST['code_postal'] : '';
    $ville = isset($_POST['ville']) ? $_POST['ville'] : '';
    $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';
    $animaux = isset($_POST['animaux']) ? $_POST['animaux'] : '';

    // Vérifie si toutes les données ont été fournies
    if ($id_appartement && $date_depart && $date_retour && $civilite && $firstname && $lastname && $email && $adresse && $code_postal && $ville && $telephone && $animaux) {

        // Prépare la requête d'insertion dans la base de données
        $requete = $connexion->prepare("INSERT INTO reservation (Id_appartements, date_depart, date_retour, civilite, firstname, lastname, email, adresse, code_postal, ville, telephone, animaux) VALUES (:id_appartement, :date_depart, :date_retour, :civilite, :firstname, :lastname, :email, :adresse, :code_postal, :ville, :telephone, :animaux)");

        // Lie les valeurs aux paramètres de la requête
        $requete->bindParam(':id_appartement', $id_appartement);
        $requete->bindParam(':date_depart', $date_depart);
        $requete->bindParam(':date_retour', $date_retour);
        $requete->bindParam(':civilite', $civilite);
        $requete->bindParam(':firstname', $firstname);
        $requete->bindParam(':lastname', $lastname);
        $requete->bindParam(':email', $email);
        $requete->bindParam(':adresse', $adresse);
        $requete->bindParam(':code_postal', $code_postal);
        $requete->bindParam(':ville', $ville);
        $requete->bindParam(':telephone', $telephone);
        $requete->bindParam(':animaux', $animaux);

        // Exécute la requête
       // ...

if ($requete->execute()) {
    // Message de succès à afficher sur la page index.php
    $_SESSION['success_message'] = "La demande de location a été enregistrée avec succès.";
    header("location:index.php");
    exit;
} 

    } else {
        // Message d'erreur à afficher sur la page index.php
        $_SESSION['error_message'] = "Une erreur s'est produite lors de l'enregistrement de la demande de location : " . $requete->errorInfo()[2];
        header("location:index.php");
        exit;
    }
}

// Ferme la connexion à la base de données
$connexion = null;
?>
