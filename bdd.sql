-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql51-93.perso
-- Generation Time: Mar 09, 2019 at 08:58 PM
-- Server version: 5.5.60-0+deb7u1-log
-- PHP Version: 5.6.38-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `geekfactgrib8s`
--

-- --------------------------------------------------------

--
-- Table structure for table `ebooks_auteurvalid`
--

CREATE TABLE IF NOT EXISTS `ebooX_auteurvalid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auteur` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=877 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ebooks_doubloncheck`
--

CREATE TABLE IF NOT EXISTS `ebooX_doubloncheck` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ebooks_ebooks`
--

CREATE TABLE IF NOT EXISTS `ebooX_ebooks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `titre` text NOT NULL,
  `auteur` text NOT NULL,
  `descr` text NOT NULL,
  `date` datetime NOT NULL,
  `sujet` text NOT NULL,
  `identifier` text NOT NULL,
  `lang` text NOT NULL,
  `filename` text NOT NULL,
  `pathfile` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4488 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ebooks_favoris`
--

CREATE TABLE IF NOT EXISTS `ebooX_favoris` (
  `user` int(11) NOT NULL,
  `book` int(11) NOT NULL,
  UNIQUE KEY `book` (`book`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ebooks_users`
--

CREATE TABLE IF NOT EXISTS `ebooX_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(25) NOT NULL,
  `pass` text NOT NULL,
  `email` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `valid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
