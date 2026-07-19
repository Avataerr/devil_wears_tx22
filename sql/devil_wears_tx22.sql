-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2026 at 05:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `devil_wears_tx22`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`id`, `user_id`, `action`, `ip_address`, `created_at`) VALUES
(1, 1, 'Logged in', '::1', '2026-07-18 18:57:08'),
(2, 1, 'Logged out', '::1', '2026-07-18 19:03:30'),
(3, 2, 'Logged in', '::1', '2026-07-18 19:03:51'),
(4, 2, 'Logged out', '::1', '2026-07-18 19:07:32'),
(5, 1, 'Logged in', '::1', '2026-07-18 19:07:42'),
(6, 1, 'Created user abvaldez@gmail.com as buyer', '::1', '2026-07-18 19:10:31'),
(7, 1, 'Logged out', '::1', '2026-07-18 19:10:43'),
(8, 5, 'Logged in', '::1', '2026-07-18 19:11:14'),
(9, 5, 'Logged out', '::1', '2026-07-18 19:12:30'),
(10, 5, 'Logged in', '::1', '2026-07-18 19:12:36'),
(11, 5, 'Logged out', '::1', '2026-07-18 19:18:24'),
(12, 5, 'Logged in', '::1', '2026-07-18 19:18:30'),
(13, 5, 'Logged out', '::1', '2026-07-18 19:19:13'),
(14, 1, 'Logged in', '::1', '2026-07-18 19:19:26'),
(15, 1, 'Added a product', '::1', '2026-07-18 19:21:46'),
(16, 1, 'Added product 1 to cart', '::1', '2026-07-18 19:25:56'),
(17, 1, 'Removed product 1 from cart', '::1', '2026-07-18 19:25:58'),
(18, 1, 'Added product 1 to cart', '::1', '2026-07-18 19:36:47'),
(19, 1, 'Logged out', '::1', '2026-07-18 20:02:18'),
(20, 2, 'Logged in', '::1', '2026-07-18 20:02:27'),
(21, 2, 'Added product 1 to cart', '::1', '2026-07-18 20:02:30'),
(22, 2, 'Placed order #1', '::1', '2026-07-18 20:02:34'),
(23, 2, 'Logged out', '::1', '2026-07-18 20:02:43'),
(24, 1, 'Logged in', '::1', '2026-07-18 20:02:50'),
(25, 1, 'Logged out', '::1', '2026-07-18 20:09:06'),
(26, 1, 'Logged in', '::1', '2026-07-18 20:09:14'),
(27, 1, 'Updated user #2', '::1', '2026-07-19 01:29:34'),
(28, 1, 'Logged out', '::1', '2026-07-19 01:30:36'),
(29, 2, 'Logged in', '::1', '2026-07-19 01:30:46'),
(30, 2, 'Logged out', '::1', '2026-07-19 01:31:00'),
(31, 1, 'Logged in', '::1', '2026-07-19 01:31:09'),
(32, 1, 'Updated user #2', '::1', '2026-07-19 01:31:17'),
(33, 1, 'Logged out', '::1', '2026-07-19 01:35:48'),
(34, 1, 'Logged in', '::1', '2026-07-19 01:36:10'),
(35, 1, 'Logged out', '::1', '2026-07-19 01:43:43'),
(36, 1, 'Logged in', '::1', '2026-07-19 01:46:44'),
(37, 1, 'Added product: Mini Square Pearl Flap Bag', '::1', '2026-07-19 02:40:00'),
(38, 1, 'Updated product: Mini Square Pearl Flap Bag', '::1', '2026-07-19 02:40:43'),
(39, 1, 'Added product: LV Monogram Monogram Flower Zipped Tote MM', '::1', '2026-07-19 02:41:17'),
(40, 1, 'Added product: Quilted CC Duma Backpack', '::1', '2026-07-19 02:41:59'),
(41, 1, 'Added product: Mini 25 Hobo', '::1', '2026-07-19 02:46:30'),
(42, 1, 'Added product: Miu Miu', '::1', '2026-07-19 02:47:03'),
(43, 1, 'Removed product 1 from cart', '::1', '2026-07-19 02:51:54'),
(44, 1, 'Added product 3 to cart', '::1', '2026-07-19 02:52:01'),
(45, 1, 'Placed order #2', '::1', '2026-07-19 02:52:07'),
(46, 1, 'Logged out of the system', '::1', '2026-07-19 03:14:50'),
(47, 1, 'Logged into the system', '::1', '2026-07-19 03:15:03'),
(48, 1, 'Added product \'Denim Urban Spirit Backpack\' (Category: Backpacks, Price: ₱1,645.00, Stock: 5)', '::1', '2026-07-19 03:16:47'),
(49, 1, 'Updated product \'Denim Urban Spirit Backpack\' (Price: ₱2,900.00, Stock: 5)', '::1', '2026-07-19 03:17:45'),
(50, 1, 'Added product \'Watanabs\' (Category: Backpacks, Price: ₱18,000.00, Stock: 1)', '::1', '2026-07-19 03:18:06'),
(51, 1, 'Deleted product \'Watanabs\'', '::1', '2026-07-19 03:18:10'),
(52, 1, 'Created buyer account for Janella Bajada', '::1', '2026-07-19 03:18:50'),
(53, 1, 'Added \'Galleria small patent Saffiano leather bag\' to cart', '::1', '2026-07-19 03:19:08'),
(54, 1, 'Removed \'Galleria small patent Saffiano leather bag\' from cart', '::1', '2026-07-19 03:19:14'),
(55, 1, 'Added \'Denim Urban Spirit Backpack\' to cart', '::1', '2026-07-19 03:19:22'),
(56, 1, 'Added \'LV Monogram Monogram Flower Zipped Tote MM\' to cart', '::1', '2026-07-19 03:19:29'),
(57, 1, 'Placed order containing: Denim Urban Spirit Backpack x1, LV Monogram Monogram Flower Zipped Tote MM x1', '::1', '2026-07-19 03:19:34');

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Backpacks'),
(4, 'Crossbody'),
(2, 'Shoulder Bags'),
(3, 'Totes');

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending Payment',
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `payment_method`, `created_at`) VALUES
(1, 2, 4800.00, 'Pending Payment', 'GCash (manual)', '2026-07-18 20:02:34'),
(2, 1, 1395.00, 'Pending Payment', 'GCash (manual)', '2026-07-19 02:52:07'),
(3, 1, 4295.00, 'Pending Payment', 'Bank Transfer', '2026-07-19 03:19:34');

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `price`, `quantity`) VALUES
(1, 1, 1, 4800.00, 1),
(2, 2, 3, 1395.00, 1),
(3, 3, 7, 2900.00, 1),
(4, 3, 3, 1395.00, 1);

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `stock`, `image`, `created_at`) VALUES
(1, 2, 'Galleria small patent Saffiano leather bag', 'The Prada Galleria bag is rediscovered each season with new interpretations that enhance its timeless silhouette. The accessory presented in a small version is enhanced by the elegant finish of Saffiano leather with a patent effect that gives the iconic style sophisticated allure.', 4800.00, 9, '1784402506_34727761_66070465_1000.webp', '2026-07-18 19:21:46'),
(2, 4, 'Mini Square Pearl Flap Bag', 'Chanel Crossbody Bag\r\nFrom the Fall/Winter 2020 Collection by Virginie Viard\r\nBlack Leather\r\nInterlocking CC Logo, Faux Pearl Accents & Chain-Link Accent\r\nGold-Tone Hardware\r\nChain-Link Shoulder Strap\r\nFringe, Beaded, Sequin & Chain-Link Accents\r\nGrosgrain Lining & Dual Interior Pockets\r\nTurn-Lock Closure at Front\r\nIncludes Dust Bag & Authenticity Card', 5500.00, 3, '1784428800_CHA1467358_1_enlarged.webp', '2026-07-19 02:40:00'),
(3, 3, 'LV Monogram Monogram Flower Zipped Tote MM', 'Louis Vuitton Tote\r\nFrom the 2019 Collection\r\nBrown Coated Canvas\r\nLV Monogram\r\nBrass Hardware\r\nLeather Trim\r\nRolled Handles & Single Shoulder Strap\r\nLeather Trim Embellishment & Three Exterior Pockets\r\nAlcantara Lining & Dual Interior Pockets\r\nZip Closure at Top\r\nIncludes Box & Dust Bag', 1395.00, 3, '1784428877_LOU1294576_1_enlarged.webp', '2026-07-19 02:41:17'),
(4, 1, 'Quilted CC Duma Backpack', 'Chanel Backpack\r\nFrom the 1990\'s Collection by Karl Lagerfeld\r\nVintage\r\nBlack Leather\r\nInterlocking CC Logo, Quilted Pattern & Chain-Link Accent\r\nGold-Tone Hardware\r\nDual Adjustable Shoulder Straps\r\nChain-Link Accents & Single Exterior Pocket\r\nLeather Lining\r\nTurn-Lock Closure at Front\r\nIncludes Box & Dust Bag', 995.00, 4, '1784428919_CHA1456457_1_enlarged.webp', '2026-07-19 02:41:59'),
(5, 2, 'Mini 25 Hobo', 'Chanel Bucket Bag\r\nPink\r\nGold-Tone Hardware\r\nChain-Link Shoulder Strap\r\nDual Exterior Pockets\r\nCanvas Lining & Dual Interior Pockets\r\nDrawstring Closure at Front\r\nIncludes Box & Dust Bag', 8500.00, 1, '1784429190_CHA1465260_1_enlarged.webp', '2026-07-19 02:46:30'),
(6, 2, 'Miu Miu', 'Miu Miu Shoulder Bag\r\nBrown Suede\r\nAntiqued Silver-Tone Hardware\r\nSingle Adjustable Shoulder Strap\r\nFive Exterior Pockets\r\nSatin Lining & Single Interior Pocket\r\nZip Closure at Top\r\nIncludes Dust Bag', 2800.00, 3, '1784429223_MIU253614_1_enlarged.webp', '2026-07-19 02:47:03'),
(7, 1, 'Denim Urban Spirit Backpack', 'Chanel Backpack\r\nFrom the Spring/Summer 2015-2016 Collection by Karl Lagerfeld\r\nOrange Denim\r\nInterlocking CC Logo\r\nGold-Tone Hardware\r\nLeather Trim\r\nDual Adjustable Shoulder Straps\r\nLeather & Chain-Link Accents\r\nSingle Exterior Pocket\r\nGrosgrain Lining & Dual Interior Pockets\r\nTurn-Lock Closure at Front\r\nIncludes Authenticity Card', 2900.00, 4, '1784431007_CHA1467729_1_enlarged.webp', '2026-07-19 03:16:47');

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `surname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `role` enum('admin','buyer') NOT NULL DEFAULT 'buyer',
  `status` enum('pending','active','disabled') NOT NULL DEFAULT 'pending',
  `verify_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `surname`, `email`, `password`, `address`, `contact_number`, `role`, `status`, `verify_token`, `created_at`) VALUES
