-- Création de la table demande_location
CREATE TABLE IF NOT EXISTS demande_location (
    Id_demande INT AUTO_INCREMENT PRIMARY KEY,
    Id_appartement INT NOT NULL,
    Id_users INT NOT NULL,
    date_demande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente', 'approuve', 'refuse') DEFAULT 'en_attente',
    FOREIGN KEY (Id_appartement) REFERENCES appartements(Id_appartement),
    FOREIGN KEY (Id_users) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
