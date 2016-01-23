-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 23, 2016 at 11:09 下午
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `6786_pernottamenti`
--

-- --------------------------------------------------------

--
-- Table structure for table `Colori`
--

CREATE TABLE `Colori` (
  `ID` int(11) NOT NULL,
  `colore` varchar(7) NOT NULL,
  `last` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Colori`
--

INSERT INTO `Colori` (`ID`, `colore`, `last`) VALUES
(0, '#0061D1', 0),
(1, '#00A823', 0),
(2, '#DA38FF', 0),
(3, '#FFD02E', 0),
(4, '#B0B0B0', 0),
(5, '#A84B00', 0),
(6, '#7E38FF', 0),
(7, '#00D1C5', 0),
(8, '#EFEFEF', 1),
(9, '#FF0D0A', 0),
(10, '#FF7AA6', 0),
(11, '#FF781A', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Pernottamenti`
--

CREATE TABLE `Pernottamenti` (
  `id` int(5) NOT NULL,
  `nome` varchar(100) COLLATE utf8_bin NOT NULL,
  `tel` varchar(15) COLLATE utf8_bin NOT NULL,
  `giorno_inizio` int(3) NOT NULL,
  `durata` int(3) NOT NULL,
  `posti` int(2) NOT NULL DEFAULT '1',
  `note` varchar(1000) COLLATE utf8_bin DEFAULT NULL,
  `gestione` tinyint(1) NOT NULL DEFAULT '0',
  `responsabile` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `colore` varchar(7) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `Pernottamenti`
--

INSERT INTO `Pernottamenti` (`id`, `nome`, `tel`, `giorno_inizio`, `durata`, `posti`, `note`, `gestione`, `responsabile`, `colore`) VALUES
(3, 'gestore', '222222222', 4, 7, 2, 'niente da dire', 1, NULL, '0'),
(12, 'primo', '22222', 2, 5, 1, '', 0, 'sara', '1'),
(13, 'secondo', '22222', 3, 5, 1, '', 0, 'sara', '2'),
(15, 'terzo', '22222', 3, 5, 1, '', 0, 'sara', '3'),
(16, 'quarto', '22222', 1, 5, 1, '', 0, 'sara', '4'),
(17, 'quinto', '22222', 2, 5, 1, '', 0, 'sara', '5'),
(18, 'sesto', '22222', 4, 1, 1, '', 0, 'sara', '6'),
(19, 'settimo', '22222', 2, 5, 1, '', 0, 'sara', '7'),
(20, 'ottavo', '22222', 2, 5, 1, '', 0, 'sara', '8');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Colori`
--
ALTER TABLE `Colori`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Pernottamenti`
--
ALTER TABLE `Pernottamenti`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Pernottamenti`
--
ALTER TABLE `Pernottamenti`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
