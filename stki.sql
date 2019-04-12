-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 04, 2019 at 11:27 AM
-- Server version: 5.7.22-0ubuntu0.17.10.1
-- PHP Version: 7.1.17-0ubuntu0.17.10.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stki`
--

-- --------------------------------------------------------

--
-- Table structure for table `algoritma`
--

CREATE TABLE `algoritma` (
  `id_algoritma` int(11) NOT NULL,
  `nama_algoritma` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `algoritma`
--

INSERT INTO `algoritma` (`id_algoritma`, `nama_algoritma`) VALUES
(1, 'Tala');

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `id` int(11) NOT NULL,
  `document` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `document`
--

INSERT INTO `document` (`id`, `document`) VALUES
(1, 'Cokcokc'),
(2, 'Cokcokc'),
(3, 'Cokcokc'),
(4, 'Cokcokc'),
(5, 'Cokcokc'),
(6, 'Cokcokc');

-- --------------------------------------------------------

--
-- Table structure for table `document_kata`
--

CREATE TABLE `document_kata` (
  `id_document` int(11) NOT NULL,
  `id_kata` int(11) NOT NULL,
  `frekuensi` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kata`
--

CREATE TABLE `kata` (
  `id` int(11) NOT NULL,
  `kata` text NOT NULL,
  `kata_dasar_1` text,
  `kata_dasar_2` text,
  `waktu_1` text,
  `waktu_2` text,
  `status_1` tinyint(1) DEFAULT '0',
  `status_2` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `algoritma`
--
ALTER TABLE `algoritma`
  ADD PRIMARY KEY (`id_algoritma`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_kata`
--
ALTER TABLE `document_kata`
  ADD KEY `document` (`id_document`),
  ADD KEY `kata` (`id_kata`);

--
-- Indexes for table `kata`
--
ALTER TABLE `kata`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `algoritma`
--
ALTER TABLE `algoritma`
  MODIFY `id_algoritma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `kata`
--
ALTER TABLE `kata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `document_kata`
--
ALTER TABLE `document_kata`
  ADD CONSTRAINT `document` FOREIGN KEY (`id_document`) REFERENCES `document` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kata` FOREIGN KEY (`id_kata`) REFERENCES `kata` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;