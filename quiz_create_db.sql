-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2025 at 05:49 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quiz`
--
CREATE DATABASE IF NOT EXISTS `quiz` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci;
USE `quiz`;

-- --------------------------------------------------------

--
-- Table structure for table `tbanswers`
--

CREATE TABLE `tbanswers` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Text` text COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `Boolean` tinyint(1) DEFAULT NULL,
  `Json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Json`)),
  `Correct` tinyint(1) NOT NULL,
  `IdQuestion` int(10) UNSIGNED NOT NULL,
  `imagePath` varchar(255) COLLATE utf8mb4_czech_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbanswertype`
--

CREATE TABLE `tbanswertype` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Type` varchar(100) COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Dumping data for table `tbanswertype`
--

INSERT INTO `tbanswertype` (`Id`, `Type`) VALUES
(4, 'Picture choice'),
(5, 'Order answers'),
(6, 'Sentence completion'),
(7, 'Picture assembly'),
(8, 'Categories fill'),
(9, 'Match answers');

-- --------------------------------------------------------

--
-- Table structure for table `tbquestions`
--

CREATE TABLE `tbquestions` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Text` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `IdQuiz` int(10) UNSIGNED NOT NULL,
  `IdAnswerType` int(11) UNSIGNED NOT NULL,
  `Position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbquiz`
--

CREATE TABLE `tbquiz` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Name` varchar(100) COLLATE utf8mb4_czech_ci NOT NULL,
  `Date` datetime NOT NULL,
  `IdUser` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbuseranswers`
--

CREATE TABLE `tbuseranswers` (
  `Id` int(11) UNSIGNED NOT NULL,
  `imagePath` varchar(255) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `orderAnswer` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`orderAnswer`)),
  `correct` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbuserquizprogress`
--

CREATE TABLE `tbuserquizprogress` (
  `IdUser` int(10) UNSIGNED NOT NULL,
  `IdQuiz` int(10) UNSIGNED NOT NULL,
  `IdQuestion` int(10) UNSIGNED NOT NULL,
  `IdAnswer` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbusers`
--

CREATE TABLE `tbusers` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Name` varchar(20) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `Surname` varchar(20) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `Username` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8mb4_czech_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbanswers`
--
ALTER TABLE `tbanswers`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `idx_IdQuestion` (`IdQuestion`);

--
-- Indexes for table `tbanswertype`
--
ALTER TABLE `tbanswertype`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tbquestions`
--
ALTER TABLE `tbquestions`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `IdQuiz` (`IdQuiz`),
  ADD KEY `IdAnswerType` (`IdAnswerType`);

--
-- Indexes for table `tbquiz`
--
ALTER TABLE `tbquiz`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `idx_UserId` (`IdUser`);

--
-- Indexes for table `tbuseranswers`
--
ALTER TABLE `tbuseranswers`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tbuserquizprogress`
--
ALTER TABLE `tbuserquizprogress`
  ADD PRIMARY KEY (`IdUser`,`IdQuiz`,`IdQuestion`,`IdAnswer`),
  ADD KEY `IdQuiz` (`IdQuiz`),
  ADD KEY `IdQuestion` (`IdQuestion`),
  ADD KEY `tbuserquizprogress_ibfk_4` (`IdAnswer`);

--
-- Indexes for table `tbusers`
--
ALTER TABLE `tbusers`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbanswers`
--
ALTER TABLE `tbanswers`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT for table `tbanswertype`
--
ALTER TABLE `tbanswertype`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbquestions`
--
ALTER TABLE `tbquestions`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `tbquiz`
--
ALTER TABLE `tbquiz`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tbuseranswers`
--
ALTER TABLE `tbuseranswers`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT for table `tbusers`
--
ALTER TABLE `tbusers`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbanswers`
--
ALTER TABLE `tbanswers`
  ADD CONSTRAINT `tbanswers_ibfk_1` FOREIGN KEY (`IdQuestion`) REFERENCES `tbquestions` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbquestions`
--
ALTER TABLE `tbquestions`
  ADD CONSTRAINT `tbquestions_ibfk_1` FOREIGN KEY (`IdQuiz`) REFERENCES `tbquiz` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbquestions_ibfk_2` FOREIGN KEY (`IdAnswerType`) REFERENCES `tbanswertype` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbquiz`
--
ALTER TABLE `tbquiz`
  ADD CONSTRAINT `tbquiz_ibfk_1` FOREIGN KEY (`IdUser`) REFERENCES `tbusers` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbuserquizprogress`
--
ALTER TABLE `tbuserquizprogress`
  ADD CONSTRAINT `tbuserquizprogress_ibfk_1` FOREIGN KEY (`IdQuiz`) REFERENCES `tbquiz` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbuserquizprogress_ibfk_2` FOREIGN KEY (`IdUser`) REFERENCES `tbusers` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbuserquizprogress_ibfk_3` FOREIGN KEY (`IdQuestion`) REFERENCES `tbquestions` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbuserquizprogress_ibfk_4` FOREIGN KEY (`IdAnswer`) REFERENCES `tbuseranswers` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
