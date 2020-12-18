-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 18 dec 2020 om 09:28
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
-- Database: `nerdygadgets`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `privateorder`
--

DROP TABLE IF EXISTS `privateorder`;
CREATE TABLE `privateorder` (
  `orderID` int(11) NOT NULL,
  `OrderDate` date DEFAULT NULL,
  `ExpectedDeliveryDate` date DEFAULT NULL,
  `Comment` text DEFAULT NULL,
  `LastEditedBy` int(11) DEFAULT NULL,
  `LastEditWhen` datetime DEFAULT NULL,
  `DiscountName` varchar(8) DEFAULT NULL,
  `TotalPrice` decimal(6,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `privateorder`
--

INSERT INTO `privateorder` (`orderID`, `OrderDate`, `ExpectedDeliveryDate`, `Comment`, `LastEditedBy`, `LastEditWhen`, `DiscountName`, `TotalPrice`) VALUES
(1, '2017-12-20', '0000-00-00', NULL, NULL, '2017-12-20 00:00:00', '0.000', NULL),
(2, '2017-12-20', '0000-00-00', NULL, NULL, '2017-12-20 00:00:00', '0.000', NULL),
(3, '2020-12-17', '2020-12-18', NULL, NULL, '2020-12-17 17:10:46', '0.000', NULL),
(4, '2020-12-17', '2020-12-18', NULL, NULL, '2020-12-17 17:11:09', '0.000', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `privateorderlines`
--

DROP TABLE IF EXISTS `privateorderlines`;
CREATE TABLE `privateorderlines` (
  `OrderLineID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `StockItemID` int(11) DEFAULT NULL,
  `Description` varchar(100) DEFAULT NULL,
  `PackageTypeID` int(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `SellPrice` decimal(6,2) DEFAULT NULL,
  `TaxRate` decimal(6,3) DEFAULT NULL,
  `PickedQuantity` int(11) DEFAULT NULL,
  `PickingCompletedWhen` datetime DEFAULT NULL,
  `LastEditedBy` int(11) DEFAULT NULL,
  `LastEditedWhen` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `privateorderlines`
--

INSERT INTO `privateorderlines` (`OrderLineID`, `OrderID`, `StockItemID`, `Description`, `PackageTypeID`, `Quantity`, `SellPrice`, `TaxRate`, `PickedQuantity`, `PickingCompletedWhen`, `LastEditedBy`, `LastEditedWhen`) VALUES
(1, 3, 138, '0', NULL, NULL, '5.00', '15.000', 1, NULL, NULL, '2020-12-17 17:10:46'),
(2, 4, 138, '0', NULL, NULL, '5.00', '15.000', 1, NULL, NULL, '2020-12-17 17:11:09');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `privateorder`
--
ALTER TABLE `privateorder`
  ADD PRIMARY KEY (`orderID`);

--
-- Indexen voor tabel `privateorderlines`
--
ALTER TABLE `privateorderlines`
  ADD PRIMARY KEY (`OrderLineID`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `privateorder`
--
ALTER TABLE `privateorder`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `privateorderlines`
--
ALTER TABLE `privateorderlines`
  MODIFY `OrderLineID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
