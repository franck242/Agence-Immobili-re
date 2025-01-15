-- Création de la table pour les documents
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` ENUM('contrat', 'facture', 'etat_des_lieux', 'autre') NOT NULL,
  `titre` varchar(255) NOT NULL,
  `fichier_path` varchar(255) NOT NULL,
  `appartement_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `notes` text,
  `statut` ENUM('brouillon', 'final', 'archive') DEFAULT 'brouillon',
  `date_creation` timestamp DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `appartement_id` (`appartement_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`appartement_id`) REFERENCES `appartements` (`Id_appartements`) ON DELETE SET NULL,
  CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id_users`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table pour les statistiques mensuelles
CREATE TABLE IF NOT EXISTS `statistiques_mensuelles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mois` date NOT NULL,
  `nombre_reservations` int DEFAULT 0,
  `revenu_total` decimal(10,2) DEFAULT 0.00,
  `taux_occupation` decimal(5,2) DEFAULT 0.00,
  `note_moyenne` decimal(3,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mois` (`mois`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table pour les métriques par appartement
CREATE TABLE IF NOT EXISTS `metriques_appartements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appartement_id` int NOT NULL,
  `mois` date NOT NULL,
  `revenu` decimal(10,2) DEFAULT 0.00,
  `nombre_nuits` int DEFAULT 0,
  `note_moyenne` decimal(3,2) DEFAULT 0.00,
  `nombre_avis` int DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `appartement_mois` (`appartement_id`, `mois`),
  CONSTRAINT `metriques_appartements_ibfk_1` FOREIGN KEY (`appartement_id`) REFERENCES `appartements` (`Id_appartements`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table pour les tâches de maintenance
CREATE TABLE IF NOT EXISTS `maintenance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appartement_id` int NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text,
  `type` ENUM('reparation', 'nettoyage', 'renovation', 'inspection', 'autre') NOT NULL,
  `priorite` ENUM('basse', 'moyenne', 'haute', 'urgente') NOT NULL,
  `statut` ENUM('a_faire', 'en_cours', 'termine', 'annule') DEFAULT 'a_faire',
  `cout_estime` decimal(10,2),
  `cout_reel` decimal(10,2),
  `date_creation` timestamp DEFAULT CURRENT_TIMESTAMP,
  `date_debut` date,
  `date_fin` date,
  `responsable_id` int,
  `notes` text,
  PRIMARY KEY (`id`),
  KEY `appartement_id` (`appartement_id`),
  KEY `responsable_id` (`responsable_id`),
  CONSTRAINT `maintenance_ibfk_1` FOREIGN KEY (`appartement_id`) REFERENCES `appartements` (`Id_appartements`) ON DELETE CASCADE,
  CONSTRAINT `maintenance_ibfk_2` FOREIGN KEY (`responsable_id`) REFERENCES `users` (`Id_users`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table pour les interventions de maintenance
CREATE TABLE IF NOT EXISTS `interventions_maintenance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `maintenance_id` int NOT NULL,
  `intervenant` varchar(255) NOT NULL,
  `date_intervention` datetime NOT NULL,
  `description` text NOT NULL,
  `cout` decimal(10,2),
  `facture_path` varchar(255),
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `maintenance_id` (`maintenance_id`),
  CONSTRAINT `interventions_maintenance_ibfk_1` FOREIGN KEY (`maintenance_id`) REFERENCES `maintenance` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table pour les équipements des appartements
CREATE TABLE IF NOT EXISTS `equipements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appartement_id` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `type` ENUM('electromenager', 'meuble', 'climatisation', 'chauffage', 'plomberie', 'electricite', 'autre') NOT NULL,
  `marque` varchar(255),
  `modele` varchar(255),
  `date_installation` date,
  `date_derniere_maintenance` date,
  `frequence_maintenance` int,
  `statut` ENUM('fonctionnel', 'a_verifier', 'en_panne', 'remplace') DEFAULT 'fonctionnel',
  `notes` text,
  PRIMARY KEY (`id`),
  KEY `appartement_id` (`appartement_id`),
  CONSTRAINT `equipements_ibfk_1` FOREIGN KEY (`appartement_id`) REFERENCES `appartements` (`Id_appartements`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table pour les notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` ENUM('maintenance', 'document', 'reservation', 'equipement', 'autre') NOT NULL,
  `message` text NOT NULL,
  `reference_id` int,
  `user_id` int,
  `lu` boolean DEFAULT FALSE,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id_users`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Trigger pour mettre à jour les statistiques lors d'une nouvelle réservation
DELIMITER //
CREATE TRIGGER after_reservation_insert
AFTER INSERT ON reservation
FOR EACH ROW
BEGIN
    DECLARE mois_stats DATE;
    SET mois_stats = DATE_FORMAT(NEW.date_depart, '%Y-%m-01');
    
    -- Mise à jour ou insertion dans statistiques_mensuelles
    INSERT INTO statistiques_mensuelles (mois, nombre_reservations)
    VALUES (mois_stats, 1)
    ON DUPLICATE KEY UPDATE
        nombre_reservations = nombre_reservations + 1;
        
    -- Mise à jour ou insertion dans metriques_appartements
    INSERT INTO metriques_appartements (appartement_id, mois, nombre_nuits)
    VALUES (NEW.Id_appartements, mois_stats, 
           DATEDIFF(NEW.date_retour, NEW.date_depart))
    ON DUPLICATE KEY UPDATE
        nombre_nuits = nombre_nuits + DATEDIFF(NEW.date_retour, NEW.date_depart);
END//

-- Trigger pour les notifications de maintenance urgente
CREATE TRIGGER after_maintenance_insert
AFTER INSERT ON maintenance
FOR EACH ROW
BEGIN
    IF NEW.priorite = 'urgente' THEN
        INSERT INTO notifications (type, message, reference_id)
        VALUES ('maintenance', 
                CONCAT('Nouvelle tâche de maintenance urgente pour l\'appartement #', NEW.appartement_id),
                NEW.id);
    END IF;
END//

-- Trigger pour les notifications d'équipement
CREATE TRIGGER after_equipement_update
AFTER UPDATE ON equipements
FOR EACH ROW
BEGIN
    IF NEW.statut = 'a_verifier' AND OLD.statut != 'a_verifier' THEN
        INSERT INTO notifications (type, message, reference_id)
        VALUES ('equipement',
                CONCAT('L\'équipement ', NEW.nom, ' nécessite une vérification'),
                NEW.id);
    END IF;
END//
DELIMITER ;
