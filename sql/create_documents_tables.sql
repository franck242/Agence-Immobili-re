USE agence_immobilliere;

-- Table pour les documents
CREATE TABLE IF NOT EXISTS documents (
    id INT NOT NULL AUTO_INCREMENT,
    type ENUM('contrat', 'facture', 'etat_des_lieux', 'autre') NOT NULL,
    titre VARCHAR(255) NOT NULL,
    fichier_path VARCHAR(255) NOT NULL,
    appartement_id INT,
    user_id INT,
    reservation_id INT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    statut ENUM('brouillon', 'final', 'archive') DEFAULT 'brouillon',
    notes TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (appartement_id) REFERENCES appartements(Id_appartements) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(Id_users) ON DELETE SET NULL,
    FOREIGN KEY (reservation_id) REFERENCES reservation(Id_reservation) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table pour les statistiques mensuelles
CREATE TABLE IF NOT EXISTS statistiques_mensuelles (
    id INT NOT NULL AUTO_INCREMENT,
    mois DATE NOT NULL,
    nombre_reservations INT DEFAULT 0,
    revenu_total DECIMAL(10,2) DEFAULT 0,
    taux_occupation DECIMAL(5,2) DEFAULT 0,
    nombre_nouveaux_clients INT DEFAULT 0,
    note_moyenne DECIMAL(3,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY (mois)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table pour les métriques par appartement
CREATE TABLE IF NOT EXISTS metriques_appartements (
    id INT NOT NULL AUTO_INCREMENT,
    appartement_id INT NOT NULL,
    mois DATE NOT NULL,
    revenu DECIMAL(10,2) DEFAULT 0,
    nombre_nuits INT DEFAULT 0,
    note_moyenne DECIMAL(3,2) DEFAULT 0,
    nombre_avis INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (appartement_id) REFERENCES appartements(Id_appartements) ON DELETE CASCADE,
    UNIQUE KEY (appartement_id, mois)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Trigger pour mettre à jour les statistiques après une nouvelle réservation
DELIMITER //
CREATE TRIGGER after_reservation_insert
AFTER INSERT ON reservation
FOR EACH ROW
BEGIN
    DECLARE mois_stats DATE;
    DECLARE montant DECIMAL(10,2);
    DECLARE duree INT;
    
    SET mois_stats = DATE_FORMAT(NEW.date_debut, '%Y-%m-01');
    SET montant = (
        SELECT prix 
        FROM appartements 
        WHERE Id_appartements = NEW.Id_appartements
    );
    SET duree = DATEDIFF(NEW.date_fin, NEW.date_debut);
    
    -- Mise à jour des statistiques mensuelles
    INSERT INTO statistiques_mensuelles (mois, nombre_reservations, revenu_total)
    VALUES (mois_stats, 1, montant * duree)
    ON DUPLICATE KEY UPDATE
        nombre_reservations = nombre_reservations + 1,
        revenu_total = revenu_total + (montant * duree);
        
    -- Mise à jour des métriques par appartement
    INSERT INTO metriques_appartements (appartement_id, mois, revenu, nombre_nuits)
    VALUES (NEW.Id_appartements, mois_stats, montant * duree, duree)
    ON DUPLICATE KEY UPDATE
        revenu = revenu + (montant * duree),
        nombre_nuits = nombre_nuits + duree;
END//

-- Trigger pour mettre à jour les statistiques après un nouvel avis
CREATE TRIGGER after_avis_insert_stats
AFTER INSERT ON avis
FOR EACH ROW
BEGIN
    DECLARE mois_stats DATE;
    SET mois_stats = DATE_FORMAT(NEW.created_at, '%Y-%m-01');
    
    -- Mise à jour des métriques par appartement
    INSERT INTO metriques_appartements (appartement_id, mois, note_moyenne, nombre_avis)
    VALUES (NEW.appartement_id, mois_stats, NEW.note, 1)
    ON DUPLICATE KEY UPDATE
        note_moyenne = ((note_moyenne * nombre_avis) + NEW.note) / (nombre_avis + 1),
        nombre_avis = nombre_avis + 1;
        
    -- Mise à jour de la note moyenne globale
    UPDATE statistiques_mensuelles
    SET note_moyenne = (
        SELECT AVG(note)
        FROM avis
        WHERE DATE_FORMAT(created_at, '%Y-%m-01') = mois_stats
    )
    WHERE mois = mois_stats;
END//
DELIMITER ;
