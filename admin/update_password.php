<?php
require_once("connexion-bdd.php");

// Le mot de passe que vous voulez utiliser
$new_password = "MIrKoS6898..@242"; // Mettez ici votre mot de passe en clair, pas le hash

// Hacher le mot de passe
$hashed_password = sha1($new_password);

try {
    $stmt = $connexion->prepare("UPDATE admin SET password = ? WHERE email = 'zaylen@gmx.fr'");
    if ($stmt->execute([$hashed_password])) {
        echo "Mot de passe mis à jour avec succès !<br>";
        echo "Nouveau mot de passe : " . $new_password . "<br>";
        echo "Hash SHA1 : " . $hashed_password;
    } else {
        echo "Erreur lors de la mise à jour du mot de passe.";
    }
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
