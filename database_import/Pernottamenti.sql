-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 30, 2016 at 06:44 PM
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
(3, 'gestore', '222222222', 156, 7, 1, 'niente da dire', 1, 'gestore', '0'),
(4, 'cliente1', '222222222', 157, 2, 2, '', 0, 'io', '1'),
(42, 'cliente2', '3333333333', 156, 3, 1, '', 0, 'io', '2'),
(44, 'gestore2', '1111', 167, 7, 1, '', 1, '', '5'),
(45, 'nuovo3', '333333', 154, 2, 1, '', 0, 'io', '6'),
(48, 'ciauuuuu', '6346', 156, 2, 3, '', 0, 'sara', '9'),
(49, 'Icy', '2356457643', 164, 4, 3, '', 0, 'sara', '11'),
(50, 'Picco de''Paperis', '12345678', 167, 2, 4, 'nessuna nota', 0, 'pippo', '0'),
(51, 'xxx', '11111', 169, 2, 2, '', 0, 'vv', '1'),
(52, 'zz', '11', 170, 2, 2, '', 0, 'ss', '2'),
(53, 'dd', '22', 167, 2, 3, '', 0, 'sss', '3'),
(54, 'sara', '4444', 186, 4, 2, '', 0, 'io', '4'),
(57, 'sdfgadfg', '356444', 153, 2, 3, '', 0, 'tu', '7');

--
-- Indexes for dumped tables
--

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
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
