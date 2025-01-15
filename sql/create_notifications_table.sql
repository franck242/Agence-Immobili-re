USE agence_immobilliere;

CREATE TABLE IF NOT EXISTS notifications (
    id INT NOT NULL AUTO_INCREMENT,
    type VARCHAR(50) NOT NULL, -- 'reservation', 'contact', 'favori'
    message TEXT NOT NULL,
    reference_id INT NOT NULL, -- ID de la réservation, du contact ou du favori
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
