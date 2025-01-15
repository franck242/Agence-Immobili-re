<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $requete = $connexion->prepare("
            INSERT INTO maintenance (
                appartement_id,
                titre,
                description,
                type,
                priorite,
                date_debut,
                date_fin,
                cout_estime,
                responsable_id
            ) VALUES (
                :appartement_id,
                :titre,
                :description,
                :type,
                :priorite,
                :date_debut,
                :date_fin,
                :cout_estime,
                :responsable_id
            )
        ");

        $requete->execute([
            ':appartement_id' => $_POST['appartement_id'],
            ':titre' => $_POST['titre'],
            ':description' => $_POST['description'],
            ':type' => $_POST['type'],
            ':priorite' => $_POST['priorite'],
            ':date_debut' => $_POST['date_debut'] ?: null,
            ':date_fin' => $_POST['date_fin'] ?: null,
            ':cout_estime' => $_POST['cout_estime'] ?: null,
            ':responsable_id' => $_POST['responsable_id'] ?: null
        ]);

        header('Location: gestion-maintenance.php?success=1');
    } catch (PDOException $e) {
        header('Location: gestion-maintenance.php?error=1');
    }
} else {
    header('Location: gestion-maintenance.php');
}
