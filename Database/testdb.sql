-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 17 dec 2020 om 12:12
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
-- Database: `testdb`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `account`
--

CREATE TABLE `account` (
  `AccountID` int(11) NOT NULL,
  `Email` varchar(45) NOT NULL,
  `Firstname` varchar(45) NOT NULL,
  `Infix` varchar(45) DEFAULT NULL,
  `Surname` varchar(45) NOT NULL,
  `Gender` varchar(3) NOT NULL,
  `Street` varchar(100) NOT NULL,
  `StreetNumber` varchar(10) NOT NULL,
  `PostalCode` varchar(7) NOT NULL,
  `City` varchar(100) NOT NULL,
  `Password` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `account`
--
DELIMITER $$
CREATE TRIGGER `insertAddress` BEFORE INSERT ON `account` FOR EACH ROW BEGIN
	IF NEW.PostalCode NOT LIKE '[1-9][0-9][0-9][0-9][A-Z][A-Z]' OR NEW.PostalCode NOT LIKE'[1-9][0-9][0-9][0-9] [A-Z][A-Z]' THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'postalCode field is not valid';
	END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `discount`
--

CREATE TABLE `discount` (
  `discountID` int(11) NOT NULL,
  `discountName` varchar(8) NOT NULL,
  `discountQuantity` decimal(3,2) NOT NULL,
  `discountValid` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `privatecustomers`
--

CREATE TABLE `privatecustomers` (
  `OrderID` int(11) NOT NULL,
  `Email` varchar(45) NOT NULL,
  `Firstname` varchar(45) NOT NULL,
  `Infix` varchar(45) DEFAULT NULL,
  `Surname` varchar(45) NOT NULL,
  `Gender` varchar(3) NOT NULL,
  `Street` varchar(100) NOT NULL,
  `StreetNumber` varchar(10) NOT NULL,
  `PostalCode` varchar(6) NOT NULL,
  `City` varchar(100) NOT NULL,
  `AccountID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `privateorder`
--

CREATE TABLE `privateorder` (
  `orderID` int(11) NOT NULL,
  `OrderDate` date DEFAULT NULL,
  `ExpectedDeliveryDate` date DEFAULT NULL,
  `Comment` text DEFAULT NULL,
  `LastEditedBy` int(11) DEFAULT NULL,
  `LastEditWhen` datetime DEFAULT NULL,
  `Discount` decimal(3,3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `privateorderlines`
--

CREATE TABLE `privateorderlines` (
  `OrderLineID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `StockItemID` int(11) DEFAULT NULL,
  `Description` varchar(100) DEFAULT NULL,
  `PackageTypeID` int(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `UnitPrice` decimal(18,2) DEFAULT NULL,
  `TaxRate` decimal(18,3) DEFAULT NULL,
  `PickedQuantity` int(11) DEFAULT NULL,
  `PickingCompletedWhen` datetime DEFAULT NULL,
  `LastEditedBy` int(11) DEFAULT NULL,
  `LastEditedWhen` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`AccountID`),
  ADD UNIQUE KEY `Email_UNIQUE` (`Email`);

--
-- Indexen voor tabel `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`discountID`);

--
-- Indexen voor tabel `privatecustomers`
--
ALTER TABLE `privatecustomers`
  ADD PRIMARY KEY (`OrderID`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
