-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 29 mai 2019 à 08:11
-- Version du serveur :  5.7.19
-- Version de PHP :  7.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `veville`
--

-- --------------------------------------------------------

--
-- Structure de la table `agence`
--

DROP TABLE IF EXISTS `agence`;
CREATE TABLE IF NOT EXISTS `agence` (
  `id_agence` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `code_postal` int(3) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  PRIMARY KEY (`id_agence`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `agence`
--

INSERT INTO `agence` (`id_agence`, `titre`, `adresse`, `ville`, `code_postal`, `description`, `photo`) VALUES
(1, 'Agence de Paris', '300 boulevard de vaugirard', 'Paris', 75015, 'Notre agence de Paris est ouvert de 09h a 18h tout les jours. ', 'Paris_Tour-Eiffel.jpg'),
(2, 'Agence de Lyon', '4 rue sainte catherine ', 'Lyon', 69003, 'Notre agence de Lyon est ouvert de 09h a 18h tout les jours. ', 'Agence Lyon_agence_lyon.jpg'),
(3, 'Agence de Bordeaux', '10 Place de l&#039;hotel de ville ', 'Bordeaux', 77005, 'Notre agence de Bordeaux est ouvert de 09h a 18h tout les jours. ', 'bordeau_agence_bordeaux.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` int(11) NOT NULL AUTO_INCREMENT,
  `id_membre` int(3) NOT NULL,
  `id_vehicule` int(3) NOT NULL,
  `id_agence` int(3) NOT NULL,
  `date_heure_depart` datetime NOT NULL,
  `date_heure_fin` datetime NOT NULL,
  `prix_total` int(3) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `id_membre` (`id_membre`),
  KEY `id_vehicule` (`id_vehicule`),
  KEY `id_agence` (`id_agence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `id_membre` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(60) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `statut` int(3) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `statut`, `date_enregistrement`) VALUES
(4, 'Bilel', '5c0576dc596b1ba2f9b13847ddb4214c', 'oufkir', 'bilel', 'biilel_95@hotmail.fr', 'm', 1, '2019-05-21 14:00:21'),
(5, 'damien', '8e219c916a8e3fa7dabb96986df14e08', 'daheb', 'damien', 'damien_daheb@hotmail.fr', 'm', 0, '2019-05-22 12:18:22');

-- --------------------------------------------------------

--
-- Structure de la table `vehicule`
--

DROP TABLE IF EXISTS `vehicule`;
CREATE TABLE IF NOT EXISTS `vehicule` (
  `id_vehicule` int(11) NOT NULL AUTO_INCREMENT,
  `id_agence` int(3) NOT NULL,
  `titre` varchar(200) NOT NULL,
  `marque` varchar(50) NOT NULL,
  `modele` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  `prix_journalier` int(3) NOT NULL,
  PRIMARY KEY (`id_vehicule`),
  KEY `id_agence` (`id_agence`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `vehicule`
--

INSERT INTO `vehicule` (`id_vehicule`, `id_agence`, `titre`, `marque`, `modele`, `description`, `photo`, `prix_journalier`) VALUES
(3, 1, 'BMW X6', 'BMW', 'X6', 'Le grand point fort de ce modèle réside dans le confort .', 'MERCEDES GLS_2015-bmw-x6.png', 320),
(4, 2, 'BMW', 'BMW', 'Serie 7', 'Le grand point fort de ce modèle réside dans le confort .', 'BMW_BMWserie5-e1488984551160.png', 280),
(5, 2, 'MERCEDES GLS', 'MERCEDES ', 'GLS', 'Le grand point fort de ce modèle réside dans le confort .', 'MERCEDES GLS_MERCEDES GLS_2015-bmw-x6.png', 400),
(7, 3, 'MERCEDES G33', 'Mercedes', 'G33', 'Le grand point fort de ce modèle réside dans le confort .', 'MERCEDES G33_mercedes-g33.png', 430),
(8, 1, 'Porsche cayenne ', 'Porsche', 'Cayenne', 'Le grand point fort de ce modèle réside dans le confort .', 'Porsche cayenne _porsche-cayenne.png', 380),
(9, 3, 'MERCEDES CLASSE E ', 'MERCEDES ', 'classe E', 'Le grand point fort de ce modèle réside dans le confort .', 'MERCEDES CLASSE E _mercedesclasseE.png', 290),
(10, 3, 'AUDI Q7', 'AUDI ', 'Q7', 'Le grand point fort de ce modèle réside dans le confort .', 'AUDI Q7_AudiQ7.png', 450),
(11, 2, 'Bentley continental GT', 'Bentley', 'continental GT', 'Le grand point fort de ce modèle réside dans le confort .', 'Bentley continental GT_bentleycontinentalGT.png', 520),
(12, 1, 'Rolls Royce Ghost', 'Rolls Royce ', 'Ghost', 'Le grand point fort de ce modèle réside dans le confort .', 'Rolls Royce Ghost_2014-Rolls-Royce-Ghost.png', 650),
(13, 1, 'Ferrari - 458', 'Ferrari', '458', 'véhicule très rapide et efficace  ', 'MERCEDES GLS_ferrari458-1.png', 900),
(14, 1, 'MERCEDES Classe C', 'MERCEDES ', 'Classe C', 'Excellent Véhicule pour s’évader ', 'MERCEDES Classe C_mercedesclassescab.png', 600),
(15, 2, 'Porsche Panamera', 'Porsche', 'Panamera', 'Véhicule de qualité supérieur ', 'Porsche Panamera_photo-porsche-panamera-2016-1-009.png', 480),
(16, 3, 'Range Rover Sport', 'Range Rover', 'Sport', 'Véhicule puissant et confortable ', 'Range Rover Sport_Rangeroversport.png', 415),
(17, 2, 'Lamborghini Aventador', 'Lamborghini', 'Aventador', 'Le grand point fort de ce modèle réside dans le confort .', 'Lamborghini Aventador_lamborghini_aventador.png', 1200),
(18, 3, 'Lamborghini Huracan', 'Lamborghini', 'Huracan', 'Le grand point fort de ce modèle réside dans le confort .', 'Lamborghini Huracan_huracan-jaune.png', 1180),
(19, 1, 'Porsche 911', 'Porsche', '911', 'Le grand point fort de ce modèle réside dans le confort .', 'Porsche 911_porsche911.png', 680),
(24, 2, 'MERCEDES SL', 'MERCEDES ', 'SL', 'Véhicule très confortable et rapide', 'MERCEDES SL_mercedes-sl.png', 640),
(25, 1, 'MERCEDES AMG GT', 'MERCEDES ', 'AMG GT', 'Véhicule très rapide , moteur performant ', 'MERCEDES AMG GT_mercedesamggt.png', 740);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_vehicule`) REFERENCES `vehicule` (`id_vehicule`),
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`),
  ADD CONSTRAINT `commande_ibfk_3` FOREIGN KEY (`id_agence`) REFERENCES `agence` (`id_agence`);

--
-- Contraintes pour la table `vehicule`
--
ALTER TABLE `vehicule`
  ADD CONSTRAINT `vehicule_ibfk_1` FOREIGN KEY (`id_agence`) REFERENCES `agence` (`id_agence`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
