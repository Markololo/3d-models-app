-- phpMyAdmin SQL Dump
-- version 5.2.3-dev+20250818.dd3d8baef3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 19, 2025 at 06:32 PM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 8.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `3d_models_app_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Carbon Fiber', 'This is a category for the 3d models that need to be made (preferably) with carbon fiber filaments.', '2025-11-05 09:29:47'),
(2, 'Handy kitchen tools', 'Find a variety of 3d printable kitchen tools and gadgets to make your life easier.', '2025-11-05 09:40:10'),
(3, 'Kids gadgets', '3d printable mini toys and gadgets for kids.', '2025-11-05 09:40:10'),
(6, 'School gadgets', 'A collection of amazing 3D designs that will make school life much more interesting and fun!', '2025-12-18 18:56:05'),
(10, 'Tech tools', 'A collection of handy tech tools and gadgets to make your workspace more organized.', '2025-12-19 11:51:30');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempted_at` timestamp NULL DEFAULT current_timestamp(),
  `successful` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `created_at`) VALUES
(1, 8, 23.99, 'Pending', '2025-12-18 18:13:06'),
(2, 5, 150.25, 'Completed', '2025-12-18 18:13:06'),
(3, 2, 100.00, 'Pending', '2025-12-18 18:14:07'),
(4, 5, 25.50, 'Pending', '2025-12-18 18:14:07'),
(5, 9, 331.46, 'pending', '2025-12-18 19:33:48');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(1, 5, 2, 1, 2.15),
(2, 5, 2, 1, 2.15),
(3, 5, 2, 1, 2.15),
(4, 5, 1, 1, 123.13),
(5, 5, 1, 1, 123.13),
(6, 5, 2, 1, 5.25),
(7, 5, 2, 1, 5.25),
(8, 5, 2, 1, 5.25),
(9, 5, 2, 1, 5.25),
(10, 5, 2, 1, 5.25),
(11, 5, 2, 1, 5.25),
(12, 5, 2, 1, 5.25),
(13, 5, 2, 1, 5.25),
(14, 5, 2, 1, 5.25),
(15, 5, 2, 1, 5.25),
(16, 5, 2, 1, 5.25),
(17, 5, 2, 1, 5.25),
(18, 5, 2, 1, 5.25),
(19, 5, 2, 1, 5.25),
(20, 5, 2, 1, 5.25);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `stock_quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 'iPhone 10 - Gyroid cover', 'Get yourself a super powerful iPhone 10 protector', 123.13, 2, '2025-11-05 09:35:46', '2025-12-18 19:34:13'),
(2, 2, 'Bag Closing Clip', 'A nice plastic clip to close any bag in your house.', 5.25, 12, '2025-11-05 09:41:49', '2025-12-19 11:46:44'),
(3, 2, 'kitchen towel hanger', 'A small hanger to stick on your wall to hang your kitchen towels.', 4.99, 5, '2025-11-05 09:43:43', NULL),
(5, 3, 'Moving Octopus', 'A small octopus with many joints to make it move a lot.', 5.75, 12, '2025-12-17 18:10:35', NULL),
(7, 3, 'NewTest', 'abcdef', 12.00, 9, '2025-12-18 19:39:58', NULL),
(8, 10, ' Open Air ITX Case (no screws)', 'The idea was a small footprint, easy to move, no major assembly required and durability.', 13.25, 3, '2025-12-19 11:54:54', NULL),
(9, 2, 'kitchen scale slider', 'These are mounts for a kitchen scale.', 4.99, 7, '2025-12-19 13:23:10', NULL),
(10, 3, '3D Mini Lantern', '3D printed lantern for desk. Put a small LED inside and done!', 7.75, 12, '2025-12-19 13:25:45', NULL),
(11, 3, 'Boy Figurine with Axe', 'Plastic figurine of a boy with an axe.', 13.85, 2, '2025-12-19 13:30:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `file_path`, `is_primary`) VALUES
(1, 1, 'upload_6944312bd959b.png', 1),
(2, 2, 'upload_69443fbde7500.jpg', 1),
(3, 1, 'upload_6944312bd959b.png', 0),
(4, 1, 'upload_694455f185af6.png', 0),
(5, 2, 'upload_69449e922e24b.jpg', 0),
(7, 8, 'upload_694583956e561.jpg', 0),
(8, 8, 'upload_694583aa6c521.jpg', 0),
(9, 8, 'upload_69458604d3c08.jpg', 0),
(10, 9, 'upload_69459829cb7c8.jpg', 1),
(11, 9, 'upload_6945983ae7ac5.jpg', 1),
(12, 10, 'upload_694598c58d15c.jpg', 1),
(13, 10, 'upload_694598df5d95e.jpg', 1),
(14, 10, 'upload_694598eccd025.jpg', 1),
(15, 5, 'upload_6945990d1d715.jpg', 1),
(16, 11, 'upload_694599cb1867d.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `trusted_devices`
--

CREATE TABLE `trusted_devices` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `device_token` varchar(255) NOT NULL,
  `device_name` varchar(100) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trusted_devices`
--

