<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

// Récupérer les notifications non lues
$requete = $connexion->prepare("
    SELECT n.*,
           CASE n.type
               WHEN 'maintenance' THEN m.titre
               WHEN 'document' THEN d.titre
               WHEN 'reservation' THEN CONCAT('Réservation #', n.reference_id)
               ELSE 'Notification'
           END as titre_reference
    FROM notifications n
    LEFT JOIN maintenance m ON n.type = 'maintenance' AND n.reference_id = m.id
    LEFT JOIN documents d ON n.type = 'document' AND n.reference_id = d.id
    WHERE n.lu = 0 AND (n.user_id = :user_id OR n.user_id IS NULL)
    ORDER BY n.created_at DESC
");
$requete->execute(['user_id' => $_SESSION['admin_id']]);
$notifications = $requete->fetchAll(PDO::FETCH_ASSOC);

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1><i class="bi bi-bell"></i> Notifications</h1>
                        <p class="text-muted">Centre de notifications</p>
                    </div>
                    <div>
                        <button class="btn btn-primary" onclick="marquerToutCommeLu()">
                            <i class="bi bi-check-all"></i> Tout marquer comme lu
                        </button>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <?php if (!empty($notifications)): ?>
                            <?php foreach ($notifications as $notif): ?>
                                <div class="notification-item p-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <?php
                                            $icon = '';
                                            switch ($notif['type']) {
                                                case 'maintenance':
                                                    $icon = 'wrench';
                                                    break;
                                                case 'document':
                                                    $icon = 'file-earmark-pdf';
                                                    break;
                                                case 'reservation':
                                                    $icon = 'calendar-check';
                                                    break;
                                            }
                                            ?>
                                            <h5><i class="bi bi-<?php echo $icon; ?>"></i> <?php echo htmlspecialchars($notif['message']); ?></h5>
                                            <p class="text-muted mb-0">
                                                <?php echo htmlspecialchars($notif['titre_reference']); ?>
                                            </p>
                                            <small class="text-muted">
                                                <?php echo date('d/m/Y H:i', strtotime($notif['created_at'])); ?>
                                            </small>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary" onclick="marquerCommeLu(<?php echo $notif['id']; ?>)">
                                            <i class="bi bi-check"></i> Marquer comme lu
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="bi bi-bell-slash" style="font-size: 3rem;"></i>
                                <p class="mt-3">Aucune nouvelle notification</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function marquerCommeLu(id) {
            fetch('marquer-notification-lu.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }

        function marquerToutCommeLu() {
            fetch('marquer-tout-lu.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }
    </script>
</body>
</html>
