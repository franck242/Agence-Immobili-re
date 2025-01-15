<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Images</title>
</head>
<body>
    <h1>Test des images</h1>
    
    <?php
    $images = scandir(__DIR__ . '/img');
    echo '<h2>Contenu du dossier img :</h2>';
    echo '<pre>';
    print_r($images);
    echo '</pre>';
    
    // Afficher la première image trouvée
    foreach ($images as $image) {
        if ($image != '.' && $image != '..' && in_array(strtolower(pathinfo($image, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])) {
            echo '<h3>Test avec l\'image : ' . htmlspecialchars($image) . '</h3>';
            echo '<img src="img/' . htmlspecialchars($image) . '" style="max-width: 300px;">';
            break;
        }
    }
    
    // Test avec une image spécifique
    echo '<h3>Test avec pexels-pixabay-275484.jpg</h3>';
    $testImage = 'pexels-pixabay-275484.jpg';
    $imagePath = __DIR__ . '/img/' . $testImage;
    echo 'Le fichier existe ? : ' . (file_exists($imagePath) ? 'Oui' : 'Non') . '<br>';
    echo '<img src="img/' . htmlspecialchars($testImage) . '" style="max-width: 300px;">';
    ?>
</body>
</html>
