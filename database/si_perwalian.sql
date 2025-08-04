-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 04, 2025 at 11:25 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `si_perwalian`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_student_classes_procedure` ()   BEGIN
DECLARE done INT DEFAULT FALSE;
DECLARE class_id BIGINT;
DECLARE class_name VARCHAR(255);
DECLARE prog_degree ENUM('D3', 'D4');
DECLARE prog_id BIGINT;
DECLARE cur CURSOR FOR
SELECT sc.id, sc.class_name, p.degree, sc.program_id
FROM student_classes sc
JOIN programs p ON sc.program_id = p.id
WHERE sc.status = 'active';
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
OPEN cur;
class_loop: LOOP
FETCH cur INTO class_id, class_name, prog_degree, prog_id;
IF done THEN
LEAVE class_loop;
END IF;
SET @after_dash = SUBSTRING_INDEX(class_name, '-', -1);  
SET @tingkat = CAST(SUBSTRING(@after_dash, 1, CHAR_LENGTH(@after_dash) - 1) AS UNSIGNED); 
SET @huruf = RIGHT(@after_dash, 1); 
SET @kode = SUBSTRING_INDEX(class_name, '-', 1); 
SET @max_tingkat = IF(prog_degree = 'D3', 3, 4);
IF @tingkat < @max_tingkat THEN
UPDATE student_classes
SET class_name = CONCAT(@kode, '-', @tingkat + 1, @huruf)
WHERE id = class_id;
ELSE
UPDATE student_classes
SET status = 'graduated',
graduated_at = CURRENT_DATE()
WHERE id = class_id;
UPDATE students
SET status = 'graduated',
inactive_at = CURRENT_DATE()
WHERE student_class_id = class_id
AND status = 'active';
END IF;
END LOOP;
CLOSE cur;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE `achievements` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `class_name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_year` year NOT NULL,
  `achievement_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('siwali_cache_2e01e17467891f7c933dbaa00e1459d23db3fe4f', 'i:2;', 1752804016),
('siwali_cache_2e01e17467891f7c933dbaa00e1459d23db3fe4f:timer', 'i:1752804016;', 1752804016),
('siwali_cache_356a192b7913b04c54574d18c28d46e6395428ab', 'i:2;', 1752803426),
('siwali_cache_356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1752803426;', 1752803426),
('siwali_cache_77de68daecd823babbb58edb1c8e14d7106e83bb', 'i:1;', 1752803828),
('siwali_cache_77de68daecd823babbb58edb1c8e14d7106e83bb:timer', 'i:1752803828;', 1752803828),
('siwali_cache_da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:1;', 1752803734),
('siwali_cache_da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1752803734;', 1752803734),
('siwali_cache_pg_cached_tables', 'a:1:{i:0;s:26:\"powergrid_columns_in_users\";}', 2069663322),
('siwali_cache_powergrid_columns_in_users', 'a:6:{s:2:\"id\";s:15:\"bigint unsigned\";s:4:\"name\";s:12:\"varchar(255)\";s:5:\"email\";s:12:\"varchar(255)\";s:8:\"password\";s:12:\"varchar(255)\";s:10:\"created_at\";s:9:\"timestamp\";s:10:\"updated_at\";s:9:\"timestamp\";}', 1754314123);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gpa_cumulatives`
--

CREATE TABLE `gpa_cumulatives` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `cumulative_gpa` decimal(3,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gpa_cumulatives`
--

