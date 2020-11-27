-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 27 nov 2020 om 11:28
-- Serverversie: 10.4.14-MariaDB
-- PHP-versie: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nerdygadgetstest`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `account`
--

CREATE TABLE `account` (
  `accountnr` int(10) NOT NULL,
  `email` varchar(45) CHARACTER SET latin1 NOT NULL,
  `firstname` varchar(15) CHARACTER SET latin1 NOT NULL,
  `infix` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `surname` varchar(20) CHARACTER SET latin1 NOT NULL,
  `gender` varchar(3) NOT NULL,
  `street` varchar(25) CHARACTER SET latin1 NOT NULL,
  `streetnr` varchar(10) CHARACTER SET latin1 NOT NULL,
  `postalcode` varchar(6) CHARACTER SET latin1 NOT NULL,
  `city` varchar(20) CHARACTER SET latin1 NOT NULL,
  `password` varchar(45) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `account`
--

INSERT INTO `account` (`accountnr`, `email`, `firstname`, `infix`, `surname`, `gender`, `street`, `streetnr`, `postalcode`, `city`, `password`) VALUES
(2, 'jasperintvel@hotmail.nl', 'Jasper', 'in \'t', 'Veld', 'M', 'Populierenplein', '11', '8102JK', 'Raalte', 'test123'),
(3, 'jasperintvelddd@hotmail.nl', 'Jasper', 'in \'t', 'Veld', 'V', 'Populierenplein', '11', '8102JK', 'Raalte', 'test123'),
(4, 'jasperitveld@hotmail.nl', 'Jasper', 'in \'t', 'Veld', 'M', 'Populierenplein', '11', '8102JK', 'Raalte', 'ffff'),
(5, 'jasperintveld@hotma.nl', '1', '1', '1', 'M', '1', '1', '8102JK', '1', '1'),
(6, '', '', '', '', '', '', '', '', '', ''),
(7, '', '', '', '', '', '', '', '', '', ''),
(8, '', '', '', '', '', '', '', '', '', ''),
(9, '', '', '', '', '', '', '', '', '', ''),
(10, '', '', '', '', '', '', '', '', '', ''),
(11, '', '', '', '', '', '', '', '', '', ''),
(12, '', '', '', '', '', '', '', '', '', ''),
(13, '', '', '', '', '', '', '', '', '', ''),
(14, '', '', '', '', '', '', '', '', '', ''),
(15, '', '', '', '', '', '', '', '', '', ''),
(16, '', '', '', '', '', '', '', '', '', ''),
(17, '', '', '', '', '', '', '', '', '', ''),
(18, 'dd@ss.dd', '1', '1', '1', 'man', 'dd', '11', 'dd', 'dd', '1'),
(19, 'dd@ss.ddd', '1', '1', '1', 'man', 'dd', '11', 'dd', 'dd', '1'),
(20, 'jasperi@hotmail.nl', 'Jasper', 'in \'t', 'Veld', 'man', 'Populierenplein', '11', '8102JK', 'Raalte', 'd'),
(21, 'jaspeld@hotmail.nl', 'Jasper', 'dd', 'Veld', 'man', 'Populierenplein', '11', '8102JK', 'Raalte', 'dd'),
(22, 'jasperineldd@honl.nl', 'Jasper', 'in \'t', 'Veld', 'man', 'Populierenplein', '11', '8102JK', 'Raalte', 'dd'),
(23, 'dd@dd.d', 'Jasper', 'in \'t', 'Veld', 'man', 'dd', '11', 'dd', 'dd', 'ff'),
(24, 'd@ss.ddd', 'Jasper', 'in \'t', 'Veld', 'man', 'dd', '11', 'dd', 'dd', 'd'),
(25, 'jastveld@hotmail.nl', 'Jasper', 'in \'t', 'Veld', 'man', 'Populierenplein', '11', '8102JK', 'Raalte', 'dd'),
(26, 'jasperintveld@hotma.k', 'Jasper', 'in \'t', 'Veld', 'man', 'Populierenplein', '11', '8102JK', 'Raalte', 'd'),
(27, 'jasperintveld@hotmail.nl', 'Jasper', 'in \'t', 'Veld', 'man', 'Populierenplein', '11', '8102JK', 'Raalte', 'test');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`accountnr`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `account`
--
ALTER TABLE `account`
  MODIFY `accountnr` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
