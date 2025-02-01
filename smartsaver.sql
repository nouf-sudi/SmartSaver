-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2025 at 09:38 PM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12
create database smartsaver;
use smartsaver;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smartsaver`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `commitments`
--

CREATE TABLE `commitments` (
  `id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `commitments`
--

INSERT INTO `commitments` (`id`, `record_id`, `category`, `amount`) VALUES
(4, 8, 'food', '8000.00'),
(5, 8, 'housing', '1000.00'),
(6, 9, 'food', '50000.00'),
(7, 10, 'food', '5000.00'),
(8, 10, 'housing', '3000.00'),
(9, 7, 'food', '5000.00'),
(10, 7, 'housing', '2500.00'),
(11, 7, 'entertainment', '1500.00'),
(12, 11, 'food', '7000.00'),
(13, 11, 'home', '1000.00'),
(14, 11, 'chaild', '1000.00');

-- --------------------------------------------------------

--
-- Table structure for table `commitment_categories`
--

CREATE TABLE `commitment_categories` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `commitment_categories`
--

INSERT INTO `commitment_categories` (`id`, `user_id`, `category`) VALUES
(1, 1, 'food'),
(2, 1, 'housing'),
(3, 1, 'entertainment'),
(4, 4, 'food'),
(5, 4, 'housing'),
(6, 5, 'food'),
(7, 5, 'home'),
(8, 5, 'chaild');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contact_submissions`
--

INSERT INTO `contact_submissions` (`id`, `name`, `email`, `message`, `submission_date`) VALUES
(1, 'Aiman Ali', 'admin@mail.com', 'hlooooo', '2025-01-21 19:26:06');

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','resolved') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `financial_records`
--

CREATE TABLE `financial_records` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `commitments` decimal(10,2) NOT NULL,
  `savings` decimal(10,2) NOT NULL,
  `surplus` decimal(10,2) NOT NULL,
  `record_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `financial_records`
--

INSERT INTO `financial_records` (`id`, `user_id`, `salary`, `commitments`, `savings`, `surplus`, `record_date`) VALUES
(3, 3, '15000.00', '7000.00', '1500.00', '6500.00', '2025-01-22'),
(4, 3, '12000.00', '8000.00', '1200.00', '2800.00', '2025-01-22'),
(7, 1, '15000.00', '0.00', '1500.00', '4500.00', '2025-01-26'),
(8, 4, '12000.00', '0.00', '6000.00', '-3000.00', '2025-01-26'),
(9, 4, '14000.00', '0.00', '4200.00', '-40200.00', '2025-01-26'),
(10, 4, '20000.00', '0.00', '2000.00', '10000.00', '2025-01-26'),
(11, 5, '15000.00', '0.00', '1800.00', '4200.00', '2025-01-26');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `expiry_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `reset_token`, `expiry_date`) VALUES
(1, 1, '04f418956fa7f2e58cf8bb1a8f17638f18f8b1ac9493d7cf2df3d31ae9bd218e', '2025-01-22 01:26:39'),
(3, 1, '6724ed870afdfe46a11500412697e0d2d17fdef2e1cfb343cef95fe5cdf13277', '2025-01-22 02:14:12'),
(4, 1, '57bbbb12fd020666544ed8e51936955bcf84c1a7f28f00fa3a8179708d888dc6', '2025-01-22 02:22:12'),
(5, 1, '41075556495a26f22d40b369d88957ea4a6f0f5c1e0500cd2c85f8a3a7643c0c', '2025-01-22 02:31:47'),
(6, 1, '171938729eb2b070aa01f6bcca45833952dac4d7a56c727c7e38cdced5d69a1f', '2025-01-22 02:32:36'),
(7, 1, '366c170b58a85dae63984410afd1d2d2f96a97c77ba91fc12ecc114de5f398b8', '2025-01-22 02:32:45'),
(8, 1, 'ff921c23f28da5441fa1258b8f43abc220d3df63ba1de1dd28e54969442e9d60', '2025-01-22 02:33:15'),
(9, 1, '9b61851e762bace6f5f8dfb1785e52e46c600befbd835fd8fd980f1266e7df58', '2025-01-22 02:34:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `verification_token`, `is_verified`, `created_at`, `role`) VALUES
(1, 'test', 'ali@gmail.com', '$2y$10$2VYkvVssserJ.vPLSvSUteUG6UE/vwHsJjLmbeE2LiSxo6qKja1gK', NULL, 0, '2025-01-21 18:56:26', 'user'),
(3, 'aa', 'aaa@gmail.com', '$2y$10$WP8aW/LJiQmw/fqRdFQ0DutMd54l28DzfLOw3WYeDYsTC7rjtK1..', NULL, 0, '2025-01-22 00:38:51', 'user'),
(4, 'Aiman Ali', 's@s.com', '$2y$10$wNMxdE/xZYXBPJAee2iGPumYNwrKlINR6gh9DuEZHFUSV6QNzSwf.', NULL, 0, '2025-01-26 00:01:43', 'admin'),
(5, 'adasd', 'w@w.com', '$2y$10$1HMvOuvk040zBEB4IZy4A.GcPb3rMls/AssyBuJnxldDsU0jDcEsy', NULL, 0, '2025-01-26 02:25:49', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user_categories`
--

CREATE TABLE `user_categories` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_categories`
--

INSERT INTO `user_categories` (`id`, `user_id`, `category`) VALUES
(1, 1, 'food');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `commitments`
--
ALTER TABLE `commitments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_record_id` (`record_id`);

--
-- Indexes for table `commitment_categories`
--
ALTER TABLE `commitment_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category` (`category`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `financial_records`
--
ALTER TABLE `financial_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_categories`
--
ALTER TABLE `user_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commitments`
--
ALTER TABLE `commitments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `commitment_categories`
--
ALTER TABLE `commitment_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_records`
--
ALTER TABLE `financial_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_categories`
--
ALTER TABLE `user_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `commitments`
--
ALTER TABLE `commitments`
  ADD CONSTRAINT `commitments_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `financial_records` (`id`),
  ADD CONSTRAINT `fk_record_id` FOREIGN KEY (`record_id`) REFERENCES `financial_records` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `commitment_categories`
--
ALTER TABLE `commitment_categories`
  ADD CONSTRAINT `commitment_categories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `financial_records`
--
ALTER TABLE `financial_records`
  ADD CONSTRAINT `financial_records_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_categories`
--
ALTER TABLE `user_categories`
  ADD CONSTRAINT `user_categories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
