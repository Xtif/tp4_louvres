-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2017 at 08:19 PM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tp4_louvres`
--

-- --------------------------------------------------------

--
-- Table structure for table `billet`
--

CREATE TABLE `billet` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `jour_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pays` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_naissance` date NOT NULL,
  `tarif_reduit` tinyint(1) NOT NULL,
  `prix_billet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `billet`
--

INSERT INTO `billet` (`id`, `reservation_id`, `jour_id`, `type`, `nom`, `prenom`, `pays`, `date_naissance`, `tarif_reduit`, `prix_billet`) VALUES
(28, 18, 18, 0, 'ezfzf', 'zfzefezf', 'Anguilla', '1986-01-16', 1, 5),
(29, 18, 18, 1, 'zefezf', 'zfzfzbdgfgbfgbb', 'Antigua-et-Barbuda', '1986-12-24', 1, 10),
(30, 19, 19, 1, 'treerg', 'egdfdfhg', 'Afghanistan', '1986-01-25', 1, 10),
(31, 19, 19, 1, 'hjkuykjyuk', 'ktykyk', 'Afghanistan', '1986-12-24', 1, 10),
(32, 19, 19, 1, 'bhrtr', 'brbzrt', 'Afghanistan', '1987-06-18', 1, 10),
(33, 20, 19, 1, 'rheh', 'erhr', 'Afghanistan', '1986-01-15', 0, 16),
(34, 20, 19, 1, 'rhrh', 'erh', 'Afghanistan', '1986-12-15', 1, 10),
(35, 20, 19, 0, 'eargaergerag', 'zergerg', 'Azerbaïdjan', '1986-01-16', 1, 5),
(36, 22, 21, 1, 'ege', 'ezgezrg', 'Angola', '1986-01-15', 1, 10),
(37, 22, 21, 1, 'ezg', 'ezgezrg', 'Australie', '1986-01-17', 1, 10),
(38, 23, 19, 1, 'tybo', 'tybo', 'Antarctique', '1986-01-23', 1, 10),
(39, 23, 19, 0, 'tjtyj', 'tyjtyj', 'Anguilla', '1986-01-16', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `jour`
--

CREATE TABLE `jour` (
  `id` int(11) NOT NULL,
  `jour` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `jour`
--

INSERT INTO `jour` (`id`, `jour`) VALUES
(18, '2017-11-20'),
(19, '2017-11-23'),
(22, '2017-11-24'),
(20, '2017-11-28'),
(21, '2017-12-05');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `jour_id` int(11) NOT NULL,
  `nom_reservation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenom_reservation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prix_total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `jour_id`, `nom_reservation`, `prenom_reservation`, `email`, `prix_total`) VALUES
(18, 18, 'FIACRE', 'Thibault', 'thibault.fiacre@gmail.com', 15),
(19, 19, 'Schembri', 'Aurélie', 'aurelie.schembri@sfr.fr', 30),
(20, 19, 'Schembri', 'Aurélie', 'aurelie.schembri@sfr.fr', 31),
(21, 20, 'FIACRE', 'Thibault', 'thibault.fiacre@gmail.com', 0),
(22, 21, 'FIACRE', 'Thibault', 'thibault.fiacre@gmail.com', 20),
(23, 19, 'Schembri', 'Aurélie', 'aurelie.schembri@sfr.fr', 15),
(24, 22, 'FIACRE', 'Thibault', 'thibault.fiacre@gmail.com', 0),
(25, 22, 'FIACRE', 'Thibault', 'thibault.fiacre@gmail.com', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `billet`
--
ALTER TABLE `billet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1F034AF6B83297E7` (`reservation_id`),
  ADD KEY `IDX_1F034AF6220C6AD0` (`jour_id`);

--
-- Indexes for table `jour`
--
ALTER TABLE `jour`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_DA17D9C5DA17D9C5` (`jour`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_42C84955220C6AD0` (`jour_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `billet`
--
ALTER TABLE `billet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `jour`
--
ALTER TABLE `jour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `billet`
--
ALTER TABLE `billet`
  ADD CONSTRAINT `FK_1F034AF6220C6AD0` FOREIGN KEY (`jour_id`) REFERENCES `jour` (`id`),
  ADD CONSTRAINT `FK_1F034AF6B83297E7` FOREIGN KEY (`reservation_id`) REFERENCES `reservation` (`id`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `FK_42C84955220C6AD0` FOREIGN KEY (`jour_id`) REFERENCES `jour` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
