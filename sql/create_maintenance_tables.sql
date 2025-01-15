USE agence_immobilliere;

-- Table pour les tâches de maintenance
CREATE TABLE IF NOT EXISTS maintenance (
    id INT NOT NULL AUTO_INCREMENT,
    appartement_id INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('reparation', 'nettoyage', 'renovation', 'inspection', 'autre') NOT NULL,
    priorite ENUM('basse', 'moyenne', 'haute', 'urgente') NOT NULL,
    statut ENUM('a_faire', 'en_cours', 'termine', 'annule') DEFAULT 'a_faire',
    cout_estime DECIMAL(10,2),
    cout_reel DECIMAL(10,2),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_debut DATE,
    date_fin DATE,
    responsable_id INT,
    notes TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (appartement_id) REFERENCES appartements(Id_appartements) ON DELETE CASCADE,
    FOREIGN KEY (responsable_id) REFERENCES users(Id_users) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table pour les tâches générales
CREATE TABLE IF NOT EXISTS taches (
    id INT NOT NULL AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    categorie ENUM('administratif', 'commercial', 'technique', 'financier', 'autre') NOT NULL,
    priorite ENUM('basse', 'moyenne', 'haute', 'urgente') NOT NULL,
    statut ENUM('a_faire', 'en_cours', 'termine', 'annule') DEFAULT 'a_faire',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_echeance DATE,
    assigne_a INT,
    cree_par INT NOT NULL,
    rappel BOOLEAN DEFAULT FALSE,
    date_rappel DATETIME,
    notes TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (assigne_a) REFERENCES users(Id_users) ON DELETE SET NULL,
    FOREIGN KEY (cree_par) REFERENCES users(Id_users) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table pour les interventions de maintenance
CREATE TABLE IF NOT EXISTS interventions_maintenance (
    id INT NOT NULL AUTO_INCREMENT,
    maintenance_id INT NOT NULL,
    intervenant VARCHAR(255) NOT NULL,
    date_intervention DATETIME NOT NULL,
    description TEXT NOT NULL,
    cout DECIMAL(10,2),
    facture_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (maintenance_id) REFERENCES maintenance(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table pour les pièces/équipements des appartements
CREATE TABLE IF NOT EXISTS equipements (
    id INT NOT NULL AUTO_INCREMENT,
    appartement_id INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    type ENUM('electromenager', 'meuble', 'climatisation', 'chauffage', 'plomberie', 'electricite', 'autre') NOT NULL,
    marque VARCHAR(255),
    modele VARCHAR(255),
    date_installation DATE,
    date_derniere_maintenance DATE,
    frequence_maintenance INT, -- en jours
    statut ENUM('fonctionnel', 'a_verifier', 'en_panne', 'remplace') DEFAULT 'fonctionnel',
    notes TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (appartement_id) REFERENCES appartements(Id_appartements) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Trigger pour créer une notification lors d'une nouvelle tâche de maintenance urgente
DELIMITER //
CREATE TRIGGER after_maintenance_insert
AFTER INSERT ON maintenance
FOR EACH ROW
BEGIN
    IF NEW.priorite = 'urgente' THEN
        INSERT INTO notifications (type, message, reference_id)
        VALUES ('maintenance', CONCAT('Nouvelle tâche de maintenance urgente pour l\'appartement #', NEW.appartement_id), NEW.id);
    END IF;
END//

-- Trigger pour créer une notification lors d'une nouvelle tâche urgente
CREATE TRIGGER after_tache_insert
AFTER INSERT ON taches
FOR EACH ROW
BEGIN
    IF NEW.priorite = 'urgente' THEN
        INSERT INTO notifications (type, message, reference_id, user_id)
        VALUES ('tache', CONCAT('Nouvelle tâche urgente : ', NEW.titre), NEW.id, NEW.assigne_a);
    END IF;
END//

-- Trigger pour créer une notification lorsqu'un équipement nécessite une maintenance
CREATE TRIGGER after_equipement_update
AFTER UPDATE ON equipements
FOR EACH ROW
BEGIN
    IF NEW.statut = 'a_verifier' AND OLD.statut != 'a_verifier' THEN
        INSERT INTO notifications (type, message, reference_id)
        VALUES ('equipement', CONCAT('L\'équipement ', NEW.nom, ' nécessite une vérification'), NEW.id);
    END IF;
END//
DELIMITER ;
