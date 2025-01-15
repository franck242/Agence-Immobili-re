<?php
require_once("authentification.php");

// Vérifier si l'utilisateur est connecté et est un admin
if (!isset($_SESSION['admin']) || !isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Créer un tableau avec les informations de l'admin pour l'affichage
$account_admin = [
    'firstname' => $_SESSION['admin_firstname'] ?? '',
    'lastname' => $_SESSION['admin_lastname'] ?? '',
    'photos' => $_SESSION['admin_photos'] ?? ''
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/menu.css" />
    <script type="text/javascript" src="dashbord.js"></script>
</head>

<body>
    <div id="mySidenav" class="sidenav">
        <div class="profile-section">
            <div class="profile-image">
                <?php if (!empty($account_admin['photos'])): ?>
                    <img src="<?php echo htmlspecialchars($account_admin['photos']); ?>" alt="Profile" class="profile-pic">
                <?php else: ?>
                    <i class="bi bi-person-circle"></i>
                <?php endif; ?>
            </div>
            <p class="admin-name">
                <?php 
                echo htmlspecialchars($account_admin['firstname'] . ' ' . $account_admin['lastname']);
                ?>
            </p>
            <p class="admin-role">Administrateur</p>
        </div>

        <div class="menu-items">
            <a href="dashboard.php" class="menu-a <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="gestion-appartements.php" class="menu-a <?php echo basename($_SERVER['PHP_SELF']) == 'gestion-appartements.php' ? 'active' : ''; ?>">
                <i class="bi bi-building"></i> Gestion Appartements
            </a>
            <a href="ajouter-appartement.php" class="menu-a <?php echo basename($_SERVER['PHP_SELF']) == 'ajouter-appartement.php' ? 'active' : ''; ?>">
                <i class="bi bi-plus-circle"></i> Ajouter Appartement
            </a>
            <a href="liste-appartements.php" class="menu-a <?php echo basename($_SERVER['PHP_SELF']) == 'liste-appartements.php' ? 'active' : ''; ?>">
                <i class="bi bi-grid"></i> Liste Détaillée
            </a>
            <a href="gestion-messages.php" class="menu-a <?php echo basename($_SERVER['PHP_SELF']) == 'gestion-messages.php' ? 'active' : ''; ?>">
                <i class="bi bi-chat-dots"></i> Gestion Messages
            </a>
            <a href="gestion-demandes.php" class="menu-a <?php echo basename($_SERVER['PHP_SELF']) == 'gestion-demandes.php' ? 'active' : ''; ?>">
                <i class="bi bi-calendar-check"></i> Gestion Demandes
            </a>
            <a href="gestion-utilisateurs.php" class="menu-a <?php echo basename($_SERVER['PHP_SELF']) == 'gestion-utilisateurs.php' ? 'active' : ''; ?>">
                <i class="bi bi-people"></i> Gestion Utilisateurs
            </a>
            <a href="deconnexion.php" class="menu-a text-danger">
                <i class="bi bi-box-arrow-right"></i> Déconnexion
            </a>
        </div>
    </div>
</body>
</html>