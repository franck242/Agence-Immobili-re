CREATE TABLE IF NOT EXISTS reservation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Id_appartements INT NOT NULL,
    date_debut DATE NOT NULL,
    date_depart DATE NOT NULL,
    statut ENUM('en_attente', 'confirmee', 'annulee') DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Id_appartements) REFERENCES appartements(Id_appartements)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
