-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 30, 2016 at 08:58 PM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `6786_prenotazioni`
--

-- --------------------------------------------------------

--
-- Table structure for table `Colori`
--

DROP TABLE IF EXISTS `Colori`;
CREATE TABLE `Colori` (
  `ID` int(11) NOT NULL,
  `colore` varchar(7) NOT NULL,
  `last` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Colori`
--

INSERT INTO `Colori` (`ID`, `colore`, `last`) VALUES
(0, '#0061D1', 1),
(1, '#00A823', 0),
(2, '#DA38FF', 0),
(3, '#FFD02E', 0),
(4, '#B0B0B0', 0),
(5, '#A84B00', 0),
(6, '#7E38FF', 0),
(7, '#00D1C5', 0),
(8, '#CFCFCF', 0),
(9, '#FF0D0A', 0),
(10, '#FF7AA6', 0),
(11, '#FF781A', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Colori`
--
ALTER TABLE `Colori`
  ADD PRIMARY KEY (`ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
