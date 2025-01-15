<?php
require_once("connexion-bdd.php");

try {
    // Récupérer l'admin
    $stmt = $connexion->prepare("SELECT * FROM admin WHERE email = 'zaylen@gmx.fr'");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        echo "Admin trouvé :<br>";
        echo "Email: " . $admin['email'] . "<br>";
        echo "Mot de passe haché actuel: " . $admin['password'] . "<br><br>";
        
        // Exemple de hachage
        $test_password = "votreMotDePasse"; // Remplacez par le mot de passe que vous utilisez
        $hashed = sha1($test_password);
        echo "Test de hachage :<br>";
        echo "Mot de passe test: " . $test_password . "<br>";
        echo "Résultat du hachage SHA1: " . $hashed . "<br>";
    } else {
        echo "Admin non trouvé dans la base de données.";
    }
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
