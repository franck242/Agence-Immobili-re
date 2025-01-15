-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 15 jan. 2025 à 04:26
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `agence_immobilliere`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `Id_admin` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `photos` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','agent','manager') NOT NULL DEFAULT 'agent',
  `telephone` varchar(20) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`Id_admin`),
  UNIQUE KEY `Id_users` (`Id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`Id_admin`, `photos`, `firstname`, `lastname`, `email`, `password`, `role`, `telephone`, `active`) VALUES
(2, 'user-solid.svg', 'sanji', 'Vinsmoke', 'zaylen@gmx.fr', '899e8fd4dc8e5b5b16d4c9871c6c5591f5a47bd7', 'agent', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `appartements`
--

DROP TABLE IF EXISTS `appartements`;
CREATE TABLE IF NOT EXISTS `appartements` (
  `Id_appartements` int NOT NULL AUTO_INCREMENT,
  `reference` varchar(20) DEFAULT NULL,
  `photos` varchar(255) NOT NULL,
  `categorie` varchar(255) NOT NULL,
  `type_id` int DEFAULT NULL,
  `superficie` varchar(255) NOT NULL,
  `lits` int NOT NULL,
  `salle_de_bains` int NOT NULL,
  `status` int DEFAULT NULL COMMENT '0=disponible,1=loué,2=travaux,3=vendu',
  `disponibilite_date` date DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `prix` varchar(100) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `pays` varchar(50) NOT NULL,
  `dpe_energie` char(1) DEFAULT NULL COMMENT 'Classe énergétique (A à G)',
  `dpe_ges` char(1) DEFAULT NULL COMMENT 'Émission de gaz (A à G)',
  `charges` decimal(10,2) DEFAULT NULL COMMENT 'Charges mensuelles',
  `taxe_fonciere` decimal(10,2) DEFAULT NULL COMMENT 'Taxe foncière annuelle',
  `meta_titre` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `partages` int DEFAULT '0',
  PRIMARY KEY (`Id_appartements`),
  KEY `type_id` (`type_id`),
  KEY `idx_prix_ville` (`prix`,`ville`),
  KEY `idx_prix_superficie` (`prix`,`superficie`),
  KEY `idx_ville_status` (`ville`,`status`)
) ;

--
-- Déchargement des données de la table `appartements`
--

INSERT INTO `appartements` (`Id_appartements`, `reference`, `photos`, `categorie`, `type_id`, `superficie`, `lits`, `salle_de_bains`, `status`, `disponibilite_date`, `description`, `prix`, `adresse`, `ville`, `pays`, `dpe_energie`, `dpe_ges`, `charges`, `taxe_fonciere`, `meta_titre`, `meta_description`, `partages`) VALUES
(5, 'APT0005', 'pexels-curtis-adams-1694007-12119390.jpg', 'Appartement T3 Prestige', NULL, '85 m²', 2, 1, 0, NULL, 'Magnifique T3 avec vue panoramique, cuisine équipée moderne, grand séjour lumineux, prestations haut de gamme', '1800', '15 rue de la République', 'Lyon', 'France', 'B', 'B', 180.00, 1440.00, NULL, NULL, 0),
(6, 'APT0006', 'pexels-john-tekeridis-1428348.jpg', 'Duplex Contemporain', NULL, '110 m²', 3, 2, 0, NULL, 'Superbe duplex contemporain avec terrasse, triple exposition, cuisine américaine équipée, proche toutes commodités', '2200', '25 cours Lafayette', 'Lyon', 'France', 'A', 'A', 220.00, 1760.00, NULL, NULL, 0),
(11, 'APT0011', 'pexels-heyho-6782346.jpg', 'Studio Design Centre', NULL, '35 m²', 1, 1, 0, NULL, 'Studio moderne entièrement rénové, parfait pour étudiant ou jeune actif, proche transports et commerces', '750', '8 rue des Capucins', 'Lyon', 'France', 'C', 'C', 75.00, 600.00, NULL, NULL, 0),
(12, 'APT0012', 'pexels-john-tekeridis-1428348.jpg', 'Grand T4 Familial', NULL, '95 m²', 3, 2, 0, NULL, 'Spacieux T4 idéal pour famille, quartier calme et résidentiel, proche écoles et parcs, garage inclus', '1950', '45 avenue Jean Jaurès', 'Lyon', 'France', 'B', 'B', 195.00, 1560.00, NULL, NULL, 0),
(13, 'APT0013', 'pexels-vecislavas-popa-1571460.jpg', 'T2 Charme Ancien', NULL, '55 m²', 1, 1, 0, NULL, 'Charmant T2 dans immeuble ancien rénové, parquet, moulures, belle hauteur sous plafond, balcon', '1100', '12 rue des Remparts', 'Lyon', 'France', 'B', 'B', 110.00, 880.00, NULL, NULL, 0),
(17, 'APT0017', 'pexels-curtis-adams-1694007-11438372.jpg', 'Loft Design Part-Dieu', NULL, '100 m²', 4, 3, 0, NULL, 'Magnifique loft contemporain, volumes exceptionnels, prestations luxueuses, proche Part-Dieu', '2900', '15 rue de la Velotte', 'Lyon', 'France', 'A', 'A', 290.00, 2320.00, NULL, NULL, 0),
(18, 'APT0018', 'pexels-curtis-adams-1694007-16501662.jpg', 'Penthouse Vue Fourvière', NULL, '150 m²', 4, 3, 0, NULL, 'Exceptionnel penthouse avec terrasse panoramique, prestations luxueuses, ascenseur privatif, cave à vin climatisée, domotique dernière génération', '4500', '10 place Bellecour', 'Lyon', 'France', 'A', 'A', 450.00, 3600.00, NULL, NULL, 0),
(19, 'APT0019', 'pexels-curtis-adams-1694007-4940605.jpg', 'Duplex Canut Rénové', NULL, '120 m²', 3, 2, 0, NULL, 'Authentique duplex dans immeuble canut, hauteur sous plafond 4m, poutres apparentes, cuisine sur-mesure, vue dégagée sur Lyon', '2800', '25 rue des Tables Claudiennes', 'Lyon', 'France', 'A', 'A', 280.00, 2240.00, NULL, NULL, 0),
(20, 'APT0020', 'pexels-heyho-6969876.jpg', 'Loft Confluence Design', NULL, '140 m²', 3, 2, 0, NULL, 'Loft d\'architecte au cœur de la Confluence, baies vitrées, rooftop privé 40m², parking sécurisé, prestations haut de gamme', '3200', '35 quai Rambaud', 'Lyon', 'France', 'A', 'A', 320.00, 2560.00, NULL, NULL, 0),
(21, 'APT0021', 'pexels-itsterrymag-2988860.jpg', 'T5 Foch Prestige', NULL, '160 m²', 4, 3, 0, NULL, 'Appartement bourgeois rénové, parquet Versailles, moulures, cheminées, cave à vin, parking, service de conciergerie', '3900', '15 avenue Maréchal Foch', 'Lyon', 'France', 'A', 'A', 390.00, 3120.00, NULL, NULL, 0),
(22, 'APT0022', 'pexels-pixabay-259580.jpg', 'Studio Premium Opéra', NULL, '40 m²', 1, 1, 0, NULL, 'Studio de standing face à l\'Opéra, entièrement meublé et équipé, climatisation, domotique, service de conciergerie', '1200', '3 rue de la République', 'Lyon', 'France', 'B', 'B', 120.00, 960.00, NULL, NULL, 0),
(23, 'APT0023', 'pexels-chait-goli-1918291.jpg', 'T3 Brotteaux Luxe', NULL, '90 m²', 2, 2, 0, NULL, 'Appartement de prestige quartier Brotteaux, proche parc, cuisine italienne, dressing, cave et parking', '2400', '18 avenue des Brotteaux', 'Lyon', 'France', 'A', 'A', 240.00, 1920.00, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `avis_biens`
--

DROP TABLE IF EXISTS `avis_biens`;
CREATE TABLE IF NOT EXISTS `avis_biens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appartement_id` int NOT NULL,
  `note` int DEFAULT NULL,
  `commentaire` text,
  `date_avis` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('en_attente','approuvé','rejeté') DEFAULT 'en_attente',
  PRIMARY KEY (`id`),
  KEY `appartement_id` (`appartement_id`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `demandes_contact`
--

DROP TABLE IF EXISTS `demandes_contact`;
CREATE TABLE IF NOT EXISTS `demandes_contact` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `type_demande` enum('information','visite','autre') DEFAULT 'information',
  `appartement_id` int DEFAULT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `statut_suivi` enum('nouveau','contacté','rendez_vous_fixé','visite_effectuée','sans_suite') DEFAULT 'nouveau',
  `agent_id` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appartement_id` (`appartement_id`),
  KEY `agent_id` (`agent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `demandes_contact`
--

INSERT INTO `demandes_contact` (`id`, `nom`, `email`, `telephone`, `message`, `type_demande`, `appartement_id`, `date_creation`, `statut_suivi`, `agent_id`) VALUES
(4, 'Marc Laurent', 'marc.laurent@email.com', '0678912345', 'Très intéressé par ce bien. Possibilité de visite ce week-end ?', 'information', 17, '2025-01-07 04:48:44', 'nouveau', NULL),
(5, 'Claire Dubois', 'claire.dubois@email.com', '0645123789', 'Je souhaiterais des informations supplémentaires sur les prestations incluses.', 'information', 6, '2025-01-07 04:48:44', 'nouveau', NULL),
(6, 'Antoine Moreau', 'antoine.moreau@email.com', '0789456123', 'Disponibilité à partir de mars ? Merci de me recontacter.', 'information', 12, '2025-01-07 04:48:44', 'nouveau', NULL),
(13, 'Harry', 'harry@gmx.fr', '0145789632', 'cet appartement est beau', 'visite', 23, '2025-01-14 15:37:13', 'nouveau', NULL),
(16, 'tik', 'tik@gmx.fr', '0645789623', 'bel appartement', 'information', 5, '2025-01-15 03:35:39', 'nouveau', NULL),
(17, 'nok', 'nok@gmail.com', '0645789632', 'je veux prendrfe conatct', 'information', NULL, '2025-01-15 03:36:24', 'nouveau', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('contrat','facture','etat_des_lieux','autre') NOT NULL,
  `titre` varchar(255) NOT NULL,
  `fichier_path` varchar(255) NOT NULL,
  `appartement_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `notes` text,
  `statut` enum('brouillon','final','archive') DEFAULT 'brouillon',
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `appartement_id` (`appartement_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `equipements`
--

DROP TABLE IF EXISTS `equipements`;
CREATE TABLE IF NOT EXISTS `equipements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appartement_id` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `type` enum('electromenager','meuble','climatisation','chauffage','plomberie','electricite','autre') NOT NULL,
  `marque` varchar(255) DEFAULT NULL,
  `modele` varchar(255) DEFAULT NULL,
  `date_installation` date DEFAULT NULL,
  `date_derniere_maintenance` date DEFAULT NULL,
  `frequence_maintenance` int DEFAULT NULL,
  `statut` enum('fonctionnel','a_verifier','en_panne','remplace') DEFAULT 'fonctionnel',
  `notes` text,
  PRIMARY KEY (`id`),
  KEY `appartement_id` (`appartement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

DROP TABLE IF EXISTS `favoris`;
CREATE TABLE IF NOT EXISTS `favoris` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `apartment_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_apartment_unique` (`user_id`,`apartment_id`),
  KEY `apartment_id` (`apartment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `favoris`
--

INSERT INTO `favoris` (`id`, `user_id`, `apartment_id`, `created_at`) VALUES
(1, 1, 6, '2025-01-07 05:48:44'),
(2, 1, 12, '2025-01-07 05:48:44'),
(3, 2, 17, '2025-01-07 05:48:44'),
(4, 2, 13, '2025-01-07 05:48:44'),
(5, 3, 5, '2025-01-07 05:48:44');

-- --------------------------------------------------------

--
-- Structure de la table `interventions_maintenance`
--

DROP TABLE IF EXISTS `interventions_maintenance`;
CREATE TABLE IF NOT EXISTS `interventions_maintenance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `maintenance_id` int NOT NULL,
  `intervenant` varchar(255) NOT NULL,
  `date_intervention` datetime NOT NULL,
  `description` text NOT NULL,
  `cout` decimal(10,2) DEFAULT NULL,
  `facture_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `maintenance_id` (`maintenance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `maintenance`
--

DROP TABLE IF EXISTS `maintenance`;
CREATE TABLE IF NOT EXISTS `maintenance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appartement_id` int NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text,
  `type` enum('reparation','nettoyage','renovation','inspection','autre') NOT NULL,
  `priorite` enum('basse','moyenne','haute','urgente') NOT NULL,
  `statut` enum('a_faire','en_cours','termine','annule') DEFAULT 'a_faire',
  `cout_estime` decimal(10,2) DEFAULT NULL,
  `cout_reel` decimal(10,2) DEFAULT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `responsable_id` int DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`),
  KEY `appartement_id` (`appartement_id`),
  KEY `responsable_id` (`responsable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('maintenance','document','reservation','equipement','autre') NOT NULL,
  `message` text NOT NULL,
  `reference_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `lu` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `statistiques_mensuelles`
--

DROP TABLE IF EXISTS `statistiques_mensuelles`;
CREATE TABLE IF NOT EXISTS `statistiques_mensuelles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mois` date NOT NULL,
  `nombre_reservations` int DEFAULT '0',
  `revenu_total` decimal(10,2) DEFAULT '0.00',
  `taux_occupation` decimal(5,2) DEFAULT '0.00',
  `note_moyenne` decimal(3,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mois` (`mois`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `suivi_commercial`
--

DROP TABLE IF EXISTS `suivi_commercial`;
CREATE TABLE IF NOT EXISTS `suivi_commercial` (
  `id` int NOT NULL AUTO_INCREMENT,
  `contact_id` int NOT NULL,
  `agent_id` bigint UNSIGNED NOT NULL,
  `type_action` enum('appel','email','visite','proposition','negociation') NOT NULL,
  `date_action` datetime NOT NULL,
  `description` text,
  `suite_a_donner` text,
  `date_relance` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `agent_id` (`agent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_appartements`
--

DROP TABLE IF EXISTS `type_appartements`;
CREATE TABLE IF NOT EXISTS `type_appartements` (
  `Id_type` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `description` text,
  PRIMARY KEY (`Id_type`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `type_appartements`
--

INSERT INTO `type_appartements` (`Id_type`, `nom`, `description`) VALUES
(1, 'Studio', 'Appartement d\'une seule pièce avec cuisine et salle de bain'),
(2, 'T1', 'Appartement avec une chambre séparée'),
(3, 'T2', 'Appartement avec deux pièces principales'),
(4, 'T3', 'Appartement avec trois pièces principales'),
(5, 'T4', 'Appartement avec quatre pièces principales'),
(6, 'T5', 'Appartement avec cinq pièces principales'),
(7, 'Duplex', 'Appartement sur deux niveaux'),
(8, 'Loft', 'Grand espace ouvert aménagé'),
(9, 'Penthouse', 'Appartement de luxe au dernier étage');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `Id_users` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `security_question` text NOT NULL,
  `security_answer` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id_users`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`Id_users`, `firstname`, `lastname`, `email`, `password`, `security_question`, `security_answer`, `created_at`) VALUES
(1, 'Jean', 'Dupont', 'jean.dupont@email.com', 'cbfdac6008f9cab4083784cbd1874f76618d2a97', '', '', '2025-01-07 00:49:38'),
(2, 'Marie', 'Martin', 'marie.martin@email.com', '0c6f6845bb8c62b778e9147c272ac4b5bdb9ae71', '', '', '2025-01-07 00:49:38'),
(3, 'Philippe', 'Durand', 'philippe.durand@email.com', 'cbfdac6008f9cab4083784cbd1874f76618d2a97', '', '', '2025-01-07 05:48:44'),
(4, 'Caroline', 'Leroy', 'caroline.leroy@email.com', '0c6f6845bb8c62b778e9147c272ac4b5bdb9ae71', '', '', '2025-01-07 05:48:44'),
(5, 'Laurent', 'Moreau', 'laurent.moreau@email.com', '7f6d5eea1bcef5ca6209d33b28e3aaeb3db26f24', '', '', '2025-01-07 05:48:44'),
(7, 'louis', 'pergaud', 'louispergaud@gmail.com', '01b949bbaa234e50bc620c807fe3ec7fe9b698ea', 'quel est mon personnage d&#039;animé préféré ?', '383d271164ea174ede00d67f0b7226227e170bba', '2025-01-14 20:52:53'),
(8, 'lucas', 'mingo', 'lucamingo@hotmail.com', '2ac7952c305b644020aba14f687cada2c3b40d2e', 'quel mon animal préféré ?', '0d5240720e7d7e888b86b7bd86fe52c403bdf4d0', '2025-01-14 20:54:27'),
(9, 'momo', 'mig', 'momo@gmx.fr', '01b949bbaa234e50bc620c807fe3ec7fe9b698ea', 'Quel est une de mes plus grande peur ?', '7dc0c6c92f386f8ecc9b5dcb631a964903e15fd4', '2025-01-15 03:24:34');

-- --------------------------------------------------------

--
-- Structure de la table `visites`
--

DROP TABLE IF EXISTS `visites`;
CREATE TABLE IF NOT EXISTS `visites` (
  `Id_visite` int NOT NULL AUTO_INCREMENT,
  `Id_appartements` int NOT NULL,
  `date_visite` datetime NOT NULL COMMENT 'Date et heure souhaitées pour la visite',
  `civilite` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `code_postal` varchar(255) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `statut` enum('demandée','confirmée','effectuée','annulée') DEFAULT 'demandée',
  `commentaire_agent` text,
  `agent_id` bigint UNSIGNED DEFAULT NULL,
  `duree_minutes` int DEFAULT '30',
  `rappel_envoye` tinyint(1) DEFAULT '0',
  `notes_pre_visite` text,
  `notes_post_visite` text,
  `interet_manifesté` enum('faible','moyen','fort') DEFAULT NULL,
  PRIMARY KEY (`Id_visite`),
  KEY `Id_appartements` (`Id_appartements`),
  KEY `visites_agent_fk` (`agent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `visites`
--

INSERT INTO `visites` (`Id_visite`, `Id_appartements`, `date_visite`, `civilite`, `firstname`, `lastname`, `email`, `adresse`, `code_postal`, `ville`, `telephone`, `statut`, `commentaire_agent`, `agent_id`, `duree_minutes`, `rappel_envoye`, `notes_pre_visite`, `notes_post_visite`, `interet_manifesté`) VALUES
(1, 5, '2023-06-20 00:00:00', 'monsieur', 'eustass', 'kid', 'eustass.kid@gmail.com', '15 rue des marrais', '38000', 'grenoble', '0142589636', 'demandée', NULL, NULL, 30, 0, NULL, NULL, 'faible'),
(2, 6, '2025-02-01 00:00:00', 'monsieur', 'Pierre', 'Dubois', 'pierre.dubois@email.com', '28 rue du Commerce', '75015', 'Paris', '0645789632', 'demandée', NULL, NULL, 30, 0, NULL, NULL, 'faible'),
(3, 12, '2025-01-15 00:00:00', 'madame', 'Sophie', 'Martin', 'sophie.martin@email.com', '5 avenue des Ternes', '75017', 'Paris', '0678451236', 'demandée', NULL, NULL, 30, 0, NULL, NULL, 'faible'),
(4, 13, '2025-02-10 00:00:00', 'monsieur', 'Thomas', 'Bernard', 'thomas.bernard@email.com', '12 rue de la Paix', '69002', 'Lyon', '0712345678', 'demandée', NULL, NULL, 30, 0, NULL, NULL, 'faible');

--
-- Déclencheurs `visites`
--
DROP TRIGGER IF EXISTS `after_visite_insert`;
DELIMITER $$
CREATE TRIGGER `after_visite_insert` AFTER INSERT ON `visites` FOR EACH ROW BEGIN
    UPDATE appartements 
    SET nombre_visites = (
        SELECT COUNT(*) 
        FROM visites 
        WHERE Id_appartements = NEW.Id_appartements
    )
    WHERE Id_appartements = NEW.Id_appartements;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `after_visite_update`;
DELIMITER $$
CREATE TRIGGER `after_visite_update` AFTER UPDATE ON `visites` FOR EACH ROW BEGIN
    IF NEW.statut = 'effectuée' AND OLD.statut != 'effectuée' THEN
        UPDATE statistiques_mensuelles
        SET nombre_visites = nombre_visites + 1
        WHERE mois = DATE_FORMAT(NEW.date_visite, '%Y-%m-01');
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_activite_agents`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `vue_activite_agents`;
CREATE TABLE IF NOT EXISTS `vue_activite_agents` (
`firstname` varchar(255)
,`Id_admin` bigint unsigned
,`lastname` varchar(255)
,`nombre_contacts` bigint
,`nombre_suivis` bigint
,`nombre_visites` bigint
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_biens_disponibles`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `vue_biens_disponibles`;
CREATE TABLE IF NOT EXISTS `vue_biens_disponibles` (
`adresse` varchar(50)
,`categorie` varchar(255)
,`charges` decimal(10,2)
,`description` varchar(255)
,`disponibilite_date` date
,`dpe_energie` char(1)
,`dpe_ges` char(1)
,`Id_appartements` int
,`lits` int
,`nombre_contacts` bigint
,`nombre_visites` bigint
,`pays` varchar(50)
,`photos` varchar(255)
,`prix` varchar(100)
,`reference` varchar(20)
,`salle_de_bains` int
,`status` int
,`superficie` varchar(255)
,`taxe_fonciere` decimal(10,2)
,`type_bien` varchar(50)
,`type_id` int
,`ville` varchar(50)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_biens_stats`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `vue_biens_stats`;
CREATE TABLE IF NOT EXISTS `vue_biens_stats` (
`categorie` varchar(255)
,`Id_appartements` int
,`jours_en_vente` int
,`nombre_contacts` bigint
,`nombre_visites` bigint
,`prix` varchar(100)
,`reference` varchar(20)
,`status` int
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_stats_agents_mensuel`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `vue_stats_agents_mensuel`;
CREATE TABLE IF NOT EXISTS `vue_stats_agents_mensuel` (
`contacts_traites` bigint
,`firstname` varchar(255)
,`Id_admin` bigint unsigned
,`lastname` varchar(255)
,`mois` varchar(7)
,`visites_effectuees` bigint
,`visites_reussies` bigint
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_suivi_contacts`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `vue_suivi_contacts`;
CREATE TABLE IF NOT EXISTS `vue_suivi_contacts` (
`agent_id` bigint unsigned
,`agent_nom` varchar(255)
,`agent_prenom` varchar(255)
,`appartement_id` int
,`date_creation` timestamp
,`email` varchar(255)
,`id` int
,`message` text
,`nom` varchar(255)
,`prix_bien` varchar(100)
,`reference_bien` varchar(20)
,`statut_suivi` enum('nouveau','contacté','rendez_vous_fixé','visite_effectuée','sans_suite')
,`telephone` varchar(20)
,`type_demande` enum('information','visite','autre')
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_tableau_bord`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `vue_tableau_bord`;
CREATE TABLE IF NOT EXISTS `vue_tableau_bord` (
`biens_disponibles` bigint
,`biens_en_travaux` bigint
,`biens_loues` bigint
,`contacts_total` bigint
,`prix_moyen` decimal(14,6)
,`visites_total` bigint
);

-- --------------------------------------------------------

--
-- Structure de la vue `vue_activite_agents`
--
DROP TABLE IF EXISTS `vue_activite_agents`;

DROP VIEW IF EXISTS `vue_activite_agents`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vue_activite_agents`  AS SELECT `a`.`Id_admin` AS `Id_admin`, `a`.`firstname` AS `firstname`, `a`.`lastname` AS `lastname`, count(distinct `v`.`Id_visite`) AS `nombre_visites`, count(distinct `d`.`id`) AS `nombre_contacts`, count(distinct `s`.`id`) AS `nombre_suivis` FROM (((`admin` `a` left join `visites` `v` on((`v`.`agent_id` = `a`.`Id_admin`))) left join `demandes_contact` `d` on((`d`.`agent_id` = `a`.`Id_admin`))) left join `suivi_commercial` `s` on((`s`.`agent_id` = `a`.`Id_admin`))) GROUP BY `a`.`Id_admin` ;

-- --------------------------------------------------------

--
-- Structure de la vue `vue_biens_disponibles`
--
DROP TABLE IF EXISTS `vue_biens_disponibles`;

DROP VIEW IF EXISTS `vue_biens_disponibles`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vue_biens_disponibles`  AS SELECT `a`.`Id_appartements` AS `Id_appartements`, `a`.`reference` AS `reference`, `a`.`photos` AS `photos`, `a`.`categorie` AS `categorie`, `a`.`type_id` AS `type_id`, `a`.`superficie` AS `superficie`, `a`.`lits` AS `lits`, `a`.`salle_de_bains` AS `salle_de_bains`, `a`.`status` AS `status`, `a`.`disponibilite_date` AS `disponibilite_date`, `a`.`description` AS `description`, `a`.`prix` AS `prix`, `a`.`adresse` AS `adresse`, `a`.`ville` AS `ville`, `a`.`pays` AS `pays`, `a`.`dpe_energie` AS `dpe_energie`, `a`.`dpe_ges` AS `dpe_ges`, `a`.`charges` AS `charges`, `a`.`taxe_fonciere` AS `taxe_fonciere`, `t`.`nom` AS `type_bien`, count(distinct `v`.`Id_visite`) AS `nombre_visites`, count(distinct `d`.`id`) AS `nombre_contacts` FROM (((`appartements` `a` left join `type_appartements` `t` on((`a`.`type_id` = `t`.`Id_type`))) left join `visites` `v` on((`v`.`Id_appartements` = `a`.`Id_appartements`))) left join `demandes_contact` `d` on((`d`.`appartement_id` = `a`.`Id_appartements`))) WHERE (`a`.`status` = 'disponible') GROUP BY `a`.`Id_appartements` ;

-- --------------------------------------------------------

--
-- Structure de la vue `vue_biens_stats`
--
DROP TABLE IF EXISTS `vue_biens_stats`;

DROP VIEW IF EXISTS `vue_biens_stats`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vue_biens_stats`  AS SELECT `a`.`Id_appartements` AS `Id_appartements`, `a`.`reference` AS `reference`, `a`.`categorie` AS `categorie`, `a`.`prix` AS `prix`, `a`.`status` AS `status`, count(distinct `v`.`Id_visite`) AS `nombre_visites`, count(distinct `d`.`id`) AS `nombre_contacts`, (to_days(curdate()) - to_days(`a`.`disponibilite_date`)) AS `jours_en_vente` FROM ((`appartements` `a` left join `visites` `v` on((`v`.`Id_appartements` = `a`.`Id_appartements`))) left join `demandes_contact` `d` on((`d`.`appartement_id` = `a`.`Id_appartements`))) GROUP BY `a`.`Id_appartements` ;

-- --------------------------------------------------------

--
-- Structure de la vue `vue_stats_agents_mensuel`
--
DROP TABLE IF EXISTS `vue_stats_agents_mensuel`;

DROP VIEW IF EXISTS `vue_stats_agents_mensuel`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vue_stats_agents_mensuel`  AS SELECT `a`.`Id_admin` AS `Id_admin`, `a`.`firstname` AS `firstname`, `a`.`lastname` AS `lastname`, date_format(`v`.`date_visite`,'%Y-%m') AS `mois`, count(distinct `v`.`Id_visite`) AS `visites_effectuees`, count(distinct `dc`.`id`) AS `contacts_traites`, count(distinct (case when (`v`.`statut` = 'effectuée') then `v`.`Id_visite` end)) AS `visites_reussies` FROM ((`admin` `a` left join `visites` `v` on((`v`.`agent_id` = `a`.`Id_admin`))) left join `demandes_contact` `dc` on((`dc`.`agent_id` = `a`.`Id_admin`))) GROUP BY `a`.`Id_admin`, date_format(`v`.`date_visite`,'%Y-%m') ORDER BY `mois` DESC, `visites_effectuees` DESC ;

-- --------------------------------------------------------

--
-- Structure de la vue `vue_suivi_contacts`
--
DROP TABLE IF EXISTS `vue_suivi_contacts`;

DROP VIEW IF EXISTS `vue_suivi_contacts`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vue_suivi_contacts`  AS SELECT `dc`.`id` AS `id`, `dc`.`nom` AS `nom`, `dc`.`email` AS `email`, `dc`.`telephone` AS `telephone`, `dc`.`message` AS `message`, `dc`.`type_demande` AS `type_demande`, `dc`.`appartement_id` AS `appartement_id`, `dc`.`date_creation` AS `date_creation`, `dc`.`statut_suivi` AS `statut_suivi`, `dc`.`agent_id` AS `agent_id`, `a`.`reference` AS `reference_bien`, `a`.`prix` AS `prix_bien`, `admin`.`firstname` AS `agent_nom`, `admin`.`lastname` AS `agent_prenom` FROM ((`demandes_contact` `dc` left join `appartements` `a` on((`dc`.`appartement_id` = `a`.`Id_appartements`))) left join `admin` on((`dc`.`agent_id` = `admin`.`Id_admin`))) ORDER BY `dc`.`date_creation` DESC ;

-- --------------------------------------------------------

--
-- Structure de la vue `vue_tableau_bord`
--
DROP TABLE IF EXISTS `vue_tableau_bord`;

DROP VIEW IF EXISTS `vue_tableau_bord`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vue_tableau_bord`  AS SELECT count((case when (`a`.`status` = 0) then 1 end)) AS `biens_disponibles`, count((case when (`a`.`status` = 1) then 1 end)) AS `biens_loues`, count((case when (`a`.`status` = 2) then 1 end)) AS `biens_en_travaux`, avg(cast(replace(`a`.`prix`,' ','') as decimal(10,2))) AS `prix_moyen`, count(distinct `v`.`Id_visite`) AS `visites_total`, count(distinct `dc`.`id`) AS `contacts_total` FROM ((`appartements` `a` left join `visites` `v` on((`v`.`Id_appartements` = `a`.`Id_appartements`))) left join `demandes_contact` `dc` on((`dc`.`appartement_id` = `a`.`Id_appartements`))) ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `appartements`
--
ALTER TABLE `appartements` ADD FULLTEXT KEY `recherche_appartement` (`description`,`adresse`,`ville`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `appartements`
--
ALTER TABLE `appartements`
  ADD CONSTRAINT `appartements_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `type_appartements` (`Id_type`);

--
-- Contraintes pour la table `avis_biens`
--
ALTER TABLE `avis_biens`
  ADD CONSTRAINT `avis_biens_ibfk_1` FOREIGN KEY (`appartement_id`) REFERENCES `appartements` (`Id_appartements`);

--
-- Contraintes pour la table `demandes_contact`
--
ALTER TABLE `demandes_contact`
  ADD CONSTRAINT `demandes_contact_ibfk_1` FOREIGN KEY (`appartement_id`) REFERENCES `appartements` (`Id_appartements`),
  ADD CONSTRAINT `demandes_contact_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `admin` (`Id_admin`);

--
-- Contraintes pour la table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`appartement_id`) REFERENCES `appartements` (`Id_appartements`) ON DELETE SET NULL,
  ADD CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id_users`) ON DELETE SET NULL;

--
-- Contraintes pour la table `equipements`
--
ALTER TABLE `equipements`
  ADD CONSTRAINT `equipements_ibfk_1` FOREIGN KEY (`appartement_id`) REFERENCES `appartements` (`Id_appartements`) ON DELETE CASCADE;

--
-- Contraintes pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `favoris_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id_users`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoris_ibfk_2` FOREIGN KEY (`apartment_id`) REFERENCES `appartements` (`Id_appartements`) ON DELETE CASCADE;

--
-- Contraintes pour la table `interventions_maintenance`
--
ALTER TABLE `interventions_maintenance`
  ADD CONSTRAINT `interventions_maintenance_ibfk_1` FOREIGN KEY (`maintenance_id`) REFERENCES `maintenance` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `maintenance`
--
ALTER TABLE `maintenance`
  ADD CONSTRAINT `maintenance_ibfk_1` FOREIGN KEY (`appartement_id`) REFERENCES `appartements` (`Id_appartements`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenance_ibfk_2` FOREIGN KEY (`responsable_id`) REFERENCES `users` (`Id_users`) ON DELETE SET NULL;

--
-- Contraintes pour la table `suivi_commercial`
--
ALTER TABLE `suivi_commercial`
  ADD CONSTRAINT `suivi_commercial_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `demandes_contact` (`id`),
  ADD CONSTRAINT `suivi_commercial_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `admin` (`Id_admin`);

--
-- Contraintes pour la table `visites`
--
ALTER TABLE `visites`
  ADD CONSTRAINT `visites_agent_fk` FOREIGN KEY (`agent_id`) REFERENCES `admin` (`Id_admin`),
  ADD CONSTRAINT `visites_ibfk_1` FOREIGN KEY (`Id_appartements`) REFERENCES `appartements` (`Id_appartements`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
