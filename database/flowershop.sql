-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 17, 2025 at 02:17 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `flowershop`
--

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `id` int(11) NOT NULL,
  `addons` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `stocks` int(11) NOT NULL,
  `sold` int(11) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `available_stocks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addons`
--

INSERT INTO `addons` (`id`, `addons`, `image`, `stocks`, `sold`, `price`, `available_stocks`) VALUES
(4, 'Ballon', '6816cd13afe39.jpg', 100, NULL, 9, NULL),
(5, 'Bracelet', '6816cd2bede40.jpg', 100, NULL, 2500, NULL),
(6, 'Butterfly', '6816cd5875a57.jpg', 100, NULL, 15, NULL),
(7, 'Chocolates', '6816cde545733.jpeg', 100, NULL, 99, NULL),
(8, 'Crown', '6816f4a715372.jpg', 100, NULL, 199, NULL),
(9, 'Necklace', '6816f4bd2e615.jpg', 100, NULL, 799, NULL),
(10, 'Pocky', '6816f4d05d88e.jpg', 100, NULL, 99, NULL),
(11, 'Pringles', '6816f4df3ec07.jpg', 100, NULL, 129, NULL),
(12, 'Teddy', '6816f4f1c78a5.jpg', 100, NULL, 125, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(11) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`email`, `password`, `phone_number`, `address`, `image`, `username`, `fullname`) VALUES
('admin@gmail.com', '$2y$10$eoUMFupkmJMdjWHqfDDuAOa/13rUt9gVI7IiX.vSmplCKuvScnH8C', '09225049004', 'Baliwag, Bulacan', '../img/cherry-blossom.png', 'admin', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `product_name`, `product_image`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(319, 72, 182, 'Dried Flower 6', '67fcbd36d0772.jpg', 1, '149', '2025-05-04 02:35:01', '2025-05-04 02:35:01'),
(320, 72, 204, 'Sweet Treats 3', '67fd1ed677aa2.jpg', 1, '399', '2025-05-04 02:38:57', '2025-05-04 02:38:57'),
(327, 72, 167, 'Bundle 1', '67fa88e379393.jpg', 1, '429', '2025-05-04 03:08:53', '2025-05-04 03:08:53'),
(328, 71, 170, 'Bundle 4', '67fcad3587e84.jpg', 1, '1499', '2025-05-04 06:31:30', '2025-05-04 06:31:30'),
(329, 71, 190, 'Luxury 1', '67fd16378214b.jpg', 1, '500', '2025-05-04 07:49:18', '2025-05-04 07:49:18'),
(330, 71, 171, 'Bundle 5', '67fcae1bc551e.jpg', 2, '299', '2025-05-04 08:03:11', '2025-05-04 08:33:28'),
(332, 71, 198, 'Funeral Package 3', '67fd1726cdaee.jpg', 1, '1499', '2025-05-04 08:24:12', '2025-05-04 08:24:12');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `product_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category`, `product_count`) VALUES
(64, 'Luxury Gifts', 7),
(65, 'Dried Flowers', 7),
(66, 'Funeral Flowers', 6),
(67, 'Bundle', 0),
(68, 'Sweet Treats', 6),
(69, 'Enternal Flowers', 0);

-- --------------------------------------------------------

--
-- Table structure for table `design_settings`
--

