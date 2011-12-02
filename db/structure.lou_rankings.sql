-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 03, 2011 at 12:46 AM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `lou_rankings`
--

-- --------------------------------------------------------

--
-- Table structure for table `alliances`
--

DROP TABLE IF EXISTS `alliances`;
CREATE TABLE IF NOT EXISTS `alliances` (
  `alliance_sid` int(11) NOT NULL AUTO_INCREMENT,
  `date_sid` int(11) NOT NULL,
  `alliance_id` int(11) DEFAULT NULL,
  `alliance_name` varchar(100) DEFAULT NULL,
  `alliance_average_score` int(11) DEFAULT NULL,
  `alliance_score` int(11) DEFAULT NULL,
  `alliance_ranking` int(11) DEFAULT NULL,
  `alliance_cities_count` int(11) DEFAULT NULL,
  `alliance_members_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`alliance_sid`,`date_sid`),
  KEY `fk_alliances_dates1` (`date_sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59693 ;

-- --------------------------------------------------------

--
-- Table structure for table `dates`
--

DROP TABLE IF EXISTS `dates`;
CREATE TABLE IF NOT EXISTS `dates` (
  `date_sid` int(11) NOT NULL AUTO_INCREMENT,
  `date_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`date_sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=110 ;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
CREATE TABLE IF NOT EXISTS `players` (
  `player_sid` int(11) NOT NULL AUTO_INCREMENT,
  `date_sid` int(11) NOT NULL,
  `player_id` int(11) DEFAULT NULL,
  `player_name` varchar(100) DEFAULT NULL,
  `alliance_id` int(11) DEFAULT NULL,
  `alliance_name` varchar(100) DEFAULT NULL,
  `player_points` int(11) DEFAULT NULL,
  `player_ranking` int(11) DEFAULT NULL,
  `player_cities` int(11) DEFAULT NULL,
  `player_status` varchar(10) DEFAULT NULL,
  `player_defensive_fame` int(11) DEFAULT NULL,
  `player_defensive_rank` int(11) DEFAULT NULL,
  `player_offensive_fame` int(11) DEFAULT NULL,
  `player_offensive_rank` int(11) DEFAULT NULL,
  PRIMARY KEY (`player_sid`,`date_sid`),
  KEY `fk_players_dates` (`date_sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=888350 ;

-- --------------------------------------------------------

--
-- Table structure for table `world_stats`
--

DROP TABLE IF EXISTS `world_stats`;
CREATE TABLE IF NOT EXISTS `world_stats` (
  `world_stat_sid` int(11) NOT NULL AUTO_INCREMENT,
  `date_sid` int(11) NOT NULL,
  `world_average_player_points` float DEFAULT NULL,
  `world_total_players` int(11) DEFAULT NULL,
  PRIMARY KEY (`world_stat_sid`,`date_sid`),
  KEY `fk_global_stats_dates1` (`date_sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=102 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alliances`
--
ALTER TABLE `alliances`
  ADD CONSTRAINT `fk_alliances_dates1` FOREIGN KEY (`date_sid`) REFERENCES `dates` (`date_sid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `players`
--
ALTER TABLE `players`
  ADD CONSTRAINT `fk_players_dates` FOREIGN KEY (`date_sid`) REFERENCES `dates` (`date_sid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `world_stats`
--
ALTER TABLE `world_stats`
  ADD CONSTRAINT `fk_global_stats_dates1` FOREIGN KEY (`date_sid`) REFERENCES `dates` (`date_sid`) ON DELETE CASCADE ON UPDATE CASCADE;
