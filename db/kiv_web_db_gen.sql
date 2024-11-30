-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 12, 2024 at 03:45 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kiv_web_db`
--
DROP DATABASE IF EXISTS `kiv_web_db`;

CREATE DATABASE `kiv_web_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

USE `kiv_web_db`;

-- --------------------------------------------------------

--
-- Table structure for table `PERMISSIONS`
--

CREATE TABLE `PERMISSIONS` (
  `perm_id` int(11) NOT NULL AUTO_INCREMENT,
  `perm_name` varchar(255) NOT NULL,
  `perm_weight` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`perm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `USER`
--

CREATE TABLE `USER` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(60) NOT NULL,
  `user_email` varchar(60) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `perm_id` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`),
  KEY `fk_USER_PERMISSIONS_idx` (`perm_id`),
  CONSTRAINT `fk_USER_PERMISSIONS` FOREIGN KEY (`perm_id`) REFERENCES `PERMISSIONS` (`perm_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `SESSIONS`
--

CREATE TABLE `SESSIONS` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expires_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`session_id`),
  UNIQUE KEY `session_id_UNIQUE` (`session_id`),
  KEY `fk_SESSIONS_USER_idx` (`user_id`),
  CONSTRAINT `fk_SESSIONS_USER` FOREIGN KEY (`user_id`) REFERENCES `USER` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Insert initial data into `PERMISSIONS` table
--

INSERT INTO `PERMISSIONS` (`perm_id`, `perm_name`, `perm_weight`) VALUES
(1, 'Reader', 1),
(2, 'Reviewer', 2),
(3, 'Admin', 3),
(4, 'SuperAdmin', 4);

--
-- Insert initial data into `USER` table
--

INSERT INTO `USER` (`user_id`, `user_name`, `user_email`, `user_password`, `perm_id`) VALUES
(1, 'Admin', 'admin@email.com', '$2y$10$9U.AGquRQf4XtZCScdvt8.U9eYuVmtxHgeHFdQ6tumvsi5Sty1/Qm', 1);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
