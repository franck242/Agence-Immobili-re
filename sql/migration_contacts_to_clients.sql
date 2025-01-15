-- Création de la nouvelle table clients
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `appartement_id` int DEFAULT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `appartement_id` (`appartement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copier les données de contacts vers clients
INSERT INTO clients (id, nom, email, telephone, message, appartement_id, date_creation)
SELECT id, name, email, phone, message, apartment_id, created_at
FROM contacts;

-- Supprimer l'ancienne table
DROP TABLE IF EXISTS contacts;
