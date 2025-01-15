-- Création de la table contacts si elle n'existe pas
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `apartment_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `apartment_id` (`apartment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- Insertion des données de test
INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `message`, `apartment_id`, `created_at`) VALUES
(1, 'niani lily', 'lily14789@gmx.fr', '0641253695', 'je le veut', 6, '2025-01-05 01:01:57'),
(2, 'niani lily', 'lily14789@gmx.fr', '0641253695', 'je le veut', NULL, '2025-01-05 01:04:17'),
(3, 'yfjht', 'lily14789@gmx.fr', '0641253695', 'fhnrfh', NULL, '2025-01-07 05:00:25'),
(4, 'Marc Laurent', 'marc.laurent@email.com', '0678912345', 'Très intéressé par ce bien. Possibilité de visite ce week-end ?', 17, '2025-01-07 05:48:44'),
(5, 'Claire Dubois', 'claire.dubois@email.com', '0645123789', 'Je souhaiterais des informations supplémentaires sur les prestations incluses.', 6, '2025-01-07 05:48:44'),
(6, 'Antoine Moreau', 'antoine.moreau@email.com', '0789456123', 'Disponibilité à partir de mars ? Merci de me recontacter.', 12, '2025-01-07 05:48:44');

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

-- Migration des données
INSERT INTO clients (id, nom, email, telephone, message, appartement_id, date_creation)
SELECT id, name, email, phone, message, apartment_id, created_at
FROM contacts;

-- Suppression de l'ancienne table
DROP TABLE contacts;
