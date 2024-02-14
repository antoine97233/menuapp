-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 14 fév. 2024 à 10:29
-- Version du serveur : 5.7.36
-- Version de PHP : 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `appmenu`
--

-- --------------------------------------------------------

--
-- Structure de la table `apm_admin_list`
--

DROP TABLE IF EXISTS `apm_admin_list`;
CREATE TABLE IF NOT EXISTS `apm_admin_list` (
  `adminId` int(11) NOT NULL AUTO_INCREMENT,
  `adminEmail` varchar(255) NOT NULL,
  `adminPassword` varchar(255) NOT NULL,
  `adminName` varchar(255) NOT NULL,
  `adminSuper` tinyint(1) NOT NULL,
  PRIMARY KEY (`adminId`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `apm_admin_list`
--

INSERT INTO `apm_admin_list` (`adminId`, `adminEmail`, `adminPassword`, `adminName`, `adminSuper`) VALUES
(59, 'test@test.com', '$2y$10$z8O6ysqRPT1nFhVRZ2J8tOVvsYJQtAowTNMVg0YHeYQMg7eBhSefK', 'Antoine', 1);

-- --------------------------------------------------------

--
-- Structure de la table `apm_category_list`
--

DROP TABLE IF EXISTS `apm_category_list`;
CREATE TABLE IF NOT EXISTS `apm_category_list` (
  `categoryId` int(11) NOT NULL AUTO_INCREMENT,
  `categoryTitle` varchar(255) NOT NULL,
  `categoryDescription` varchar(255) NOT NULL,
  `groupId` int(11) NOT NULL,
  `categoryRank` int(11) NOT NULL,
  `categorySlug` varchar(255) NOT NULL,
  PRIMARY KEY (`categoryId`),
  KEY `category_entite_FK` (`groupId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `apm_group_list`
--

DROP TABLE IF EXISTS `apm_group_list`;
CREATE TABLE IF NOT EXISTS `apm_group_list` (
  `groupId` int(11) NOT NULL AUTO_INCREMENT,
  `groupTitle` varchar(255) NOT NULL,
  `groupDescription` varchar(255) NOT NULL,
  `groupSlug` varchar(255) NOT NULL,
  PRIMARY KEY (`groupId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `apm_item_list`
--

DROP TABLE IF EXISTS `apm_item_list`;
CREATE TABLE IF NOT EXISTS `apm_item_list` (
  `itemId` int(11) NOT NULL AUTO_INCREMENT,
  `itemTitle` varchar(255) NOT NULL,
  `itemDescription` varchar(255) NOT NULL,
  `itemPrice` varchar(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `itemImagePath` varchar(255) NOT NULL,
  `itemStock` int(11) DEFAULT NULL,
  `itemSlug` varchar(255) NOT NULL,
  PRIMARY KEY (`itemId`),
  KEY `item_category_FK` (`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `apm_menu_list`
--

DROP TABLE IF EXISTS `apm_menu_list`;
CREATE TABLE IF NOT EXISTS `apm_menu_list` (
  `menuId` int(11) NOT NULL AUTO_INCREMENT,
  `menuTitle` varchar(255) NOT NULL,
  `menuPath` varchar(255) NOT NULL,
  PRIMARY KEY (`menuId`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `apm_slider_list`
--

DROP TABLE IF EXISTS `apm_slider_list`;
CREATE TABLE IF NOT EXISTS `apm_slider_list` (
  `sliderId` int(11) NOT NULL AUTO_INCREMENT,
  `sliderName` varchar(255) NOT NULL,
  `sliderTitle` varchar(255) NOT NULL,
  `sliderDescription` varchar(255) NOT NULL,
  `sliderImagePath` varchar(255) NOT NULL,
  `sliderRank` int(11) NOT NULL,
  `sliderSlug` varchar(255) NOT NULL,
  PRIMARY KEY (`sliderId`)
) ENGINE=MyISAM AUTO_INCREMENT=425 DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `apm_category_list`
--
ALTER TABLE `apm_category_list`
  ADD CONSTRAINT `category_entite_FK` FOREIGN KEY (`groupId`) REFERENCES `apm_group_list` (`groupId`);

--
-- Contraintes pour la table `apm_item_list`
--
ALTER TABLE `apm_item_list`
  ADD CONSTRAINT `item_category_FK` FOREIGN KEY (`categoryId`) REFERENCES `apm_category_list` (`categoryId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
