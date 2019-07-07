-- phpMyAdmin SQL Dump
-- version 4.7.3
-- Generation Time: Jul 07, 2019 at 02:27 PM
-- Server version: 5.6.42-log
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;



-- --------------------------------------------------------

--
-- Table structure for table `ebooX_auteurvalid`
--

CREATE TABLE `ebooX_auteurvalid` (
  `id` int(11) NOT NULL,
  `auteur` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `ebooX_doubloncheck`
--

CREATE TABLE `ebooX_doubloncheck` (
  `id` int(11) NOT NULL,
  `titre` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `ebooX_ebooX`
--

CREATE TABLE `ebooX_ebooX` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `titre` text NOT NULL,
  `auteur` text NOT NULL,
  `descr` text NOT NULL,
  `date` datetime NOT NULL,
  `sujet` text NOT NULL,
  `identifier` text NOT NULL,
  `lang` text NOT NULL,
  `filename` text NOT NULL,
  `pathfile` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



--
-- Table structure for table `ebooX_ebooks_infos`
--

CREATE TABLE `ebooX_ebooks_infos` (
  `id` int(11) NOT NULL,
  `book` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `dl` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `ebooX_favoris`
--

CREATE TABLE `ebooX_favoris` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `book` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



--
-- Table structure for table `ebooX_listelec`
--

CREATE TABLE `ebooX_listelec` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `book` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



--
-- Table structure for table `ebooX_messages`
--

CREATE TABLE `ebooX_messages` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `ebooX_users`
--

CREATE TABLE `ebooX_users` (
  `id` int(11) NOT NULL,
  `nick` varchar(25) NOT NULL,
  `pass` text NOT NULL,
  `email` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `valid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Indexes for table `ebooX_auteurvalid`
--
ALTER TABLE `ebooX_auteurvalid`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebooX_doubloncheck`
--
ALTER TABLE `ebooX_doubloncheck`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebooX_ebooX`
--
ALTER TABLE `ebooX_ebooX`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebooX_ebooX_infos`
--
ALTER TABLE `ebooX_ebooX_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebooX_favoris`
--
ALTER TABLE `ebooX_favoris`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `ebooX_listelec`
--
ALTER TABLE `ebooX_listelec`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `ebooX_messages`
--
ALTER TABLE `ebooX_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebooX_users`
--
ALTER TABLE `ebooX_users`
  ADD PRIMARY KEY (`id`);

