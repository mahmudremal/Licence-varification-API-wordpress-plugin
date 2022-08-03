-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 05, 2022 at 03:54 PM
-- Server version: 10.3.35-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `futurewo_licenses`
--

-- --------------------------------------------------------

--
-- Table structure for table `licenses`
--




CREATE TABLE IF NOT EXISTS licenses (
SRL bigint(20) UNSIGNED NOT NULL,
ID text NOT NULL,
API text NOT NULL,
product text NOT NULL,
counted bigint(255) NOT NULL DEFAULT 0,
fullname text DEFAULT NULL,
email text DEFAULT NULL,
social text NOT NULL,
lc_type text NOT NULL DEFAULT '0',
label text NOT NULL DEFAULT '0',
lc_status text NOT NULL DEFAULT '1',
comments mediumtext NOT NULL DEFAULT '\'\'',
createdon timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='This tables saved licenses which is varified by API';

--
-- Dumping data for table `licenses`
--

INSERT INTO `licenses` (`SRL`, `ID`, `API`, `product`, `counted`, `fullname`, `email`, `lc_type`, `label`, `comments`, `createdon`) VALUES
(1, '53e1270541f5-647d56531df2451bb783688768173855', 'status', 'fiverr.chrome', 1272, 'Remal Mahmud', 'mahmudremal@yahoo.com', '0', '0', '\'\'', '2022-07-03 15:35:05'),
(2, '53e1570541f5-647d56531df2751bb733688768173855', 'status', 'fiverr.chrome', 2, 'Dipto Sayman Abir', NULL, '0', '0', '\'\'', '2022-07-03 15:35:05'),
(3, '53e2370541f5-647d56531df2751bb7324658768173855', 'status', 'fiverr.chrome', 581, 'Jasim Uddin', NULL, '0', '0', '\'\'', '2022-07-03 15:35:05'),
(4, '57e2075541f5-647d56531df2751bb2394658817673855', 'status', 'fiverr.chrome', 0, 'Ayan Ahmed Junaid', NULL, '0', '0', '\'\'', '2022-07-03 15:35:05'),
(5, '57e2075541f5-756d56531df2517bb2394325461673855', 'status', 'fiverr.chrome', 581, 'MD Fahim', NULL, '0', '0', '\'\'', '2022-07-03 15:35:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `licenses`
--
ALTER TABLE `licenses`
  ADD UNIQUE KEY `SRL` (`SRL`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `licenses`
--
ALTER TABLE `licenses`
  MODIFY `SRL` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