INSERT INTO `gpa_cumulatives` (`id`, `student_id`, `cumulative_gpa`, `created_at`, `updated_at`) VALUES
(1, 23, '3.33', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(2, 24, '2.67', '2025-07-17 18:52:48', '2025-07-17 18:52:48'),
(3, 25, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(4, 26, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(5, 27, '2.83', '2025-07-17 18:52:48', '2025-07-17 18:52:48'),
(6, 28, '3.50', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(7, 29, '2.83', '2025-07-17 18:52:48', '2025-07-17 18:52:48'),
(8, 30, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(9, 31, '2.50', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(10, 32, '3.17', '2025-07-17 18:52:48', '2025-07-17 18:52:48'),
(11, 33, '3.83', '2025-07-17 18:52:48', '2025-07-17 18:52:48'),
(12, 34, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(13, 35, '2.67', '2025-07-17 18:52:48', '2025-07-17 18:52:48'),
(14, 36, '3.50', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(15, 37, '3.17', '2025-07-17 18:52:48', '2025-07-17 18:52:48'),
(16, 38, '2.33', '2025-07-17 18:52:48', '2025-07-17 18:52:48'),
(17, 39, '3.50', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(18, 40, '2.50', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(19, 41, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(20, 42, '3.33', '2025-07-17 18:52:48', '2025-07-17 18:52:48'),
(21, 43, '2.67', '2025-07-17 18:52:48', '2025-07-17 18:52:48'),
(22, 44, '2.83', '2025-07-17 18:52:48', '2025-07-17 18:52:48');

-- --------------------------------------------------------

--
-- Table structure for table `gpa_semesters`
--

CREATE TABLE `gpa_semesters` (
  `id` bigint UNSIGNED NOT NULL,
  `gpa_cumulative_id` bigint UNSIGNED NOT NULL,
  `semester` int NOT NULL,
  `semester_gpa` decimal(3,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gpa_semesters`
--

INSERT INTO `gpa_semesters` (`id`, `gpa_cumulative_id`, `semester`, `semester_gpa`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(2, 1, 2, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(3, 1, 3, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(4, 1, 4, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(5, 1, 5, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(6, 1, 6, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(7, 2, 1, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(8, 2, 2, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(9, 2, 3, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(10, 2, 4, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(11, 2, 5, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(12, 2, 6, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(13, 3, 1, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(14, 3, 2, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(15, 3, 3, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(16, 3, 4, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(17, 3, 5, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(18, 3, 6, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(19, 4, 1, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(20, 4, 2, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(21, 4, 3, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(22, 4, 4, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(23, 4, 5, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(24, 4, 6, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(25, 5, 1, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(26, 5, 2, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(27, 5, 3, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(28, 5, 4, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(29, 5, 5, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(30, 5, 6, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(31, 6, 1, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(32, 6, 2, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(33, 6, 3, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(34, 6, 4, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(35, 6, 5, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(36, 6, 6, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(37, 7, 1, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(38, 7, 2, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(39, 7, 3, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(40, 7, 4, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(41, 7, 5, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(42, 7, 6, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(43, 8, 1, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(44, 8, 2, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(45, 8, 3, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(46, 8, 4, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(47, 8, 5, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(48, 8, 6, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(49, 9, 1, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(50, 9, 2, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(51, 9, 3, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(52, 9, 4, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(53, 9, 5, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(54, 9, 6, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(55, 10, 1, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(56, 10, 2, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(57, 10, 3, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(58, 10, 4, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(59, 10, 5, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(60, 10, 6, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(61, 11, 1, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(62, 11, 2, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(63, 11, 3, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(64, 11, 4, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(65, 11, 5, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(66, 11, 6, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(67, 12, 1, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(68, 12, 2, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(69, 12, 3, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(70, 12, 4, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(71, 12, 5, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(72, 12, 6, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(73, 13, 1, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(74, 13, 2, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(75, 13, 3, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(76, 13, 4, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(77, 13, 5, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(78, 13, 6, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(79, 14, 1, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(80, 14, 2, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(81, 14, 3, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:10'),
(82, 14, 4, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(83, 14, 5, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(84, 14, 6, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(85, 15, 1, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(86, 15, 2, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(87, 15, 3, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(88, 15, 4, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(89, 15, 5, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(90, 15, 6, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(91, 16, 1, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(92, 16, 2, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(93, 16, 3, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(94, 16, 4, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(95, 16, 5, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(96, 16, 6, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(97, 17, 1, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(98, 17, 2, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(99, 17, 3, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(100, 17, 4, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(101, 17, 5, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(102, 17, 6, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(103, 18, 1, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(104, 18, 2, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(105, 18, 3, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(106, 18, 4, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(107, 18, 5, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(108, 18, 6, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(109, 19, 1, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(110, 19, 2, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(111, 19, 3, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(112, 19, 4, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(113, 19, 5, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(114, 19, 6, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(115, 20, 1, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(116, 20, 2, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(117, 20, 3, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(118, 20, 4, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(119, 20, 5, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(120, 20, 6, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(121, 21, 1, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(122, 21, 2, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(123, 21, 3, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(124, 21, 4, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(125, 21, 5, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(126, 21, 6, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(127, 22, 1, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(128, 22, 2, '3.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(129, 22, 3, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(130, 22, 4, '2.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(131, 22, 5, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11'),
(132, 22, 6, '4.00', '2025-07-17 18:52:48', '2025-07-17 18:53:11');

-- --------------------------------------------------------

--
-- Table structure for table `gpa_stats`
--

CREATE TABLE `gpa_stats` (
  `id` bigint UNSIGNED NOT NULL,
  `student_class_id` bigint UNSIGNED NOT NULL,
  `max_semester` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gpa_stats`
--

INSERT INTO `gpa_stats` (`id`, `student_class_id`, `max_semester`, `created_at`, `updated_at`) VALUES
(1, 5, 6, '2025-07-17 18:52:48', '2025-07-17 18:52:48');

-- --------------------------------------------------------

--
-- Table structure for table `gpa_stat_semesters`
--

CREATE TABLE `gpa_stat_semesters` (
  `id` bigint UNSIGNED NOT NULL,
  `gpa_stat_id` bigint UNSIGNED NOT NULL,
  `semester` int NOT NULL,
  `avg` double DEFAULT NULL,
  `min` double DEFAULT NULL,
  `max` double DEFAULT NULL,
  `below_3` int DEFAULT NULL,
  `below_3_percent` double DEFAULT NULL,
  `above_equal_3` int DEFAULT NULL,
  `above_equal_3_percent` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gpa_stat_semesters`
--

INSERT INTO `gpa_stat_semesters` (`id`, `gpa_stat_id`, `semester`, `avg`, `min`, `max`, `below_3`, `below_3_percent`, `above_equal_3`, `above_equal_3_percent`, `created_at`, `updated_at`) VALUES
(7, 1, 1, 2.86, 2, 4, 7, 31.82, 15, 68.18, '2025-07-17 18:53:11', '2025-07-17 18:53:11'),
(8, 1, 2, 3.14, 2, 4, 7, 31.82, 15, 68.18, '2025-07-17 18:53:11', '2025-07-17 18:53:11'),
(9, 1, 3, 2.95, 2, 4, 9, 40.91, 13, 59.09, '2025-07-17 18:53:11', '2025-07-17 18:53:11'),
(10, 1, 4, 2.77, 2, 4, 10, 45.45, 12, 54.55, '2025-07-17 18:53:11', '2025-07-17 18:53:11'),
(11, 1, 5, 3.18, 2, 4, 5, 22.73, 17, 77.27, '2025-07-17 18:53:11', '2025-07-17 18:53:11'),
(12, 1, 6, 3.14, 2, 4, 4, 18.18, 18, 81.82, '2025-07-17 18:53:11', '2025-07-17 18:53:11');

-- --------------------------------------------------------

--
-- Table structure for table `guidances`
--

CREATE TABLE `guidances` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `class_name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_year` year NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `problem` text COLLATE utf8mb4_unicode_ci,
  `solution` text COLLATE utf8mb4_unicode_ci,
  `problem_date` date DEFAULT NULL,
  `solution_date` date DEFAULT NULL,
  `is_validated` int DEFAULT NULL,
  `validation_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guidances`
--

INSERT INTO `guidances` (`id`, `student_id`, `class_name`, `entry_year`, `created_by`, `problem`, `solution`, `problem_date`, `solution_date`, `is_validated`, `validation_note`, `created_at`, `updated_at`) VALUES
(1, 42, 'TI-3A', 2022, 49, 'Saya punya masalah dengann teman', 'Temui saya setelah perkuliahan', '2025-07-18', '2025-07-18', 1, NULL, '2025-07-17 19:00:03', '2025-07-17 19:00:32'),
(2, 42, 'TI-3A', 2022, 2, 'Berkelahi dengan temannnn', 'Temui saya', '2025-07-18', '2025-07-18', 1, NULL, '2025-07-17 19:01:07', '2025-07-17 19:02:00');

-- --------------------------------------------------------

--
-- Table structure for table `khs`
--

CREATE TABLE `khs` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `semester` int NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `khs`
--

INSERT INTO `khs` (`id`, `student_id`, `semester`, `file`, `created_at`, `updated_at`) VALUES
(23, 23, 4, 'khs/khs_220102001_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(24, 24, 4, 'khs/khs_220202002_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(25, 25, 4, 'khs/khs_220302003_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(26, 26, 4, 'khs/khs_220202004_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(27, 27, 4, 'khs/khs_220302005_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(28, 28, 4, 'khs/khs_220102007_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(29, 29, 4, 'khs/khs_220302008_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(30, 30, 4, 'khs/khs_220102010_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(31, 31, 4, 'khs/khs_220102011_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(32, 32, 4, 'khs/khs_220302012_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(33, 35, 4, 'khs/khs_220202015_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(34, 36, 4, 'khs/khs_220302016_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(35, 33, 4, 'khs/khs_220102013_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(36, 34, 4, 'khs/khs_220102014_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(37, 37, 4, 'khs/khs_220302017_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(38, 38, 4, 'khs/khs_220102018_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(39, 39, 4, 'khs/khs_220302019_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(40, 40, 4, 'khs/khs_220302020_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(41, 41, 4, 'khs/khs_220302021_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(42, 42, 4, 'khs/khs_220302022_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(43, 44, 4, 'khs/khs_220202024_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31'),
(44, 43, 4, 'khs/khs_220102023_4.pdf', '2025-07-17 18:49:31', '2025-07-17 18:49:31');

-- --------------------------------------------------------

--
-- Table structure for table `krs`
--

CREATE TABLE `krs` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `krs_format_id` bigint UNSIGNED NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `krs`
--

INSERT INTO `krs` (`id`, `student_id`, `krs_format_id`, `file`, `created_at`, `updated_at`) VALUES
(1, 42, 1, 'krs/lzK53wfCWZvkO1cuWWFmJe5Df0WHD6a6gEl5Dy93.pdf', '2025-07-17 18:59:20', '2025-07-17 18:59:20'),
(2, 42, 2, 'krs/WTa7KiX80DgUIPXf1TIJBUs33PL7WqH7XjE06PeT.pdf', '2025-07-17 18:59:33', '2025-07-17 18:59:33');

-- --------------------------------------------------------

--
-- Table structure for table `krs_formats`
--

CREATE TABLE `krs_formats` (
  `id` bigint UNSIGNED NOT NULL,
  `program_id` bigint UNSIGNED NOT NULL,
  `semester` int NOT NULL,
  `academic_year` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `krs_formats`
--

INSERT INTO `krs_formats` (`id`, `program_id`, `semester`, `academic_year`, `file`, `created_at`, `updated_at`) VALUES
(1, 1, 4, '2023/2024', 'krs_format/06RJzOWbwKVpusOcJUgTH9qrivHVp14UZ0vVEV88.pdf', '2025-07-17 18:26:19', '2025-07-17 18:26:19'),
(2, 1, 3, '2023/2024', 'krs_format/rwvx7kFi339E8IXnIeLhMfJzkG6RGb38NxDH0pdI.pdf', '2025-07-17 18:49:59', '2025-07-17 18:49:59');

-- --------------------------------------------------------

--
-- Table structure for table `lecturers`
--

CREATE TABLE `lecturers` (
  `id` bigint UNSIGNED NOT NULL,
  `nidn` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` char(18) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lecturer_phone_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lecturer_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `lecturer_signature` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lecturers`
--

INSERT INTO `lecturers` (`id`, `nidn`, `nip`, `lecturer_phone_number`, `lecturer_address`, `lecturer_signature`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '1192813218', '112342112342112342', '08976589032', 'Madiun', 'signatures/jhKuY3hADf5H1vRwB1bs6Ulpjqg2vpkNVmzLJiJy.png', 2, '2025-07-14 18:08:16', '2025-07-17 18:54:37'),
(2, '1192827890', '112343112343112343', '08976589032', 'Madiun', NULL, 4, '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(3, '1192838532', '112344112344112344', '08976589032', 'Madiun', NULL, 6, '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(4, '1192845678', '112344112344112344', '08976589032', 'Madiun', NULL, 7, '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(5, '1192858763', '112346112346112346', '08976589032', 'Madiun', NULL, 5, '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(6, '1192862345', '112347112347112347', '08976589032', 'Madiun', 'signatures/Nbrc8vOrcRH1obvrssF1Rg5wpgZTEUTsosOpno8y.png', 3, '2025-07-14 18:08:16', '2025-07-17 18:56:10'),
(7, '1192878765', '112348112348112348', '08976589032', 'Madiun', NULL, 8, '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(8, '1192484831', '11234403539474083', '+14694263056', '1567 O\'Conner Stravenue\nNew Rickie, PA 39387', NULL, 9, '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(9, '1192190582', '11234859626882699', '(213) 324-1309', '6393 Syble Cliffs\nJustinechester, NC 65867-9285', NULL, 10, '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(10, '1192442756', '11234282280560793', '+1 (283) 632-8376', '654 Swift Crossroad Apt. 236\nAltenwerthview, MD 80530-9291', NULL, 11, '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(11, '1192852870', '11234602714079779', '(586) 999-3592', '9293 Francisco Club Apt. 275\nNew Grant, NY 73584-8009', NULL, 12, '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(12, '1192899066', '11234576631312650', '986-619-4979', '99928 Alvera Prairie\nSouth Alec, PA 35543', NULL, 13, '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(13, '1192074040', '11234760816354035', '717-655-4275', '170 Donny Pines\nWilkinsonview, DE 14052', NULL, 14, '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(14, '1192557092', '11234733046493005', '+1-818-252-3773', '89814 Viviane Parks\nEast Nolanburgh, ND 47536', NULL, 15, '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(15, '1192280099', '11234565981276567', '1-680-404-1664', '166 Adalberto Springs Apt. 983\nLake Chaddfort, NV 17921-6758', NULL, 16, '2025-07-14 18:08:17', '2025-07-14 18:08:17'),
(16, '1192322244', '11234849606374463', '+1-469-876-3562', '43275 Lebsack Prairie\nLake Dawn, ND 85331', NULL, 17, '2025-07-14 18:08:17', '2025-07-14 18:08:17'),
(17, '1192593671', '11234297845202076', '+1-858-620-0836', '921 Jazmyne Harbor Suite 062\nDickiton, ND 84545-8326', NULL, 18, '2025-07-14 18:08:17', '2025-07-14 18:08:17'),
(18, '1192869214', '11234956926939125', '802-481-9315', '73821 Spencer Path\nAidanhaven, CO 87399-0930', NULL, 19, '2025-07-14 18:08:17', '2025-07-14 18:08:17'),
(19, '1192023416', '11234593827497549', '+1-320-561-2702', '778 Ed Common Suite 084\nPort Ava, KS 87896-2778', NULL, 20, '2025-07-14 18:08:17', '2025-07-14 18:08:17'),
(20, '1192591170', '11234168144688713', '802.751.7267', '3425 Will Avenue Apt. 278\nMarciaberg, NV 12509', NULL, 21, '2025-07-14 18:08:17', '2025-07-14 18:08:17'),
(21, '1192945462', '11234455088052060', '1-680-850-9601', '3747 Wunsch Loaf Suite 209\nPort Louburgh, IL 14888-1846', NULL, 22, '2025-07-14 18:08:17', '2025-07-14 18:08:17'),
(22, '1192984149', '11234025872250991', '541.674.3090', '7679 August Plains Apt. 719\nJameyshire, LA 72051', NULL, 23, '2025-07-14 18:08:17', '2025-07-14 18:08:17'),
(23, '1192806246', '11234322692464758', '+1-682-448-8470', '46268 Alf Flats Apt. 174\nWest Titus, VT 02634', NULL, 24, '2025-07-14 18:08:17', '2025-07-14 18:08:17'),
(24, '1192735265', '11234263179961206', '+14436256701', '857 Marisol Mills Apt. 306\nPort Altheabury, IL 91793', NULL, 25, '2025-07-14 18:08:17', '2025-07-14 18:08:17'),
(25, '1192970446', '11234701028202685', '(630) 884-1388', '903 Wilkinson Road Apt. 239\nLake Joey, DE 24850-9860', NULL, 26, '2025-07-14 18:08:17', '2025-07-14 18:08:17'),
(26, '1192908887', '11234966615313744', '678.944.4330', '2538 Dock Dam\nBettiemouth, NC 75451-6764', NULL, 27, '2025-07-14 18:08:17', '2025-07-14 18:08:17'),
(27, '1192532062', '11234982746265332', '1-351-729-7025', '914 Damion Well Suite 859\nMayerview, LA 46665', NULL, 28, '2025-07-14 18:08:17', '2025-07-14 18:08:17');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_08_16_043105_create_lecturers_table', 1),
(5, '2024_08_16_043134_create_programs_table', 1),
(6, '2024_08_16_043204_create_student_classes_table', 1),
(7, '2024_08_16_043235_create_students_table', 1),
(8, '2024_08_16_043305_create_gpa_cumulatives_table', 1),
(9, '2024_08_16_043312_create_gpa_semesters_table', 1),
(10, '2024_08_16_043823_create_reports_table', 1),
(11, '2024_08_30_032312_create_achievements_table', 1),
(12, '2024_08_30_032339_create_scholarships_table', 1),
(13, '2024_08_30_032349_create_guidances_table', 1),
(14, '2024_08_30_032357_create_warnings_table', 1),
(15, '2024_08_30_032812_create_tuition_arrears_table', 1),
(16, '2024_08_30_032857_create_student_resignations_table', 1),
(17, '2025_00_13_161326_create_krs_formats_table', 1),
(18, '2025_01_14_052710_create_khs_table', 1),
(19, '2025_01_14_052716_create_krs_table', 1),
(20, '2025_05_29_022502_create_permission_tables', 1),
(21, '2025_06_26_082639_create_gpa_stats_table', 1),
(22, '2025_06_26_090622_create_gpa_stat_semesters_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 6),
(3, 'App\\Models\\User', 7),
(4, 'App\\Models\\User', 8),
(2, 'App\\Models\\User', 9),
(2, 'App\\Models\\User', 10),
(2, 'App\\Models\\User', 11),
(2, 'App\\Models\\User', 12),
(2, 'App\\Models\\User', 13),
(2, 'App\\Models\\User', 14),
(2, 'App\\Models\\User', 15),
(2, 'App\\Models\\User', 16),
(2, 'App\\Models\\User', 17),
(2, 'App\\Models\\User', 18),
(2, 'App\\Models\\User', 19),
(2, 'App\\Models\\User', 20),
(2, 'App\\Models\\User', 21),
(2, 'App\\Models\\User', 22),
(2, 'App\\Models\\User', 23),
(2, 'App\\Models\\User', 24),
(2, 'App\\Models\\User', 25),
(2, 'App\\Models\\User', 26),
(2, 'App\\Models\\User', 27),
(2, 'App\\Models\\User', 28),
(3, 'App\\Models\\User', 29),
(5, 'App\\Models\\User', 30),
(5, 'App\\Models\\User', 31),
(5, 'App\\Models\\User', 32),
(5, 'App\\Models\\User', 33),
(5, 'App\\Models\\User', 34),
(5, 'App\\Models\\User', 35),
(5, 'App\\Models\\User', 36),
(5, 'App\\Models\\User', 37),
(5, 'App\\Models\\User', 38),
(5, 'App\\Models\\User', 39),
(5, 'App\\Models\\User', 40),
(5, 'App\\Models\\User', 41),
(5, 'App\\Models\\User', 42),
(5, 'App\\Models\\User', 43),
(5, 'App\\Models\\User', 44),
(5, 'App\\Models\\User', 45),
(5, 'App\\Models\\User', 46),
(5, 'App\\Models\\User', 47),
(5, 'App\\Models\\User', 48),
(5, 'App\\Models\\User', 49),
(5, 'App\\Models\\User', 50),
(5, 'App\\Models\\User', 51);

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` bigint UNSIGNED NOT NULL,
  `program_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `degree` enum('D3','D4') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `head_of_program_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `program_name`, `degree`, `created_at`, `updated_at`, `head_of_program_id`) VALUES
(1, 'Teknik Informatika', 'D3', '2025-07-14 18:08:17', '2025-07-14 18:08:17', 6),
(2, 'Rekayasa Keamanan Siber', 'D4', '2025-07-14 18:08:17', '2025-07-14 18:08:17', 2),
(3, 'Akuntansi Lembaga Keuangan Syariah', 'D4', '2025-07-14 18:08:17', '2025-07-14 18:08:17', 5),
(4, 'Teknologi Rekayasa Multimedia', 'D4', '2025-07-14 18:08:17', '2025-07-14 18:08:17', 3),
(5, 'Teknologi Rekayasa Perangkat Lunak', 'D4', '2025-07-14 18:08:17', '2025-07-14 18:08:17', 4),
(6, 'Prodi Baru', 'D4', '2025-07-14 18:08:17', '2025-07-17 18:48:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint UNSIGNED NOT NULL,
  `student_class_id` bigint UNSIGNED DEFAULT NULL,
  `academic_advisor_id` bigint UNSIGNED DEFAULT NULL,
  `class_name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_year` year NOT NULL,
  `academic_advisor_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `academic_advisor_decree` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` int NOT NULL,
  `academic_year` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','submitted','approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `submitted_at` date DEFAULT NULL,
  `approved_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `student_class_id`, `academic_advisor_id`, `class_name`, `entry_year`, `academic_advisor_name`, `academic_advisor_decree`, `semester`, `academic_year`, `status`, `submitted_at`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 2, 8, 'TI-1B', 2024, 'Kylee Auer', '743/PL.77/HK.84.26/2036', 1, '2024/2025', 'approved', '2025-07-15', '2025-07-15', '2025-07-14 19:03:07', '2025-07-14 19:05:57'),
(2, 2, 8, 'TI-1B', 2024, 'Kylee Auer', '743/PL.77/HK.84.26/2036', 2, '2024/2025', 'approved', '2025-07-15', '2025-07-15', '2025-07-14 19:03:12', '2025-07-14 19:06:03'),
(3, 3, 9, 'TI-2A', 2023, 'Gwen Gottlieb', '894/PL.52/HK.91.96/2065', 1, '2024/2025', 'approved', '2025-07-15', '2025-07-15', '2025-07-14 19:04:20', '2025-07-14 19:06:11'),
(4, 3, 9, 'TI-2A', 2023, 'Gwen Gottlieb', '894/PL.52/HK.91.96/2065', 2, '2024/2025', 'submitted', '2025-07-15', NULL, '2025-07-14 19:04:25', '2025-07-14 19:04:57'),
(5, 3, 9, 'TI-2A', 2023, 'Gwen Gottlieb', '894/PL.52/HK.91.96/2065', 3, '2024/2025', 'draft', NULL, NULL, '2025-07-14 19:04:34', '2025-07-14 19:04:34'),
(6, 3, 9, 'TI-2A', 2023, 'Gwen Gottlieb', '894/PL.52/HK.91.96/2065', 4, '2024/2025', 'draft', NULL, NULL, '2025-07-14 19:04:41', '2025-07-14 19:04:41'),
(7, 1, 1, 'TI-1A', 2024, 'Lutfi Syafirullah, S.T., M.Kom.', '352/PL.77/HK.45.69/2000', 1, '2024/2025', 'draft', NULL, NULL, '2025-07-14 19:05:22', '2025-07-14 19:05:22'),
(8, 1, 1, 'TI-1A', 2024, 'Lutfi Syafirullah, S.T., M.Kom.', '352/PL.77/HK.45.69/2000', 2, '2024/2025', 'draft', NULL, NULL, '2025-07-14 19:05:29', '2025-07-14 19:05:29'),
(9, 5, 11, 'TI-3A', 2022, 'Deven Runte', '789/PL.19/HK.35.66/2041', 1, '2024/2025', 'approved', '2025-07-15', '2025-07-15', '2025-07-14 19:19:54', '2025-07-14 19:21:13'),
(10, 5, 11, 'TI-3A', 2022, 'Deven Runte', '789/PL.19/HK.35.66/2041', 2, '2024/2025', 'approved', '2025-07-15', '2025-07-15', '2025-07-14 19:20:08', '2025-07-14 19:21:18'),
(11, 5, 11, 'TI-3A', 2022, 'Deven Runte', '789/PL.19/HK.35.66/2041', 3, '2024/2025', 'approved', '2025-07-15', '2025-07-15', '2025-07-14 19:20:15', '2025-07-14 19:21:23'),
(12, 5, 11, 'TI-3A', 2022, 'Deven Runte', '789/PL.19/HK.35.66/2041', 4, '2024/2025', 'submitted', '2025-07-15', NULL, '2025-07-14 19:20:22', '2025-07-14 19:20:53'),
(13, 5, 11, 'TI-3A', 2022, 'Deven Runte', '789/PL.19/HK.35.66/2041', 5, '2024/2025', 'submitted', '2025-07-15', NULL, '2025-07-14 19:20:30', '2025-07-14 19:21:00'),
(14, 5, 1, 'TI-3A', 2022, 'Lutfi Syafirullah, S.T., M.Kom.', '789/PL.19/HK.35.66/2041', 6, '2024/2025', 'approved', '2025-07-18', '2025-07-18', '2025-07-17 18:51:53', '2025-07-17 18:55:56');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-07-14 18:08:13', '2025-07-14 18:08:13'),
(2, 'dosenWali', 'web', '2025-07-14 18:08:13', '2025-07-14 18:08:13'),
(3, 'kaprodi', 'web', '2025-07-14 18:08:13', '2025-07-14 18:08:13'),
(4, 'jurusan', 'web', '2025-07-14 18:08:13', '2025-07-14 18:08:13'),
(5, 'mahasiswa', 'web', '2025-07-14 18:08:13', '2025-07-14 18:08:13');

-- --------------------------------------------------------

--
-- Table structure for table `scholarships`
--

CREATE TABLE `scholarships` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `class_name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_year` year NOT NULL,
  `scholarship_type` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('sCQfI8YI76BI08sa5mvlMlCwe9eRx4xv7sw9Q8F6', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUmh3WjlLbnlDeHo0SExVaWFFaTZ1MVJaZTRqZWxWTENSeGtPdDZIbSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4ta3JzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1754305811);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `student_class_id` bigint UNSIGNED DEFAULT NULL,
  `student_phone_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nim` char(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','graduated','dropout','resign','academic_leave') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `inactive_at` date DEFAULT NULL,
  `active_at_semester` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `student_class_id`, `student_phone_number`, `nim`, `student_address`, `status`, `inactive_at`, `active_at_semester`, `created_at`, `updated_at`) VALUES
(23, 30, 5, '8123456', '220102001', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(24, 31, 5, '8123458', '220202002', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(25, 32, 5, '8123457', '220302003', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(26, 33, 5, '8123459', '220202004', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(27, 34, 5, '8123460', '220302005', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(28, 35, 5, '8123461', '220102007', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(29, 36, 5, '8123462', '220302008', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(30, 37, 5, '8123463', '220102010', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(31, 38, 5, '8123464', '220102011', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(32, 39, 5, '8123465', '220302012', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(33, 40, 5, '8123466', '220102013', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(34, 41, 5, '8123467', '220102014', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(35, 42, 5, '8123468', '220202015', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(36, 43, 5, '8123469', '220302016', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(37, 44, 5, '8123470', '220302017', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(38, 45, 5, '8123471', '220102018', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(39, 46, 5, '8123472', '220302019', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(40, 47, 5, '8123473', '220302020', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(41, 48, 5, '8123474', '220302021', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(42, 49, 5, '8123475', '220302022', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(43, 50, 5, '8123476', '220102023', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35'),
(44, 51, 5, '8123477', '220202024', 'Cilacap', 'active', NULL, 1, '2025-07-17 18:48:35', '2025-07-17 18:48:35');

-- --------------------------------------------------------

--
-- Table structure for table `student_classes`
--

CREATE TABLE `student_classes` (
  `id` bigint UNSIGNED NOT NULL,
  `program_id` bigint UNSIGNED DEFAULT NULL,
  `academic_advisor_id` bigint UNSIGNED DEFAULT NULL,
  `academic_advisor_decree` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_year` year NOT NULL,
  `status` enum('active','graduated') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `graduated_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_classes`
--

INSERT INTO `student_classes` (`id`, `program_id`, `academic_advisor_id`, `academic_advisor_decree`, `class_name`, `entry_year`, `status`, `graduated_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '352/PL.77/HK.45.69/2000', 'TI-1A', 2024, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:08:17'),
(2, 1, 8, '743/PL.77/HK.84.26/2036', 'TI-1B', 2024, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:14:58'),
(3, 1, 9, '894/PL.52/HK.91.96/2065', 'TI-2A', 2023, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:15:03'),
(4, 1, 10, '717/PL.90/HK.19.79/2030', 'TI-2B', 2023, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:15:11'),
(5, 1, 1, '789/PL.19/HK.35.66/2041', 'TI-3A', 2022, 'active', NULL, '2025-07-14 18:08:17', '2025-07-17 18:50:51'),
(6, 1, 12, '095/PL.09/HK.73.48/2096', 'TI-3B', 2022, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:15:24'),
(7, 2, 13, '750/PL.08/HK.82.60/2026', 'RKS-1A', 2024, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:15:34'),
(8, 2, 14, '156/PL.81/HK.16.14/2011', 'RKS-1B', 2024, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:15:41'),
(9, 2, 15, '807/PL.95/HK.41.34/2083', 'RKS-2A', 2023, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:17:45'),
(10, 2, 16, '906/PL.38/HK.14.09/2058', 'RKS-2B', 2023, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:17:38'),
(11, 2, 17, '198/PL.35/HK.56.53/2023', 'RKS-3A', 2022, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:17:51'),
(12, 2, 18, '725/PL.09/HK.76.24/2039', 'RKS-3B', 2022, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:17:58'),
(15, 3, 21, '404/PL.43/HK.75.96/2034', 'ALKS-1A', 2024, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:21:08'),
(16, 3, 22, '880/PL.90/HK.10.81/2070', 'ALKS-1B', 2024, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:21:14'),
(17, 3, 23, '366/PL.63/HK.53.73/2031', 'ALKS-2A', 2023, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:21:26'),
(18, 3, 24, '741/PL.61/HK.92.17/2082', 'ALKS-2B', 2023, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:21:21'),
(19, 3, 25, '337/PL.03/HK.33.44/2096', 'ALKS-3A', 2022, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:21:32'),
(20, 3, 26, '011/PL.65/HK.21.36/2085', 'ALKS-3B', 2022, 'active', NULL, '2025-07-14 18:08:17', '2025-07-14 18:21:37'),
(22, 4, 19, 'Consequatur ratione', 'TRM-2A', 2023, 'active', NULL, '2025-07-14 18:19:43', '2025-07-14 18:19:43'),
(23, 5, 20, 'Aspernatur non commo', 'TRPL-1A', 2024, 'active', NULL, '2025-07-14 18:20:20', '2025-07-14 18:20:20'),
(24, 6, 27, 'Explicabo Quis even', 'PB-1A', 2024, 'active', NULL, '2025-07-14 18:20:53', '2025-07-14 18:20:53');

-- --------------------------------------------------------

--
-- Table structure for table `student_resignations`
--

CREATE TABLE `student_resignations` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `resignation_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decree_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tuition_arrears`
--

CREATE TABLE `tuition_arrears` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `class_name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_year` year NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `semester` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$12$wFDrcl2BaRVSX16JzQQUv.Df1nDCORT.xIpycYhlxePhzjonrJqRm', '2025-07-14 18:08:13', '2025-07-14 18:08:13'),
(2, 'Lutfi Syafirullah, S.T., M.Kom.', 'lutfi@gmail.com', '$2y$12$6/4HcaYPY3V7bc7vsZ2KluUWWljRrkM0DKY6DAA79xSd9KHslDkAu', '2025-07-14 18:08:14', '2025-07-14 18:08:14'),
(3, 'Cahya Vikasari, S.T., M.Eng.', 'cahya@gmail.com', '$2y$12$8.Z1NN4ac1GpibcgewSDy.T70afzLwCTglC.j/JPjXqfX3FML3gOi', '2025-07-14 18:08:14', '2025-07-14 18:08:14'),
(4, 'Abdul Rohman Supriyono, S.T., M.Kom.', 'abdul@gmail.com', '$2y$12$7xmlTHky4PsaTTzD1sWJCeNdvBjsyj2Ysfn4CczrsKaHM7hqwFnpy', '2025-07-14 18:08:14', '2025-07-14 18:08:14'),
(5, 'Faizin Firdaus', 'faizin@gmail.com', '$2y$12$uK7JtxgknexO7ArQTg2ovunAuZUW7d3qTjryBYnQM3ZC0jKinWpoO', '2025-07-14 18:08:15', '2025-07-14 18:08:15'),
(6, 'Nur Wachid Adi Prasetya, S.Kom., M.Kom.', 'wachid@gmail.com', '$2y$12$VB.AJgVe8egjlzWVQ9swh.8tuLMUaEskzZIywSkLVrIFFOreJDYgW', '2025-07-14 18:08:15', '2025-07-14 18:08:15'),
(7, 'Prih Diantono Abda`u, S.Kom., M.Kom.', 'abdau@gmail.com', '$2y$12$w4o400yd3dnd5TbxoKoHyeFANG4F6cfNWLj.Q60Y3uBrsFJoWOV66', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(8, 'Dwi Novia Prasetyanti, S.Kom., M.Cs.', 'novi@gmail.com', '$2y$12$2aK9F2TRPWZmrWiXgYyFzeXl7IgxQ6XR8ctzegRcrQyusQqV.FYwq', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(9, 'Kylee Auer', 'lou54@example.org', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(10, 'Gwen Gottlieb', 'rrolfson@example.org', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(11, 'Rosanna Turner I', 'jacklyn.mcglynn@example.org', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(12, 'Deven Runte', 'okeefe.vivian@example.net', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(13, 'Julio Upton', 'allen.roob@example.net', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(14, 'Henri Ledner MD', 'monroe.mayert@example.com', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(15, 'Aimee Hand III', 'slang@example.com', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(16, 'Dock Goldner', 'gutkowski.mervin@example.com', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(17, 'Donald Klocko', 'bayer.brandon@example.com', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(18, 'Arielle Gerlach', 'karelle.koch@example.com', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(19, 'Antwon Gorczany', 'bklocko@example.com', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(20, 'Grover Wyman I', 'celestine.wolff@example.org', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(21, 'Miss Marilyne Dach I', 'ykoelpin@example.org', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(22, 'Gertrude Kautzer DDS', 'carlotta.reichert@example.org', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(23, 'Prof. Jared O\'Keefe', 'gislason.kris@example.net', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(24, 'Clementine Bernhard', 'ehansen@example.com', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(25, 'Judy Grimes', 'estel.kling@example.com', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(26, 'Carroll Barrows', 'marianna83@example.com', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(27, 'Dr. Lavern Murphy', 'brigitte61@example.com', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(28, 'Jaclyn Huel', 'flossie86@example.com', '$2y$12$chPl4hruDtWCfGd0JVQtOe/2l9XQckbigPG8BKMUqpGMsHYfgm2cK', '2025-07-14 18:08:16', '2025-07-14 18:08:16'),
(29, 'Alfina', 'pin@gmail.com', '$2y$12$fecPGZ2GvOWO23F77a4heupZFiDC4OE9.MxsW41DRaKQikxsYKkyi', '2025-07-17 18:23:51', '2025-07-17 18:23:51'),
(30, 'Adisa Laras Pertiwi', 'adisa@gmail.com', '$2y$12$0aBxiyno10JgQvZvkKeRvuyvEJ1YZJX7a6ihXx9Ic.aLYlPdOM8kC', '2025-07-17 18:24:21', '2025-07-17 18:24:21'),
(31, 'Arif Nur Azhar', 'arif@gmail.com', '$2y$12$EqERSYJYxGYCgc4ALsXAZuqNNFHpmjchopjoHjF.eiThK.Rx9ZIxW', '2025-07-17 18:24:21', '2025-07-17 18:24:21'),
(32, 'Aulia Nabila Zahwah', 'aulia@gmail.com', '$2y$12$Cr30R8fqbc6t.uxA06hJQutFtZK7omAoTmn2Put4PGNmTja5Z7zJi', '2025-07-17 18:24:22', '2025-07-17 18:24:22'),
(33, 'Azzahra Rikha Nur Aini', 'azzahra@gmail.com', '$2y$12$tyM3OyT100PVITNkHsLtHe6d9VM/Dtmvw3en0SGrZwhqCqM7Whttm', '2025-07-17 18:24:22', '2025-07-17 18:24:22'),
(34, 'Daniel Fajar Inzaghi', 'daniel@gmail.com', '$2y$12$aEE6cW4hBQxHFGXFCqAn4eXJpvNshUtgfionMgnXB7ekK5nE.FcJi', '2025-07-17 18:24:22', '2025-07-17 18:24:22'),
(35, 'Fardan Nurhidayat', 'fardan@gmail.com', '$2y$12$BHKDGKJxXUToSCIqSQGaquGMyxDDjPKX33GG3AwAB0FcNRiZyT0fi', '2025-07-17 18:24:22', '2025-07-17 18:24:22'),
(36, 'Fariani Vinita Tamonob', 'fariani@gmail.com', '$2y$12$BqApFQYjA1MnXm/ahWYAne/7JBJN0O7znUJ6EIGH6TxsQV8mQOrUK', '2025-07-17 18:24:23', '2025-07-17 18:24:23'),
(37, 'Gita Listyani Putri', 'gita@gmail.com', '$2y$12$yXITJH48bfR/9179jzuUUeVK.1oqibOl1ULNet.Ejg4YCNi1.QK0u', '2025-07-17 18:24:23', '2025-07-17 18:24:23'),
(38, 'Hanif Maulana Trangginas', 'hanif@gmail.com', '$2y$12$2zrU9HeGgDMhVyg7L.iaEehbKK4p8oZLeJwwfCzBjZXFEsdILgENq', '2025-07-17 18:24:23', '2025-07-17 18:24:23'),
(39, 'Josefh Immanuel Cristian Rombot', 'josefh@gmail.com', '$2y$12$imabBy1QmOGmVJNlosUjLuxxOU00Kg4xTxvNF8n1U7yv.Cu.HTQRG', '2025-07-17 18:24:23', '2025-07-17 18:24:23'),
(40, 'Muhammad Akbar Reza Saputro', 'muhammadak@gmail.com', '$2y$12$HRiVwpVmpBB2MWsLw2UfXeHE..165QGSeSip2X9ToY4xfXK0BMstW', '2025-07-17 18:24:24', '2025-07-17 18:24:24'),
(41, 'Muhammad Fikri Dzaki Sugianto', 'muhammadfik@gmail.com', '$2y$12$qUjMBuvK5PtkcPZvZY7wWutCFunMZERDw9p5eAQzSZBb0O2Yx1lnu', '2025-07-17 18:24:24', '2025-07-17 18:24:24'),
(42, 'Maria Ine Febrianti', 'maria@gmail.com', '$2y$12$UaGSVTMCW.MWbQ.B/heJ7uIRhmhD1.d8vypBmoo1NYmYun5UqmcC.', '2025-07-17 18:24:24', '2025-07-17 18:24:24'),
(43, 'Meisya Anggraeni', 'meisya@gmail.com', '$2y$12$GTPMxjTynxkXTnXboSypVO3Q99ibU0nrQzbvpfYaaYRZqi9SNUL5a', '2025-07-17 18:24:24', '2025-07-17 18:24:24'),
(44, 'Nafis Watsiq Amrullah', 'nafis@gmail.com', '$2y$12$.D/2S0WH6E7E78dheLdEs.J0dMTWuzwn742QA8eAUQxtOON.MZb3K', '2025-07-17 18:24:25', '2025-07-17 18:24:25'),
(45, 'Nanda Gusniar Pratama', 'nanda@gmail.com', '$2y$12$XBYblRwiO9MUP3.YlJaaB.LWE4bD7CvdC27yaVR8dm3sqyxxkN.7e', '2025-07-17 18:24:25', '2025-07-17 18:24:25'),
(46, 'Puput Eradibah', 'puput@gmail.com', '$2y$12$7Ic.vGVtLCq5zRlhx8KIBuEXbUFVTDxdeMbTsuNFCLL47KVoHPN7y', '2025-07-17 18:24:25', '2025-07-17 18:24:25'),
(47, 'Ramli Rahmansyah', 'ramli@gmail.com', '$2y$12$jLDkHITlKmykL3wwnuU41uLSUappUoMsSekVTmr.mxpiD76FPJBBS', '2025-07-17 18:24:26', '2025-07-17 18:24:26'),
(48, 'Ratna Winingsih', 'ratna@gmail.com', '$2y$12$5cojAM96vpXsaIK3sLyxWOqSuX./0aEVgaPZ2qzJ36wlzGHapErHy', '2025-07-17 18:24:26', '2025-07-17 18:24:26'),
(49, 'Rayhan Afrizal Fajri', 'rayhan@gmail.com', '$2y$12$4/xmnPaNHKLeGvITeCSCVOTi81nPWLrTkmFRxARhvqsJBY9hBCmgK', '2025-07-17 18:24:26', '2025-07-17 18:24:26'),
(50, 'Yefta Charrand Kusuma Putra', 'yefta@gmail.com', '$2y$12$ldARQtVXM9DJm6lMmMKmvOADkxKvJXj4utHqEygLKC/0QxROaNmGW', '2025-07-17 18:24:26', '2025-07-17 18:24:26'),
(51, 'Sinthia Dwi Yolandasari', 'sinthia@gmail.com', '$2y$12$fQ9cHwhVrLHHgAoELN2In.zTCuImdE8lRsa2v6XvNLl/cZ109Xt6C', '2025-07-17 18:24:27', '2025-07-17 18:24:27');

-- --------------------------------------------------------

--
-- Table structure for table `warnings`
--

CREATE TABLE `warnings` (
  `id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `class_name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entry_year` year NOT NULL,
  `warning_type` enum('SP 1','SP 2','SP 3') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `achievements_student_id_foreign` (`student_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `gpa_cumulatives`
--
ALTER TABLE `gpa_cumulatives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gpa_cumulatives_student_id_foreign` (`student_id`);

--
-- Indexes for table `gpa_semesters`
--
ALTER TABLE `gpa_semesters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gpa_semesters_gpa_cumulative_id_foreign` (`gpa_cumulative_id`);

--
-- Indexes for table `gpa_stats`
--
ALTER TABLE `gpa_stats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gpa_stats_student_class_id_foreign` (`student_class_id`);

--
-- Indexes for table `gpa_stat_semesters`
--
ALTER TABLE `gpa_stat_semesters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gpa_stat_semesters_gpa_stat_id_foreign` (`gpa_stat_id`);

--
-- Indexes for table `guidances`
--
ALTER TABLE `guidances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guidances_student_id_foreign` (`student_id`),
  ADD KEY `guidances_created_by_foreign` (`created_by`);

--
-- Indexes for table `khs`
--
ALTER TABLE `khs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `khs_student_id_foreign` (`student_id`);

--
-- Indexes for table `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `krs_student_id_foreign` (`student_id`),
  ADD KEY `krs_krs_format_id_foreign` (`krs_format_id`);

--
-- Indexes for table `krs_formats`
--
ALTER TABLE `krs_formats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `krs_formats_program_id_foreign` (`program_id`);

--
-- Indexes for table `lecturers`
--
ALTER TABLE `lecturers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lecturers_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `programs_head_of_program_id_foreign` (`head_of_program_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_student_class_id_foreign` (`student_class_id`),
  ADD KEY `reports_academic_advisor_id_foreign` (`academic_advisor_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `scholarships`
--
ALTER TABLE `scholarships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scholarships_student_id_foreign` (`student_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_nim_unique` (`nim`),
  ADD KEY `students_user_id_foreign` (`user_id`),
  ADD KEY `students_student_class_id_foreign` (`student_class_id`);

--
-- Indexes for table `student_classes`
--
ALTER TABLE `student_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_classes_program_id_foreign` (`program_id`),
  ADD KEY `student_classes_academic_advisor_id_foreign` (`academic_advisor_id`);

--
-- Indexes for table `student_resignations`
--
ALTER TABLE `student_resignations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_resignations_student_id_foreign` (`student_id`);

--
-- Indexes for table `tuition_arrears`
--
ALTER TABLE `tuition_arrears`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tuition_arrears_student_id_foreign` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `warnings`
--
ALTER TABLE `warnings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warnings_student_id_foreign` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gpa_cumulatives`
--
ALTER TABLE `gpa_cumulatives`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `gpa_semesters`
--
ALTER TABLE `gpa_semesters`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `gpa_stats`
--
ALTER TABLE `gpa_stats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gpa_stat_semesters`
--
ALTER TABLE `gpa_stat_semesters`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `guidances`
--
ALTER TABLE `guidances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `khs`
--
ALTER TABLE `khs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `krs`
--
ALTER TABLE `krs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `krs_formats`
--
ALTER TABLE `krs_formats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lecturers`
--
ALTER TABLE `lecturers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `scholarships`
--
ALTER TABLE `scholarships`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `student_classes`
--
ALTER TABLE `student_classes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `student_resignations`
--
ALTER TABLE `student_resignations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tuition_arrears`
--
ALTER TABLE `tuition_arrears`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `warnings`
--
ALTER TABLE `warnings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `achievements`
--
ALTER TABLE `achievements`
  ADD CONSTRAINT `achievements_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gpa_cumulatives`
--
ALTER TABLE `gpa_cumulatives`
  ADD CONSTRAINT `gpa_cumulatives_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gpa_semesters`
--
ALTER TABLE `gpa_semesters`
  ADD CONSTRAINT `gpa_semesters_gpa_cumulative_id_foreign` FOREIGN KEY (`gpa_cumulative_id`) REFERENCES `gpa_cumulatives` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gpa_stats`
--
ALTER TABLE `gpa_stats`
  ADD CONSTRAINT `gpa_stats_student_class_id_foreign` FOREIGN KEY (`student_class_id`) REFERENCES `student_classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gpa_stat_semesters`
--
ALTER TABLE `gpa_stat_semesters`
  ADD CONSTRAINT `gpa_stat_semesters_gpa_stat_id_foreign` FOREIGN KEY (`gpa_stat_id`) REFERENCES `gpa_stats` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `guidances`
--
ALTER TABLE `guidances`
  ADD CONSTRAINT `guidances_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `guidances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `khs`
--
ALTER TABLE `khs`
  ADD CONSTRAINT `khs_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `krs`
--
ALTER TABLE `krs`
  ADD CONSTRAINT `krs_krs_format_id_foreign` FOREIGN KEY (`krs_format_id`) REFERENCES `krs_formats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `krs_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `krs_formats`
--
ALTER TABLE `krs_formats`
  ADD CONSTRAINT `krs_formats_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lecturers`
--
ALTER TABLE `lecturers`
  ADD CONSTRAINT `lecturers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programs`
--
ALTER TABLE `programs`
  ADD CONSTRAINT `programs_head_of_program_id_foreign` FOREIGN KEY (`head_of_program_id`) REFERENCES `lecturers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_academic_advisor_id_foreign` FOREIGN KEY (`academic_advisor_id`) REFERENCES `lecturers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reports_student_class_id_foreign` FOREIGN KEY (`student_class_id`) REFERENCES `student_classes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `scholarships`
--
ALTER TABLE `scholarships`
  ADD CONSTRAINT `scholarships_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_student_class_id_foreign` FOREIGN KEY (`student_class_id`) REFERENCES `student_classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_classes`
--
ALTER TABLE `student_classes`
  ADD CONSTRAINT `student_classes_academic_advisor_id_foreign` FOREIGN KEY (`academic_advisor_id`) REFERENCES `lecturers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `student_classes_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_resignations`
--
ALTER TABLE `student_resignations`
  ADD CONSTRAINT `student_resignations_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tuition_arrears`
--
ALTER TABLE `tuition_arrears`
  ADD CONSTRAINT `tuition_arrears_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `warnings`
--
ALTER TABLE `warnings`
  ADD CONSTRAINT `warnings_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `update_student_classes_event` ON SCHEDULE EVERY 1 YEAR STARTS '2025-08-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
CALL update_student_classes_procedure();
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
