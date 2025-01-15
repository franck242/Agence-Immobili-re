USE agence_immobilliere;

-- Table pour les conversations
CREATE TABLE IF NOT EXISTS conversations (
    id INT NOT NULL AUTO_INCREMENT,
    appartement_id INT,
    user_id INT NOT NULL,
    admin_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'archived') DEFAULT 'active',
    PRIMARY KEY (id),
    FOREIGN KEY (appartement_id) REFERENCES appartements(Id_appartements) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(Id_users) ON DELETE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES users(Id_users) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table pour les messages
CREATE TABLE IF NOT EXISTS messages (
    id INT NOT NULL AUTO_INCREMENT,
    conversation_id INT NOT NULL,
    sender_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(Id_users) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table pour les disponibilités
CREATE TABLE IF NOT EXISTS disponibilites (
    id INT NOT NULL AUTO_INCREMENT,
    appartement_id INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    statut ENUM('disponible', 'reserve', 'maintenance') DEFAULT 'disponible',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (appartement_id) REFERENCES appartements(Id_appartements) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Trigger pour créer une notification lors d'un nouveau message
DELIMITER //
CREATE TRIGGER after_message_insert
AFTER INSERT ON messages
FOR EACH ROW
BEGIN
    DECLARE recipient_id INT;
    DECLARE conv_user_id INT;
    DECLARE conv_admin_id INT;
    
    -- Récupérer les IDs de la conversation
    SELECT user_id, admin_id INTO conv_user_id, conv_admin_id
    FROM conversations
    WHERE id = NEW.conversation_id;
    
    -- Déterminer le destinataire
    IF NEW.sender_id = conv_user_id THEN
        SET recipient_id = conv_admin_id;
    ELSE
        SET recipient_id = conv_user_id;
    END IF;
    
    -- Créer la notification
    INSERT INTO notifications (type, message, reference_id, user_id)
    VALUES ('message', 'Nouveau message reçu', NEW.id, recipient_id);
END//
DELIMITER ;
