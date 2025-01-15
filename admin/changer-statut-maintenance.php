<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Récupérer le statut actuel
        $requete = $connexion->prepare("SELECT statut FROM maintenance WHERE id = :id");
        $requete->execute([':id' => $id]);
        $maintenance = $requete->fetch(PDO::FETCH_ASSOC);
        
        if ($maintenance) {
            $nouveau_statut = '';
            switch ($maintenance['statut']) {
                case 'a_faire':
                    $nouveau_statut = 'en_cours';
                    break;
                case 'en_cours':
                    $nouveau_statut = 'termine';
                    break;
                case 'termine':
                    $nouveau_statut = 'a_faire';
                    break;
                default:
                    $nouveau_statut = 'a_faire';
            }
            
            $requete = $connexion->prepare("
                UPDATE maintenance 
                SET statut = :statut 
                WHERE id = :id
            ");
            $requete->execute([
                ':id' => $id,
                ':statut' => $nouveau_statut
            ]);
        }
        
        header('Location: gestion-maintenance.php?success=1');
    } catch (PDOException $e) {
        header('Location: gestion-maintenance.php?error=1');
    }
} else {
    header('Location: gestion-maintenance.php');
}