CREATE TABLE `design_settings` (
  `id` int(11) NOT NULL,
  `background_color` varchar(255) DEFAULT NULL,
  `font_color` varchar(255) DEFAULT NULL,
  `shop_name` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `image_one_path` varchar(255) DEFAULT NULL,
  `image_two_path` varchar(255) DEFAULT NULL,
  `image_three_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `design_settings`
--

INSERT INTO `design_settings` (`id`, `background_color`, `font_color`, `shop_name`, `logo_path`, `image_one_path`, `image_two_path`, `image_three_path`) VALUES
(1, '#fff8f0', '#000000', 'Sunny Bloom', '../img/logo2.png', '../img/chocolates.jpeg', '../img/lotus.jpg', '../img/crown.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `flower`
--

CREATE TABLE `flower` (
  `id` int(11) NOT NULL,
  `flower` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `stocks` int(11) NOT NULL,
  `sold` int(11) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `available_stocks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flower`
--

INSERT INTO `flower` (`id`, `flower`, `image`, `stocks`, `sold`, `price`, `available_stocks`) VALUES
(8, 'Cosmos', '67fd036a9d36d.jpg', 100, NULL, 25, NULL),
(9, 'Dahlia', '67fcfc95948db.jpg', 100, NULL, 5, NULL),
(10, 'Red Rose', '67fcfc5335fe6.jpg', 100, NULL, 99, NULL),
(11, 'Daisy', '67fcfc4273446.jpg', 100, NULL, 17, NULL),
(12, 'Tulips', '67fd18ab414ac.jpg', 100, NULL, 29, NULL),
(13, 'Blue Rose', '67fd18bbdd020.jpg', 100, NULL, 99, NULL),
(14, 'Corn Flower', '67fd18eedd69d.jpg', 100, NULL, 50, NULL),
(15, 'Iris', '67fd1d010fe73.jpeg', 100, NULL, 30, NULL),
(16, 'Lavander', '67fd1d1e753d4.jpg', 100, NULL, 50, NULL),
(17, 'Lily', '67fd1d2e46dba.jpg', 100, NULL, 25, NULL),
(18, 'Lotus', '67fd1d46f0461.jpg', 100, NULL, 15, NULL),
(19, 'Peony', '67fd1d5d6449c.jpeg', 100, NULL, 50, NULL),
(20, 'Pink Rose', '67fd1d6d85d9e.jpg', 100, NULL, 99, NULL),
(21, 'Plumeria', '67fd1d8153f9a.jpg', 100, NULL, 70, NULL),
(22, 'Sunflower', '67fd1d978d8e3.jpg', 100, NULL, 80, NULL),
(23, 'Zinnia', '67fd1dab54d77.jpeg', 100, NULL, 56, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) DEFAULT 'approved\r\n',
  `custom_letter` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_name`, `name`, `phone`, `address`, `payment_method`, `total_amount`, `order_date`, `status`, `custom_letter`) VALUES
