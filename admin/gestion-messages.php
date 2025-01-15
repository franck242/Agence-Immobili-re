<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

// Récupérer tous les messages avec les informations sur l'appartement
$requete = $connexion->prepare("
    SELECT m.*, a.categorie as appartement_nom
    FROM demandes_contact m
    LEFT JOIN appartements a ON m.appartement_id = a.Id_appartements
    ORDER BY m.date_creation DESC
");
$requete->execute();
$messages = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Messages - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
    <style>
        .action-icon {
            cursor: pointer;
            padding: 5px;
            font-size: 1.1rem;
        }
        .action-icon:hover {
            opacity: 0.8;
        }
        .view-icon { color: #0d6efd; }
        .edit-icon { color: #ffc107; }
        .delete-icon { color: #dc3545; }
        .message-preview {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
    <?php include_once("menu.php"); ?>
    
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section">
                <h1><i class="bi bi-chat-dots"></i> Gestion des Messages</h1>
                <p class="text-muted">Gérez tous les messages reçus</p>
            </div>

            <div class="card mt-4 slide-in">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Message</th>
                                    <th>Appartement</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $message): ?>
                                <tr class="fade-in">
                                    <td>
                                        <?php echo htmlspecialchars($message['email']); ?>
                                        <br>
                                        <a href="mailto:<?php echo $message['email']; ?>" 
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-reply"></i> Répondre
                                        </a>
                                    </td>
                                    <td>
                                        <div class="message-preview">
                                            <?php echo htmlspecialchars($message['message']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo $message['appartement_nom'] ? htmlspecialchars($message['appartement_nom']) : '-'; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($message['date_creation'])); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="voir-message.php?id=<?php echo $message['id']; ?>" 
                                               class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="modifier-message.php?id=<?php echo $message['id']; ?>" 
                                               class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="javascript:void(0)" 
                                               onclick="if(confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) window.location.href='supprimer-message.php?id=<?php echo $message['id']; ?>'" 
                                               class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
</body>
</html>
