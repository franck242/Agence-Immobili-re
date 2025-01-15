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
        
        // Test avec le mot de passe exact
        $test_password = "MIrKoS6898..@242";
        $hashed = sha1($test_password);
        echo "Test avec votre mot de passe :<br>";
        echo "Mot de passe testé: " . $test_password . "<br>";
        echo "Hash SHA1 généré: " . $hashed . "<br><br>";
        
        echo "Les hash correspondent ? : " . ($admin['password'] === $hashed ? "OUI" : "NON") . "<br>";
        
        if ($admin['password'] !== $hashed) {
            echo "<br>Solution proposée :<br>";
            echo "1. Utilisez ce SQL dans phpMyAdmin pour mettre à jour directement le mot de passe :<br>";
            echo "<pre>UPDATE admin SET password = '" . $hashed . "' WHERE email = 'zaylen@gmx.fr';</pre>";
        }
    } else {
        echo "Admin non trouvé dans la base de données.";
    }
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
