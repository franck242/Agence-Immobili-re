-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 05 jan. 2025 à 02:43
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
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`Id_admin`),
  UNIQUE KEY `Id_users` (`Id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`Id_admin`, `photos`, `firstname`, `email`, `password`) VALUES
(2, 'user-solid.svg', 'sanji', 'sanji.vinsmoke@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef');

-- --------------------------------------------------------

--
-- Structure de la table `appartements`
--

DROP TABLE IF EXISTS `appartements`;
CREATE TABLE IF NOT EXISTS `appartements` (
  `Id_appartements` int NOT NULL AUTO_INCREMENT,
  `photos` varchar(255) NOT NULL,
  `categorie` varchar(255) NOT NULL,
  `superficie` varchar(255) NOT NULL,
  `lits` int NOT NULL,
  `salle_de_bains` int NOT NULL,
  `status` int NOT NULL COMMENT '0=libre,1=louer,2=trvaux',
  `description` varchar(255) NOT NULL,
  `prix` varchar(100) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `pays` varchar(50) NOT NULL,
  PRIMARY KEY (`Id_appartements`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `appartements`
--

INSERT INTO `appartements` (`Id_appartements`, `photos`, `categorie`, `superficie`, `lits`, `salle_de_bains`, `status`, `description`, `prix`, `adresse`, `ville`, `pays`) VALUES
(5, 'pexels-pixabay-275484.jpg', 'Appartement T3 Prestige', '85 m²', 2, 1, 0, 'Magnifique T3 avec vue panoramique, cuisine équipée moderne, grand séjour lumineux, prestations haut de gamme', '1800', '15 rue de la République', 'Lyon', 'France'),
(6, 'pexels-john-tekeridis-1428348.jpg', 'Duplex Contemporain', '110 m²', 3, 2, 0, 'Superbe duplex contemporain avec terrasse, triple exposition, cuisine américaine équipée, proche toutes commodités', '2200', '25 cours Lafayette', 'Lyon', 'France'),
(11, 'pexels-pixabay-275484.jpg', 'Studio Design Centre', '35 m²', 1, 1, 0, 'Studio moderne entièrement rénové, parfait pour étudiant ou jeune actif, proche transports et commerces', '750', '8 rue des Capucins', 'Lyon', 'France'),
(12, 'pexels-vecislavas-popa-1571468.jpg', 'Grand T4 Familial', '95 m²', 3, 2, 0, 'Spacieux T4 idéal pour famille, quartier calme et résidentiel, proche écoles et parcs, garage inclus', '1950', '45 avenue Jean Jaurès', 'Lyon', 'France'),
(13, 'pexels-vecislavas-popa-1571460.jpg', 'T2 Charme Ancien', '55 m²', 1, 1, 0, 'Charmant T2 dans immeuble ancien rénové, parquet, moulures, belle hauteur sous plafond, balcon', '1100', '12 rue des Remparts', 'Lyon', 'France'),
(17, 'pexels-vecislavas-popa-813692.jpg', 'Loft Design Part-Dieu', '75 m²', 2, 1, 0, 'Magnifique loft contemporain, volumes exceptionnels, prestations luxueuses, proche Part-Dieu', '1600', '30 rue de la Villette', 'Lyon', 'France');

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `message`, `apartment_id`, `created_at`) VALUES
(1, 'niani lily', 'lily14789@gmx.fr', '0641253695', 'je le veut', 6, '2025-01-05 01:01:57'),
(2, 'niani lily', 'lily14789@gmx.fr', '0641253695', 'je le veut', NULL, '2025-01-05 01:04:17');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `Id_reservation` int NOT NULL AUTO_INCREMENT,
  `Id_appartements` int NOT NULL,
  `date_depart` date NOT NULL,
  `date_retour` date NOT NULL,
  `civilite` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `code_postal` varchar(255) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `animaux` varchar(255) NOT NULL,
  PRIMARY KEY (`Id_reservation`),
  KEY `Id_appartements` (`Id_appartements`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`Id_reservation`, `Id_appartements`, `date_depart`, `date_retour`, `civilite`, `firstname`, `lastname`, `email`, `adresse`, `code_postal`, `ville`, `telephone`, `animaux`) VALUES
(5, 5, '2023-06-20', '2023-06-21', 'monsieur', 'eustass', 'kid', 'eustass.kid@gmail.com', '15 rue des marrais', '38000', 'grenoble', '0142589636', 'lezard');

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
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id_users`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `favoris_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id_users`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoris_ibfk_2` FOREIGN KEY (`apartment_id`) REFERENCES `appartements` (`Id_appartements`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`Id_appartements`) REFERENCES `appartements` (`Id_appartements`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
