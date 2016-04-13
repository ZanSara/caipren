-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 03, 2016 at 03:35 PM
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
-- Table structure for table `Pernottamenti`
--

DROP TABLE IF EXISTS `Pernottamenti`;
CREATE TABLE `Pernottamenti` (
  `id` int(5) NOT NULL,
  `nome` varchar(100) COLLATE utf8_bin NOT NULL,
  `tel` varchar(15) COLLATE utf8_bin NOT NULL,
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

INSERT INTO `Pernottamenti` (`id`, `nome`, `tel`, `stagione`, `giorno_inizio`, `durata`, `posti`, `note`, `gestione`, `responsabile`, `colore`) VALUES
(3, 'Zanzottera', '222222222', '2016', 156, 7, 1, 'niente da dire', 1, 'gestore', '0'),
(4, 'cliente1', '222222222', '2016', 157, 2, 2, '', 0, 'io', '1'),
(42, 'cliente2', '3333333333', '2016', 156, 3, 1, '', 0, 'io', '2'),
(44, 'gestore2', '1111', '2016', 167, 7, 1, '', 1, '', '5'),
(48, 'ciauuuuu', '6346', '2016', 156, 2, 3, '', 0, 'sara', '9'),
(49, 'Icy', '2356457643', '2016', 164, 4, 3, '', 0, 'sara', '11'),
(50, 'Picco de''Paperis', '12345678', '2016', 167, 2, 4, 'nessuna nota', 0, 'pippo', '0'),
(51, 'xxx', '11111', '2016', 169, 2, 2, '', 0, 'vv', '1'),
(52, 'zz', '11', '2016', 170, 2, 2, '', 0, 'ss', '2'),
(53, 'dd', '22', '2016', 167, 2, 3, '', 0, 'sss', '3'),
(54, 'sara', '4444', '2016', 186, 4, 2, '', 0, 'io', '4'),
(57, 'sdfgadfg', '356444', '2016', 153, 2, 4, '', 0, 'tu', '7'),
(58, 'cliente popup', '56233', '2016', 157, 1, 1, '', 0, 'io', '1'),
(59, 'cliente popup', '56233', '2016', 157, 1, 1, '', 0, 'io', '2'),
(60, 'cliente popup', '56233', '2016', 157, 1, 1, '', 0, 'io', '3'),
(62, 'cliente popup', '56233', '2016', 157, 1, 1, '', 0, 'io', '5'),
(63, 'cliente popup', '5623642', '2016', 155, 1, 1, '', 0, 'io', '6'),
(64, 'cliente popup', '5623642', '2016', 155, 1, 1, '', 0, 'io', '7'),
(65, 'cliente evidente', '34523523', '2016', 155, 1, 1, '', 0, 'io', '8'),
(66, 'cliente evidente', '34523523', '2016', 155, 1, 1, '', 0, 'io', '9'),
(67, 'cliente evidente', '34523523', '2016', 155, 1, 1, '', 0, 'io', '10'),
(68, 'cliente evidente', '34523523', '2016', 155, 1, 1, '', 0, 'io', '11'),
(69, 'cliente evidente', '34523523', '2016', 155, 1, 1, '', 0, 'io', '0'),
(70, 'cliente evidente', '34523523', '2016', 155, 1, 1, '', 0, 'io', '1'),
(71, 'cliente evidente', '34523523', '2016', 155, 1, 1, '', 0, 'io', '2'),
(72, 'cliente evidente', '34523523', '2016', 155, 1, 1, '', 0, 'io', '3'),
(73, 'cliente evidente', '34523523', '2016', 155, 1, 1, '', 0, 'io', '4'),
(74, 'cliente evidente', '34523523', '2016', 155, 1, 1, '', 0, 'io', '5'),
(78, 'cliente errore', '623424', '2016', 155, 1, 2, '', 0, 'io', '9'),
(79, 'cliente1', '573453', '2016', 155, 1, 1, '', 0, 'io', '10'),
(80, 'cliente1', '573453', '2016', 155, 1, 1, '', 0, 'io', '11'),
(81, 'cliente avviso', '6234254', '2016', 156, 1, 1, '', 0, 'io', '0'),
(82, 'cliente avviso', '6234254', '2016', 156, 1, 1, '', 0, 'io', '1'),
(83, 'cliente avviso', '6234254', '2016', 156, 1, 1, '', 0, 'io', '2'),
(84, 'cliente avviso', '6234254', '2016', 156, 1, 1, '', 0, 'io', '3'),
(85, 'cliente avviso', '6234254', '2016', 156, 1, 1, '', 0, 'io', '4'),
(86, 'cliente nuovo', '43121', '2016', 158, 3, 2, '', 0, 'io', '5');

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
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
