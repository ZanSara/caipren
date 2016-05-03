-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 03, 2016 at 02:27 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.6.19

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

CREATE TABLE `Colori` (
  `ID` int(11) NOT NULL,
  `colore` varchar(7) NOT NULL,
  `last` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Colori`
--

INSERT INTO `Colori` (`ID`, `colore`, `last`) VALUES
(0, '#0061D1', 37),
(1, '#00A823', 38),
(2, '#DA38FF', 27),
(3, '#FFD02E', 28),
(4, '#B0B0B0', 29),
(5, '#A84B00', 30),
(6, '#7E38FF', 31),
(7, '#00D1C5', 32),
(8, '#CFCFCF', 33),
(9, '#FF0D0A', 34),
(10, '#FF7AA6', 35),
(11, '#FF781A', 36);

-- --------------------------------------------------------

--
-- Table structure for table `Pernottamenti`
--

CREATE TABLE `Pernottamenti` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE utf8_bin NOT NULL,
  `tel` varchar(50) COLLATE utf8_bin NOT NULL,
  `provincia` varchar(20) COLLATE utf8_bin NOT NULL,
  `stagione` varchar(4) COLLATE utf8_bin NOT NULL,
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

INSERT INTO `Pernottamenti` (`id`, `nome`, `tel`, `provincia`, `stagione`, `giorno_inizio`, `durata`, `posti`, `note`, `gestione`, `responsabile`, `colore`) VALUES
(12, 'gestoreA', '00000000', 'mb', '2016', 151, 4, 0, '', 1, 'io', '2'),
(16, 'gestoreRottoA', '3333333', 'MBPNM', '2016', 155, 2, 0, '', 1, 'io', '6'),
(18, 'cliente1', '234123412', 'mi', '2016', 156, 2, 6, '', 0, 'io', '8'),
(19, 'cliente2', '464345234', 'va', '2016', 152, 3, 2, '', 0, 'io ancora', '9'),
(20, 'cliente3', '64567453', 'BG', '2016', 157, 3, 1, 'E'' finalmente riuscito a funzionare, evvai!', 1, 'tu', '10'),
(21, 'cliente4', '754564354', 'BS', '2016', 154, 2, 10, '', 0, 'lui', '11'),
(22, 'cliente11', '54736463', 'LC', '2016', 152, 1, 1, '', 0, 'io', '0'),
(23, 'cliente11', '54736463', 'LC', '2016', 152, 1, 1, '', 1, 'io', '1');

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