(70, 'test', 'Testing 1', '09123456798', 'Testing St.', 'COD', 3899.00, '2025-05-04 05:03:41', 'approved\r\n', NULL),
(71, 'test', 'Testing 2', '09123456789', 'Testing St.', 'BDO', 2399.00, '2025-05-04 05:04:15', 'approved\r\n', NULL),
(72, 'test', 'Testing 3', '09123456789', 'Testing St.', 'GCash', 1499.00, '2025-05-04 05:04:50', 'approved\r\n', NULL),
(73, 'test', 'Testing', '09321456789', 'Testing St.', 'COD', 1499.00, '2025-05-04 07:49:47', 'approved\r\n', NULL),
(74, 'test', 'Testing', '09123456897', 'Testing St.', 'GCash', 349.00, '2025-05-04 11:10:54', 'approved\r\n', NULL),
(75, 'test', 'Testing', '09123456897', 'Testing St.', 'GCash', 349.00, '2025-05-04 11:11:06', 'approved\r\n', NULL),
(76, 'test', 'Testing', '09123456897', 'Testing St.', 'GCash', 349.00, '2025-05-04 11:11:48', 'approved\r\n', NULL),
(79, 'test', 'Testing', '09123467498', 'Testing St.', 'GCash', 2299.00, '2025-05-04 11:13:10', 'approved\r\n', NULL),
(80, 'test', 'Testing 2', '09236785463', 'Testing St.', 'BDO', 699.00, '2025-05-04 11:15:40', 'approved\r\n', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `quantity`, `price`, `total_price`, `product_image`) VALUES
(74, 70, 'Funeral Package 2', 1, 3899.00, 3899.00, '67fd17138967b.jpg'),
(75, 71, 'Luxury 1', 1, 500.00, 500.00, '67fd16378214b.jpg'),
(76, 71, 'Funeral Package 5', 1, 1899.00, 1899.00, '67fd186880c30.jpg'),
(77, 72, 'Bundle 4', 1, 1499.00, 1499.00, '67fcad3587e84.jpg'),
(78, 73, 'Funeral Package 3', 5, 1499.00, 1499.00, '67fd1726cdaee.jpg'),
(80, 79, 'Enternal Flower 3', 1, 2299.00, 2299.00, '67fcbd9f9b2bf.jpg'),
(81, 80, 'Sweet Treats 4', 1, 699.00, 699.00, '67fd1eee75d18.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pos_orders`
--

CREATE TABLE `pos_orders` (
  `id` int(11) NOT NULL,
  `cashier_name` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pos_orders`
--

INSERT INTO `pos_orders` (`id`, `cashier_name`, `total_amount`, `payment_method`, `order_date`) VALUES
(1, 'Admin', 1398.00, 'Cash', '2025-05-06 06:14:09'),
(2, 'Admin', 100.00, 'Cash', '2025-05-06 06:15:06'),
(3, 'Admin', 1398.00, 'Cash', '2025-05-06 06:33:55'),
(4, 'Admin', 429.00, 'Cash', '2025-05-06 06:53:21');

-- --------------------------------------------------------

--
-- Table structure for table `pos_order_items`
--

CREATE TABLE `pos_order_items` (
  `id` int(11) NOT NULL,
  `pos_order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pos_order_items`
--

INSERT INTO `pos_order_items` (`id`, `pos_order_id`, `product_id`, `product_name`, `quantity`, `price`, `total_price`) VALUES
(1, 3, 203, 'Sweet Treats 2', 1, 599.00, 599.00),
(2, 3, 202, 'Sweet Treats 1', 1, 799.00, 799.00),
(3, 4, 167, 'Bundle 1', 1, 429.00, 429.00);

-- --------------------------------------------------------

--
-- Table structure for table `pots`
--

CREATE TABLE `pots` (
  `id` int(11) NOT NULL,
  `pots` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `stocks` int(11) NOT NULL,
  `sold` int(11) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `available_stocks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pots`
--

INSERT INTO `pots` (`id`, `pots`, `image`, `stocks`, `sold`, `price`, `available_stocks`) VALUES
(8, 'Pot 1', '6817158e3743d.jpg', 100, NULL, 150, NULL),
(9, 'Pot 2', '6817159e14e85.jpg', 100, NULL, 250, NULL),
(10, 'Pot 3', '681715b5be8b5.jpg', 100, NULL, 129, NULL),
(11, 'Pot 4', '681715cab1d0c.jpg', 100, NULL, 229, NULL),
(12, 'Pot 5', '6817160a6e21d.jpeg', 100, NULL, 160, NULL),
(13, 'Pot 6', '6817161b32b39.jpg', 100, NULL, 99, NULL),
(14, 'Pot 7', '6817162e2c683.jpg', 100, NULL, 119, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Available',
  `total_sold` int(11) DEFAULT 0,
  `available_stocks` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `image`, `category`, `qty`, `price`, `category_id`, `status`, `total_sold`, `available_stocks`) VALUES
(167, 'Bundle 1', '1746343206_bundle1.jpg', 'Bundle', 10, '429', 59, 'Available', 0, 10),
(168, 'Bundle 2', '1744612616_bundle2.jpg', 'Bundle', 10, '530', 59, 'Available', 0, 10),
(169, 'Bundle 3', '1746343216_bundle3.jpg', 'Bundle', 10, '485', 59, 'Available', 0, 10),
(170, 'Bundle 4', '67fcad3587e84.jpg', 'Bundle', 10, '1499', 59, 'Available', 1, 9),
(171, 'Bundle 5', '67fcae1bc551e.jpg', 'Bundle', 12, '299', 59, 'Available', 0, 12),
(172, 'Bundle 6', '67fcae4752c42.jpg', 'Bundle', 10, '350', 59, 'Available', 0, 10),
(173, 'Dried Flower 1', '67fcafdb28851.jpg', 'Dried Flowers', 10, '599', 65, 'Available', 0, 10),
(174, 'Dried Flower 2', '67fcaffce4a72.jpg', 'Dried Flowers', 10, '499', 65, 'Available', 0, 10),
(175, 'Dried Flower 3', '67fcb470bfb12.jpg', 'Dried Flowers', 10, '399', 65, 'Available', 0, 10),
(176, 'Dried Flower 4', '1744615205_dried4.jpg', 'Dried Flowers', 10, '1599', 65, 'Available', 0, 10),
(177, 'Dried Flower 5', '67fcb74617883.jpg', 'Dried Flowers', 10, '199', 65, 'Available', 0, 10),
(182, 'Dried Flower 6', '67fcbd36d0772.jpg', 'Dried Flowers', 10, '149', 65, 'Available', 0, 10),
(184, 'Enternal Flower 1', '67fcbd75e5593.jpg', 'Enternal Flowers', 10, '1599', 63, 'Available', 0, 10),
(185, 'Enternal Flower 2', '67fcbd867eb52.jpg', 'Enternal Flowers', 10, '1599', 63, 'Available', 0, 10),
(186, 'Enternal Flower 3', '67fcbd9f9b2bf.jpg', 'Enternal Flowers', 10, '2299', 63, 'Available', 1, 9),
(187, 'Enternal Flower 4', '67fcbdc9eb9a9.jpg', 'Enternal Flowers', 10, '2299', 63, 'Available', 0, 10),
(188, 'Enternal Flower 5', '67fcbde04cdb2.jpg', 'Enternal Flowers', 10, '2599', 63, 'Available', 0, 10),
(189, 'Enternal Flower 6', '1744632693_enternal6.jpg', 'Enternal Flowers', 10, '1599', 63, 'Available', 0, 10),
(190, 'Luxury 1', '67fd16378214b.jpg', 'Luxury Gifts', 10, '500', 64, 'Available', 1, 9),
(191, 'Luxury 2', '67fd165d59d23.jpg', 'Luxury Gifts', 10, '299', 64, 'Available', 0, 10),
(192, 'Luxury 3', '67fd167d303a2.jpg', 'Luxury Gifts', 10, '3999', 64, 'Available', 0, 10),
(193, 'Luxury 4', '67fd1697473f6.jpg', 'Luxury Gifts', 10, '349', 64, 'Available', 0, 10),
(194, 'Luxury 5', '67fd16b24ce05.jpg', 'Luxury Gifts', 10, '799', 64, 'Available', 0, 10),
(195, 'Luxury 6', '67fd16ccc85ea.jpg', 'Luxury Gifts', 10, '1299', 64, 'Available', 0, 10),
(196, 'Funeral Package 1', '67fd16f242b0f.jpg', 'Funeral Flowers', 10, '1350', 66, 'Available', 0, 10),
(197, 'Funeral Package 2', '67fd17138967b.jpg', 'Funeral Flowers', 10, '3899', 66, 'Available', 1, 9),
(198, 'Funeral Package 3', '67fd1726cdaee.jpg', 'Funeral Flowers', 10, '1499', 66, 'Available', 5, 5),
(199, 'Funeral Package 4', '67fd184020c66.jpg', 'Funeral Flowers', 10, '1599', 66, 'Available', 0, 10),
(200, 'Funeral Package 5', '67fd186880c30.jpg', 'Funeral Flowers', 10, '1899', 66, 'Available', 1, 9),
(201, 'Funeral Package 6', '67fd187fc2ed8.jpg', 'Funeral Flowers', 10, '1399', 66, 'Available', 0, 10),
(202, 'Sweet Treats 1', '67fd1ea54fb53.jpg', 'Sweet Treats', 10, '799', 68, 'Available', 0, 10),
(203, 'Sweet Treats 2', '67fd1ec28b183.jpg', 'Sweet Treats', 10, '599', 68, 'Available', 0, 10),
(204, 'Sweet Treats 3', '67fd1ed677aa2.jpg', 'Sweet Treats', 10, '399', 68, 'Available', 0, 10),
(205, 'Sweet Treats 4', '67fd1eee75d18.jpg', 'Sweet Treats', 10, '699', 68, 'Available', 1, 9),
(206, 'Sweet Treats 5', '1746343233_sweettreats5.jpg', 'Sweet Treats', 10, '299', 68, 'Available', 0, 10),
(207, 'Sweet Treats 6', '1746343245_sweettreats6.jpg', 'Sweet Treats', 10, '2299', 68, 'Available', 0, 10),
(209, 'Test', '68176316552eb.jpg', 'Luxury Gifts', 10, '100', 64, 'Available', 0, 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` int(6) NOT NULL,
  `email_verified_at` datetime(6) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiration` varchar(255) DEFAULT NULL,
  `last_attempt` timestamp NOT NULL DEFAULT current_timestamp(),
  `blocked` tinyint(1) NOT NULL DEFAULT 0,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `contact_number` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `verification_code`, `email_verified_at`, `reset_token`, `reset_token_expiration`, `last_attempt`, `blocked`, `attempts`, `contact_number`, `address`, `image_path`, `first_name`, `middle_name`, `last_name`) VALUES
(66, 'test1', 'test1@gmail.com', '$2y$10$S.laHtAxLK7dg5DTPThpYegilagYNO.o/qKiHNsR6pAcRLz.VfhvG', 189424, NULL, NULL, NULL, '2025-04-19 05:07:26', 1, 3, '09551041534', 'kalye Katorse', NULL, 'Habib', 'M', 'A AHASAN'),
(71, 'test', 'test@gmail.com', '$2y$10$QLYlpKbvTymOu.F2ZBBl1uBDtSoLqnrn/2yLUVtU3l7jvCg4IkSBa', 937574, '2025-04-29 17:03:07.000000', NULL, NULL, '2025-04-29 09:02:56', 0, 0, '09123456789', 'Testing St.', '../img/6816d98cebd07.jpg', 'Testing', 'T', 'Ground'),
(72, 'test2', 'test2@gmail.com', '$2y$10$vZ6xItlid01QytOeCLYqk.cTBYitAChvIRNhWA/tvDl9MdV/aPe9e', 310948, '2025-05-04 11:06:51.000000', NULL, NULL, '2025-05-04 02:32:34', 0, 0, '09123456789', 'Testing St.', '../img/6816d95da9ba1.jpg', 'Test 2', '2', 'Testing'),
(73, 'test3', 'test3@gmail.com', '$2y$10$iHR7glEOIJI.JkCTeAtmp.eThrAbKYpbJd08nn94luCMSA/JZU1R.', 354211, NULL, NULL, NULL, '2025-05-04 03:11:13', 0, 0, '09123456789', 'Testing St.', '', 'Testing 3', '3', 'Testing'),
(74, 'itsmy', 'ybiza2018@gmail.com', '$2y$10$4fywoMtfRZtJYrCSLfPVBeMFBXgBOog9g0KEopmV.kcVloPdOz/TW', 331651, NULL, NULL, NULL, '2025-05-06 11:50:35', 0, 0, '09496563656', 'kalye Katorse', '', 'Habib', 'M', 'A AHASAN');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `design_settings`
--
ALTER TABLE `design_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flower`
--
ALTER TABLE `flower`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `pos_orders`
--
ALTER TABLE `pos_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_order_items`
--
ALTER TABLE `pos_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pos_order_id` (`pos_order_id`);

--
-- Indexes for table `pots`
--
ALTER TABLE `pots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addons`
--
ALTER TABLE `addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=343;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `design_settings`
--
ALTER TABLE `design_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `flower`
--
ALTER TABLE `flower`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `pos_orders`
--
ALTER TABLE `pos_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pos_order_items`
--
ALTER TABLE `pos_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pots`
--
ALTER TABLE `pots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `pos_order_items`
--
ALTER TABLE `pos_order_items`
  ADD CONSTRAINT `pos_order_items_ibfk_1` FOREIGN KEY (`pos_order_id`) REFERENCES `pos_orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
