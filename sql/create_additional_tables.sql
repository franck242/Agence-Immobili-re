USE agence_immobilliere;

-- Table pour les images multiples
CREATE TABLE IF NOT EXISTS images_appartements (
    id INT NOT NULL AUTO_INCREMENT,
    appartement_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (appartement_id) REFERENCES appartements(Id_appartements) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table pour les avis
CREATE TABLE IF NOT EXISTS avis (
    id INT NOT NULL AUTO_INCREMENT,
    appartement_id INT NOT NULL,
    user_id INT NOT NULL,
    note INT NOT NULL CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    date_sejour DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_approved BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (id),
    FOREIGN KEY (appartement_id) REFERENCES appartements(Id_appartements) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(Id_users) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Trigger pour créer une notification lors d'un nouvel avis
DELIMITER //
CREATE TRIGGER after_avis_insert
AFTER INSERT ON avis
FOR EACH ROW
BEGIN
    INSERT INTO notifications (type, message, reference_id)
    VALUES ('avis', CONCAT('Nouvel avis pour l\'appartement #', NEW.appartement_id), NEW.id);
END//
DELIMITER ;
