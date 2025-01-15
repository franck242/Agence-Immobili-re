CREATE TABLE IF NOT EXISTS appartements (
    Id_appartements INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    superficie INT NOT NULL,
    lits INT NOT NULL,
    salle_de_bains INT NOT NULL,
    status TINYINT NOT NULL DEFAULT 0,
    ville VARCHAR(100) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    pays VARCHAR(100) NOT NULL,
    photos VARCHAR(255),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
