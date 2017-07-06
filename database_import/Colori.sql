-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 06, 2017 at 05:13 PM
-- Server version: 5.6.33
-- PHP Version: 5.2.4-2ubuntu5.27

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `6786_prenotazioni`
--

-- --------------------------------------------------------

--
-- Table structure for table `Colori`
--

CREATE TABLE IF NOT EXISTS `Colori` (
  `ID` int(11) NOT NULL,
  `colore` varchar(7) NOT NULL,
  `last` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Colori`
--

INSERT INTO `Colori` (`ID`, `colore`, `last`) VALUES
(0, '#0061D1', 97),
(1, '#00A823', 98),
(2, '#DA38FF', 99),
(3, '#FFD02E', 100),
(4, '#B0B0B0', 101),
(5, '#A84B00', 102),
(6, '#7E38FF', 103),
(7, '#00D1C5', 104),
(8, '#CFCFCF', 105),
(9, '#FF0D0A', 106),
(10, '#FF7AA6', 107),
(11, '#FF781A', 96);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
