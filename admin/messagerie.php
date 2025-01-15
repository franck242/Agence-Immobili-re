<?php
require_once("connexion-bdd.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit();
}

// Récupérer toutes les conversations
$requete = $connexion->prepare("
    SELECT c.*,
           u.firstname as user_firstname,
           u.lastname as user_lastname,
           u.email as user_email,
           a.categorie as appartement_nom,
           a.photos as appartement_photo,
           (SELECT COUNT(*) FROM messages WHERE conversation_id = c.id AND is_read = FALSE AND sender_id != :admin_id) as unread_count,
           (SELECT message FROM messages WHERE conversation_id = c.id ORDER BY created_at DESC LIMIT 1) as last_message
    FROM conversations c
    JOIN users u ON c.user_id = u.Id_users
    LEFT JOIN appartements a ON c.appartement_id = a.Id_appartements
    WHERE c.status = 'active'
    ORDER BY c.updated_at DESC
");
$requete->execute([':admin_id' => $_SESSION['admin_id']]);
$conversations = $requete->fetchAll(PDO::FETCH_ASSOC);

include_once("menu.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/animations.css">
    <style>
        .conversation-list {
            height: calc(100vh - 200px);
            overflow-y: auto;
        }
        .conversation-item {
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .conversation-item:hover {
            background-color: rgba(0,0,0,0.05);
        }
        .conversation-item.active {
            background-color: rgba(0,123,255,0.1);
        }
        .messages-container {
            height: calc(100vh - 300px);
            overflow-y: auto;
        }
        .message-input {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 15px;
            border-top: 1px solid #dee2e6;
        }
        .message-bubble {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 15px;
            margin-bottom: 10px;
        }
        .message-sent {
            background-color: #007bff;
            color: white;
            margin-left: auto;
        }
        .message-received {
            background-color: #e9ecef;
        }
        .apartment-preview {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        .unread-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <div id="main">
        <div class="container-fluid fade-in">
            <div class="welcome-section slide-in">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1><i class="bi bi-chat-dots"></i> Messagerie</h1>
                        <p class="text-muted">Gérez vos conversations avec les clients</p>
                    </div>
                </div>

                <div class="row">
                    <!-- Liste des conversations -->
                    <div class="col-md-4 border-end">
                        <div class="conversation-list">
                            <?php foreach ($conversations as $conv): ?>
                            <div class="conversation-item p-3 border-bottom position-relative" 
                                 onclick="chargerConversation(<?php echo $conv['id']; ?>)">
                                <div class="d-flex align-items-center">
                                    <?php if ($conv['appartement_photo']): ?>
                                    <img src="uploads-images/<?php echo htmlspecialchars($conv['appartement_photo']); ?>" 
                                         alt="Appartement" 
                                         class="apartment-preview me-3">
                                    <?php endif; ?>
                                    <div>
                                        <h6 class="mb-1">
                                            <?php echo htmlspecialchars($conv['user_firstname'] . ' ' . $conv['user_lastname']); ?>
                                        </h6>
                                        <small class="text-muted">
                                            <?php echo $conv['appartement_nom'] ? htmlspecialchars($conv['appartement_nom']) : 'Discussion générale'; ?>
                                        </small>
                                        <?php if ($conv['last_message']): ?>
                                        <p class="mb-0 text-truncate" style="max-width: 200px;">
                                            <?php echo htmlspecialchars($conv['last_message']); ?>
                                        </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if ($conv['unread_count'] > 0): ?>
                                <span class="badge bg-primary unread-badge">
                                    <?php echo $conv['unread_count']; ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Zone de messages -->
                    <div class="col-md-8">
                        <div id="messages-container" class="messages-container p-3">
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-chat-dots" style="font-size: 3rem;"></i>
                                <p class="mt-3">Sélectionnez une conversation pour afficher les messages</p>
                            </div>
                        </div>
                        <div class="message-input">
                            <form id="message-form" class="d-none">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="message-text" 
                                           placeholder="Tapez votre message..." required>
                                    <button class="btn btn-primary" type="submit">
                                        <i class="bi bi-send"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentConversationId = null;
        let lastMessageId = 0;

        function chargerConversation(conversationId) {
            currentConversationId = conversationId;
            document.getElementById('message-form').classList.remove('d-none');
            
            fetch('get-messages.php?conversation_id=' + conversationId)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('messages-container');
                    container.innerHTML = '';
                    
                    data.forEach(message => {
                        ajouterMessage(message);
                        if (message.id > lastMessageId) {
                            lastMessageId = message.id;
                        }
                    });
                    
                    container.scrollTop = container.scrollHeight;
                });
        }

        function ajouterMessage(message) {
            const container = document.getElementById('messages-container');
            const isAdmin = message.sender_id == <?php echo $_SESSION['admin_id']; ?>;
            const messageDiv = document.createElement('div');
            
            messageDiv.className = `message-bubble ${isAdmin ? 'message-sent' : 'message-received'}`;
            messageDiv.textContent = message.message;
            
            container.appendChild(messageDiv);
        }

        document.getElementById('message-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const messageText = document.getElementById('message-text').value;
            if (!messageText.trim()) return;
            
            fetch('envoyer-message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'conversation_id=' + currentConversationId + '&message=' + encodeURIComponent(messageText)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('message-text').value = '';
                    ajouterMessage({
                        message: messageText,
                        sender_id: <?php echo $_SESSION['admin_id']; ?>
                    });
                    const container = document.getElementById('messages-container');
                    container.scrollTop = container.scrollHeight;
                }
            });
        });

        // Vérifier les nouveaux messages toutes les 5 secondes
        setInterval(() => {
            if (currentConversationId) {
                fetch('check-new-messages.php?conversation_id=' + currentConversationId + '&last_id=' + lastMessageId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            data.forEach(message => {
                                ajouterMessage(message);
                                if (message.id > lastMessageId) {
                                    lastMessageId = message.id;
                                }
                            });
                            const container = document.getElementById('messages-container');
                            container.scrollTop = container.scrollHeight;
                        }
                    });
            }
        }, 5000);
    </script>
</body>
</html>
