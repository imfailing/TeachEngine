-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 04, 2012 at 08:46 PM
-- Server version: 5.1.40
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `training`
--
CREATE DATABASE `training` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `training`;

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `category` int(2) NOT NULL,
  `topic` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `author` int(11) NOT NULL,
  `maxusers` int(2) NOT NULL,
  `state` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`id`, `date`, `time`, `category`, `topic`, `description`, `author`, `maxusers`, `state`) VALUES
(1, '2012-05-27', '10:31:00', 0, 'Ололо', 'fgsfds', 1, 10, 1),
(2, '2012-05-27', '10:40:00', 0, 'Оллоло', 'Раз Раз Раз', 1, 10, 1),
(3, '2012-05-27', '10:40:00', 0, 'Оллоло', 'Раз Раз Раз', 1, 10, 1),
(10, '2012-05-29', '12:21:00', 1, 'asd', 'wqwqewqqwweq', 1, 0, 3),
(9, '2012-05-30', '10:25:00', 1, 'asd', 'qwe', 1, 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `users_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `users_login` varchar(30) NOT NULL,
  `users_password` varchar(32) NOT NULL,
  `users_hash` varchar(32) NOT NULL,
  `users_name` varchar(50) NOT NULL,
  `users_email` varchar(64) NOT NULL,
  `users_type` tinyint(4) NOT NULL,
  PRIMARY KEY (`users_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`users_id`, `users_login`, `users_password`, `users_hash`, `users_name`, `users_email`, `users_type`) VALUES
(1, 'admin', 'c3284d0f94606de1fd2af172aba15bf3', '1f90044e9c9735930a3b2d1229e53c18', 'fgsfds', '', 0);