INSERT INTO `trusted_devices` (`id`, `user_id`, `device_token`, `device_name`, `user_agent`, `ip_address`, `last_used_at`, `expires_at`, `created_at`) VALUES
(1, 9, 'c1b43872ae56018b7f24d3776a2101e85c909b7929bb6cf776d44fa7c8f251b7', 'Windows PC', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '127.0.0.1', '2025-12-19 16:38:26', '2026-01-18 00:42:54', '2025-12-18 12:42:54');

-- --------------------------------------------------------

--
-- Table structure for table `two_factor_auth`
--

CREATE TABLE `two_factor_auth` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `secret` varchar(255) NOT NULL,
  `enabled` tinyint(1) DEFAULT 0,
  `enabled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `two_factor_auth`
--

INSERT INTO `two_factor_auth` (`id`, `user_id`, `secret`, `enabled`, `enabled_at`, `created_at`, `updated_at`) VALUES
(1, 7, '7PV7QEUF7WFLVFCXBLSJCERYHUV2VBS3', 0, '2025-12-17 20:48:16', '2025-12-17 20:48:16', '2025-12-17 21:03:36'),
(2, 9, 'YHDFYOA5SJYANJQML7PYE4GFPSZDZWJN', 1, '2025-12-19 00:35:56', '2025-12-19 00:35:56', '2025-12-19 00:35:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL COMMENT 'Unique user ID',
  `first_name` varchar(100) NOT NULL COMMENT 'User’s first name',
  `last_name` varchar(100) NOT NULL COMMENT 'User’s last name',
  `username` varchar(50) NOT NULL COMMENT 'User’s login name',
  `email` varchar(100) NOT NULL COMMENT 'User’s email address',
  `password_hash` varchar(255) NOT NULL COMMENT 'Hashed password (bcrypt)',
  `role` enum('admin','customer') DEFAULT 'customer' COMMENT 'Defines user role',
  `created_at` datetime DEFAULT current_timestamp() COMMENT 'Account creation date',
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Last profile update'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password_hash`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Mark', 'Sander', 'MarkSander', 'MarkSander@gmail.com', '$2y$12$82NwKJYo.z5ZQBj5.4cjs.Eubky7wrB8JbURVkV4kAs1Z1rxV0gkq', 'customer', '2025-11-20 08:44:22', NULL),
(2, 'Merrab', 'Khan', 'MeerabKhan', 'MeerabKhan@gmail.com', '$2y$12$N62qzDfmEUB5qZ6LzoGb7uIkOeAV66EmcLTjgRbpmiH4cH8Q0qK6u', 'admin', '2025-11-20 08:46:22', '2025-11-20 10:26:15'),
(3, 'abc', 'def', 'abcd', 'abcd@gmail.com', '$2y$12$JjFIeh0CGHBi3ZjNxq/v9OkQCqs.CLn5dMh9bDIB1GPaxiDhEWqOa', 'customer', '2025-11-20 10:01:38', NULL),
(4, 'abc', 'abc', 'abc', 'abc@gmail.com', '$2y$12$ndcw1BtGt2m.2uq4zTKMhe9Tr2na.oeDOVyTfVW1F0UJ.wNLotNVu', 'admin', '2025-11-26 08:50:25', '2025-11-26 08:53:28'),
(5, 'mariam', 'mariam', 'mariam', 'mariam@gmail.com', '$2y$12$qNM3kjq7mOc9QiMMYCYkiOYTJgZwMxiW8IPVcW8GpCWApbNeIBhLC', 'customer', '2025-12-04 09:19:10', NULL),
(7, 'Marko', 'Lemon', 'Markololo', 'markololo2468@gmail.com', '$2y$12$lqWgW.M9.c.cjwA.ycsNg.f4lUMDSYwc6Lhqc8OzeOAgtle2ZeQQW', 'admin', '2025-12-17 15:45:45', '2025-12-17 17:19:21'),
(8, 'Lemon', 'Sandra', 'Sandy@gmail.com', 'salimaisha2007@gmail.com', '$2y$12$l0dJ7lzwnDKYszO5Ao1knOehBqQOzdTatTSZx8cfAJskPXkGTK16S', 'customer', '2025-12-18 16:47:29', NULL),
(9, 'Sleiman', 'Rabah', 'Sleaman123', 'sleima@gmail.com', '$2y$12$74PaE6dm8Cx3HT2rzCn/yuVBKifHxjgjpWWvakaNAoBpPTT5LH.Ia', 'customer', '2025-12-18 19:33:17', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email_ip` (`email`,`ip_address`),
  ADD KEY `idx_attempted_at` (`attempted_at`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `trusted_devices`
--
ALTER TABLE `trusted_devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `device_token` (`device_token`),
  ADD KEY `idx_device_token` (`device_token`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `two_factor_auth`
--
ALTER TABLE `two_factor_auth`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `trusted_devices`
--
ALTER TABLE `trusted_devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `two_factor_auth`
--
ALTER TABLE `two_factor_auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique user ID', AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `trusted_devices`
--
ALTER TABLE `trusted_devices`
  ADD CONSTRAINT `trusted_devices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `two_factor_auth`
--
ALTER TABLE `two_factor_auth`
  ADD CONSTRAINT `two_factor_auth_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