(1, 'Maverrick', 'Bajada', 'Watanabe', 'mbwatanabe@fit.edu.ph', 'watanabs', 'Quezon City', '09123456789', 'admin', 'active', NULL, '2026-07-18 18:39:28'),
(2, 'Kim', '', 'Dokja', 'kimdokja@gmail.com', 'bihyung', 'Seoul, South Korea', '09111111111', 'buyer', 'active', NULL, '2026-07-18 18:44:11'),
(3, 'Lee', '', 'Chaeyeon', 'leechaeyeon@gmail.com', 'izone', 'Seoul, South Korea', '09222222222', 'buyer', 'active', NULL, '2026-07-18 18:44:11'),
(4, 'Simoune', 'Nicole', 'Toquero', 'sntoquero@fit.edu.ph', 'sntoquero', 'Quezon City', '09333333333', 'admin', 'active', NULL, '2026-07-18 18:44:11'),
(5, 'Althea', 'Bajada', 'Valdez', 'abvaldez@gmail.com', 'dancingqueen', 'Quezon City', '09192564567', 'buyer', 'active', NULL, '2026-07-18 19:10:31'),
(6, 'Janella', '', 'Bajada', 'jbajada@gmail.com', 'pengu', 'Quezon City', '09234567423', 'buyer', 'active', NULL, '2026-07-19 03:18:50');

ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `audit_log`
  ADD CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;
