CREATE TABLE IF NOT EXISTS `demandes_visites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appartement_id` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `date_demande` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('nouvelle','vue','traitee') NOT NULL DEFAULT 'nouvelle',
  PRIMARY KEY (`id`),
  KEY `appartement_id` (`appartement_id`),
  CONSTRAINT `demandes_visites_ibfk_1` FOREIGN KEY (`appartement_id`) REFERENCES `appartements` (`Id_appartements`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
