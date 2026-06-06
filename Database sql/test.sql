-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2026 at 06:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `cats`
--

CREATE TABLE `cats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `user_role` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cats`
--

INSERT INTO `cats` (`id`, `name`, `type`, `user_role`, `created_at`, `updated_at`) VALUES
(11, 'Burger', 'Food', 6, '2026-03-31 16:19:04', '2026-03-31 16:19:04'),
(12, 'Pizza', 'Food', 6, '2026-03-31 16:19:32', '2026-03-31 16:20:49'),
(13, 'Soft Drink', 'Drink', 6, '2026-03-31 16:20:02', '2026-03-31 16:20:59'),
(14, 'Ingredient', 'Ingredient', 6, '2026-04-01 05:43:38', '2026-04-01 05:43:38');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `amt` float NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `name`, `type`, `amt`, `created_at`, `updated_at`) VALUES
(5, 'Coca Cola', 'Cash', 100, '2026-04-01 07:11:47', '2026-04-01 07:11:47'),
(6, 'Coca Cola', 'Cash', 1000, '2026-04-02 03:55:46', '2026-04-02 03:55:46'),
(7, 'nothing', 'Cash', 2500, '2026-04-09 07:18:36', '2026-04-09 07:18:36'),
(8, 'nunu', 'Cash', 500, '2026-04-15 11:36:40', '2026-04-15 11:36:40'),
(9, 'blah', 'Cash', 890, '2026-04-25 06:30:25', '2026-04-25 06:30:25'),
(10, 'Beer', 'Cash', 500, '2026-05-15 12:43:04', '2026-05-15 12:43:04'),
(11, 'beer', 'Cash', 350, '2026-05-15 16:38:05', '2026-05-15 16:38:05'),
(12, 'kalu', 'Bank', 300, '2026-05-16 08:18:12', '2026-05-16 08:18:12');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `cat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cost` float NOT NULL,
  `min_stock` decimal(10,2) DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `index_no` int(11) DEFAULT 0,
  `to_make` int(11) NOT NULL DEFAULT 2,
  `type` enum('raw_material','finished_product','menu_item') DEFAULT 'raw_material',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `qty` decimal(10,2) NOT NULL DEFAULT 5.00,
  `sup_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `unit_id`, `cat_id`, `price`, `cost`, `min_stock`, `is_active`, `index_no`, `to_make`, `type`, `created_at`, `updated_at`, `qty`, `sup_id`) VALUES
(12, 'Coca Cola', 5, 13, 100.00, 50, 10.00, 1, 1, 2, 'raw_material', '2026-04-01 05:42:41', '2026-04-25 06:48:41', 0.00, NULL),
(13, 'Onion', 2, 14, 0.00, 100, 1.00, 1, 0, 2, 'raw_material', '2026-04-01 05:57:41', '2026-04-01 07:02:49', 0.00, NULL),
(14, 'Bread', 4, 14, 0.00, 15, 30.00, 1, 0, 2, 'raw_material', '2026-04-01 05:59:26', '2026-04-01 07:02:57', 20.00, NULL),
(15, 'Tomato', 2, 14, 0.00, 120, 1.00, 1, 0, 2, 'raw_material', '2026-04-01 05:59:47', '2026-04-01 07:03:03', 30.00, NULL),
(16, 'Kachup', 1, 14, 0.00, 400, 4.00, 1, 0, 2, 'raw_material', '2026-04-01 06:00:35', '2026-04-01 07:03:17', 30.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kitchen_stats`
--

CREATE TABLE `kitchen_stats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `table_no` varchar(20) DEFAULT NULL,
  `total_items` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `on_time_items` int(10) UNSIGNED DEFAULT 0,
  `late_items` int(10) UNSIGNED DEFAULT 0,
  `chef_name` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `finished_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kitchen_stats`
--

INSERT INTO `kitchen_stats` (`id`, `order_number`, `table_no`, `total_items`, `on_time_items`, `late_items`, `chef_name`, `date_created`, `created_at`, `updated_at`, `finished_at`) VALUES
(100, 'ORD-260401-L3LP', NULL, 1, 0, 0, 'admin', '2026-04-01', '2026-04-01 06:29:03', '2026-04-01 06:29:03', NULL),
(101, 'ORD-260401-M6R6', NULL, 1, 1, 0, 'admin', '2026-04-01', '2026-04-01 07:06:51', '2026-04-01 07:06:57', '2026-04-01 07:06:57'),
(102, 'ORD-260401-KG4C', NULL, 1, 1, 0, 'admin', '2026-04-01', '2026-04-01 07:06:52', '2026-04-01 07:06:59', '2026-04-01 07:06:59'),
(103, 'ORD-260401-LZX1', NULL, 1, 1, 0, 'admin', '2026-04-01', '2026-04-01 18:19:15', '2026-04-01 18:19:27', '2026-04-01 18:19:27'),
(104, 'ORD-260401-QCQX', NULL, 1, 0, 1, 'admin', '2026-04-02', '2026-04-02 03:53:59', '2026-04-02 03:54:04', '2026-04-02 03:54:04'),
(105, 'ORD-260402-BCOX', NULL, 2, 1, 1, 'admin', '2026-04-02', '2026-04-02 03:54:01', '2026-04-02 03:54:06', '2026-04-02 03:54:06'),
(106, 'ORD-260409-ZCQ4', NULL, 2, 0, 2, 'admin', '2026-04-15', '2026-04-15 11:35:17', '2026-04-15 11:35:23', '2026-04-15 11:35:23'),
(107, 'ORD-260409-NRB3', NULL, 2, 0, 2, 'admin', '2026-04-15', '2026-04-15 11:35:17', '2026-04-15 16:59:04', '2026-04-15 16:59:04'),
(108, 'ORD-260415-WVKU', NULL, 2, 0, 2, 'admin', '2026-04-15', '2026-04-15 11:35:25', '2026-04-15 16:59:05', '2026-04-15 16:59:05'),
(109, 'ORD-260415-9C2V', NULL, 2, 0, 2, 'admin', '2026-04-15', '2026-04-15 16:59:01', '2026-04-15 16:59:07', '2026-04-15 16:59:07'),
(110, 'ORD-260415-LO0O', NULL, 2, 0, 2, 'admin', '2026-04-15', '2026-04-15 16:59:02', '2026-04-15 16:59:08', '2026-04-15 16:59:08'),
(111, 'ORD-260415-PMWE', NULL, 2, 1, 1, 'admin', '2026-04-15', '2026-04-15 17:00:26', '2026-04-15 17:01:07', '2026-04-15 17:01:07'),
(112, 'ORD-260507-UGOP', NULL, 2, 0, 2, 'admin', '2026-05-15', '2026-05-15 11:57:04', '2026-05-15 11:57:25', '2026-05-15 11:57:25'),
(113, 'ORD-260425-DDY8', NULL, 3, 0, 3, 'admin', '2026-05-15', '2026-05-15 11:57:05', '2026-05-15 11:57:26', '2026-05-15 11:57:26'),
(114, 'ORD-260422-GBT2', NULL, 2, 0, 2, 'admin', '2026-05-15', '2026-05-15 11:57:07', '2026-05-15 11:57:27', '2026-05-15 11:57:27'),
(115, 'ORD-260423-O3AG', NULL, 2, 0, 2, 'admin', '2026-05-15', '2026-05-15 11:57:19', '2026-05-15 11:57:29', '2026-05-15 11:57:29'),
(116, 'ORD-260425-QJVG', NULL, 2, 0, 2, 'admin', '2026-05-15', '2026-05-15 11:57:22', '2026-05-15 11:57:30', '2026-05-15 11:57:30'),
(117, 'ORD-260515-YSMA', NULL, 2, 1, 1, 'admin', '2026-05-15', '2026-05-15 11:57:24', '2026-05-15 11:57:31', '2026-05-15 11:57:31'),
(118, 'ORD-260515-KGCM', NULL, 1, 0, 1, 'admin', '2026-05-15', '2026-05-15 12:05:13', '2026-05-15 12:13:16', '2026-05-15 12:13:16'),
(119, 'ORD-260515-RMM0', NULL, 1, 1, 0, 'admin', '2026-05-15', '2026-05-15 12:13:19', '2026-05-15 12:13:21', '2026-05-15 12:13:21'),
(120, 'ORD-260515-BYFO', NULL, 2, 0, 2, 'admin', '2026-05-16', '2026-05-16 08:16:33', '2026-05-16 08:16:37', '2026-05-16 08:16:37'),
(121, 'ORD-260516-TIIZ', NULL, 5, 0, 5, 'admin', '2026-05-16', '2026-05-16 08:16:35', '2026-05-16 08:16:38', '2026-05-16 08:16:38'),
(122, 'ORD-260516-GH1F', NULL, 2, 1, 1, 'admin', '2026-05-16', '2026-05-16 08:17:04', '2026-05-16 08:17:33', '2026-05-16 08:17:33');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('storage','main','consumption') DEFAULT 'storage',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Main Store', 'main', '2026-03-18 18:00:24', '2026-03-19 10:40:39'),
(2, 'Kitchen', 'consumption', '2026-03-18 18:00:24', '2026-03-18 18:00:24'),
(3, 'Bar', 'consumption', '2026-03-18 18:00:24', '2026-03-18 18:00:24');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `cat_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` int(11) NOT NULL,
  `price` int(100) NOT NULL DEFAULT 0,
  `prep_time` int(10) UNSIGNED NOT NULL DEFAULT 5,
  `index_no` int(11) NOT NULL DEFAULT 0,
  `to_make` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `cat_id`, `user_type`, `price`, `prep_time`, `index_no`, `to_make`, `created_at`, `updated_at`) VALUES
(21, 'Special Burger', 11, 6, 900, 10, 1, 1, '2026-04-01 05:46:59', '2026-04-01 05:54:51'),
(22, 'Burger', 11, 6, 700, 8, 2, 1, '2026-04-01 05:47:22', '2026-04-01 05:47:22'),
(23, 'Special Pizza', 12, 6, 1000, 10, 1, 1, '2026-04-01 05:47:49', '2026-04-01 05:47:49'),
(24, 'Margarita Pizza', 12, 6, 1200, 10, 2, 1, '2026-04-01 05:48:16', '2026-04-01 05:54:58');

-- --------------------------------------------------------

--
-- Table structure for table `menu_ingredients`
--

CREATE TABLE `menu_ingredients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_ingredients`
--

INSERT INTO `menu_ingredients` (`id`, `menu_id`, `item_id`, `quantity`, `created_at`, `updated_at`) VALUES
(41, 21, 13, 0.20, '2026-04-01 07:05:18', '2026-04-01 07:05:18'),
(42, 21, 14, 1.00, '2026-04-01 07:05:18', '2026-04-01 07:05:18'),
(43, 21, 15, 0.30, '2026-04-01 07:05:18', '2026-04-01 07:05:18'),
(44, 21, 16, 0.40, '2026-04-01 07:05:18', '2026-04-01 07:05:18'),
(45, 22, 13, 0.10, '2026-04-15 16:55:17', '2026-04-15 16:55:17'),
(46, 22, 14, 1.00, '2026-04-15 16:55:17', '2026-04-15 16:55:17'),
(47, 22, 15, 0.30, '2026-04-15 16:55:17', '2026-04-15 16:55:17'),
(48, 22, 16, 0.50, '2026-04-15 16:55:17', '2026-04-15 16:55:17');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2026_02_04_065748_user', 1),
(3, '2026_02_08_115632_create_cats_table', 2),
(4, '2026_02_08_130907_create_menu_table', 3),
(5, '2026_02_13_164000_create_unit_table', 4),
(6, '2026_02_19_074023_create_orders_table', 5),
(7, '2026_02_19_075422_create_order_items_table', 5),
(8, '2026_03_25_141233_create_menu_ingredients_table', 6),
(9, '2026_03_25_144236_create_stocks_table', 7),
(10, '2026_03_29_114252_create_expense_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `table_no` varchar(255) DEFAULT NULL,
  `order_type` enum('dine_in','takeaway') NOT NULL DEFAULT 'dine_in',
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_flagged` tinyint(1) DEFAULT 0,
  `is_billed` tinyint(1) NOT NULL DEFAULT 0,
  `payment_status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `waiter_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'new',
  `special_instructions` text DEFAULT NULL,
  `is_urgent` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `table_no`, `order_type`, `total_amount`, `is_flagged`, `is_billed`, `payment_status`, `created_at`, `updated_at`, `waiter_id`, `status`, `special_instructions`, `is_urgent`) VALUES
(239, 'ORD-260401-KG4C', 6, '5', 'dine_in', 700.00, 0, 1, 1, '2026-04-01 07:06:04', '2026-04-01 07:08:57', 12, 'new', NULL, 0),
(240, 'ORD-260401-M6R6', 6, '3', 'dine_in', 900.00, 0, 1, 1, '2026-04-01 07:06:32', '2026-04-01 07:09:11', 13, 'new', NULL, 0),
(241, 'ORD-260401-LZX1', 6, '4', 'dine_in', 3600.00, 0, 1, 1, '2026-04-01 18:13:42', '2026-04-01 18:14:34', 13, 'new', NULL, 0),
(242, 'ORD-260401-QCQX', 6, '78', 'dine_in', 900.00, 0, 1, 1, '2026-04-01 18:20:49', '2026-04-01 18:24:37', 12, 'new', NULL, 0),
(243, 'ORD-260402-BCOX', 6, '6', 'dine_in', 8300.00, 0, 1, 1, '2026-04-02 03:53:51', '2026-04-02 03:54:45', 12, 'new', NULL, 0),
(244, 'ORD-260409-NRB3', 6, '7', 'dine_in', 15000.00, 0, 1, 1, '2026-04-09 07:16:22', '2026-04-09 07:17:57', 13, 'new', NULL, 0),
(245, 'ORD-260409-ZCQ4', 6, '5', 'dine_in', 5600.00, 0, 1, 1, '2026-04-09 07:16:43', '2026-04-09 07:17:33', 13, 'new', NULL, 0),
(246, 'ORD-260415-LO0O', 6, '5', 'dine_in', 4100.00, 0, 1, 1, '2026-04-15 11:32:53', '2026-04-15 11:34:56', 13, 'new', NULL, 0),
(247, 'ORD-260415-WVKU', 6, '6', 'dine_in', 4500.00, 0, 1, 1, '2026-04-15 11:33:21', '2026-04-15 11:35:08', 12, 'new', NULL, 0),
(248, 'ORD-260415-9C2V', 6, '5', 'dine_in', 4400.00, 0, 1, 1, '2026-04-15 16:46:21', '2026-04-15 16:48:30', 13, 'new', NULL, 0),
(249, 'ORD-260415-PMWE', 6, '1', 'dine_in', 5000.00, 0, 1, 1, '2026-04-15 16:59:25', '2026-04-23 10:29:11', 12, 'new', NULL, 0),
(250, 'ORD-260422-GBT2', 6, '2', 'dine_in', 4300.00, 0, 1, 1, '2026-04-22 07:24:50', '2026-04-23 10:30:31', 12, 'new', NULL, 0),
(251, 'ORD-260423-O3AG', 6, '5', 'dine_in', 2900.00, 0, 1, 1, '2026-04-23 10:32:03', '2026-04-23 10:32:57', 12, 'new', NULL, 0),
(252, 'ORD-260425-DDY8', 6, '23', 'dine_in', 3500.00, 0, 1, 1, '2026-04-25 06:09:12', '2026-04-25 06:29:53', 13, 'new', NULL, 0),
(253, 'ORD-260425-QJVG', 6, '33', 'dine_in', 3200.00, 0, 1, 1, '2026-04-25 08:58:46', '2026-04-25 08:59:36', 13, 'new', NULL, 0),
(254, 'ORD-260507-UGOP', 6, '8', 'dine_in', 1600.00, 0, 1, 1, '2026-05-07 08:44:16', '2026-05-07 08:45:00', 12, 'new', NULL, 0),
(255, 'ORD-260515-YSMA', 6, '5', 'dine_in', 1200.00, 0, 1, 1, '2026-05-15 11:54:49', '2026-05-15 12:23:38', 12, 'new', NULL, 0),
(256, 'ORD-260515-RMM0', 6, '1', 'dine_in', 900.00, 0, 1, 1, '2026-05-15 12:04:41', '2026-05-15 12:23:21', 12, 'new', NULL, 0),
(257, 'ORD-260515-KGCM', 6, '6', 'dine_in', 700.00, 0, 1, 1, '2026-05-15 12:04:52', '2026-05-15 12:23:09', 12, 'new', NULL, 0),
(258, 'ORD-260515-BYFO', 6, '6', 'dine_in', 3600.00, 0, 1, 1, '2026-05-15 16:36:54', '2026-05-15 16:37:38', 13, 'new', NULL, 0),
(259, 'ORD-260516-TIIZ', 6, '90', 'dine_in', 4300.00, 0, 1, 1, '2026-05-16 05:44:51', '2026-05-16 05:45:36', 12, 'new', NULL, 0),
(260, 'ORD-260516-GH1F', 6, '78', 'dine_in', 6100.00, 0, 1, 1, '2026-05-16 08:16:56', '2026-05-16 08:17:24', 12, 'new', NULL, 0),
(261, 'ORD-260516-IQGR', 6, '77', 'dine_in', 2600.00, 0, 1, 1, '2026-05-16 14:50:34', '2026-05-16 14:51:10', 15, 'new', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_type` enum('menu','item') NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `cost` decimal(10,2) DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) DEFAULT 0.00,
  `profit` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `send_to_kitchen` tinyint(1) DEFAULT NULL,
  `to_make` int(11) DEFAULT NULL,
  `is_flagged` int(11) NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `prep_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `name`, `product_id`, `product_type`, `quantity`, `unit_price`, `cost`, `subtotal`, `total_cost`, `profit`, `created_at`, `updated_at`, `send_to_kitchen`, `to_make`, `is_flagged`, `status`, `prep_time`) VALUES
(464, 239, 'Burger', 22, 'menu', 1, 700.00, 0.00, 700.00, 0.00, 700.00, '2026-04-01 07:06:04', '2026-04-01 07:07:00', NULL, 1, 0, 'served', 8),
(465, 240, 'Special Burger', 21, 'menu', 1, 900.00, 231.00, 900.00, 231.00, 669.00, '2026-04-01 07:06:32', '2026-04-01 07:06:59', NULL, 1, 0, 'served', 10),
(466, 241, 'Special Burger', 21, 'menu', 4, 900.00, 231.00, 3600.00, 924.00, 2676.00, '2026-04-01 18:13:42', '2026-04-01 18:20:14', NULL, 1, 0, 'served', 10),
(467, 242, 'Special Burger', 21, 'menu', 1, 900.00, 231.00, 900.00, 231.00, 669.00, '2026-04-01 18:20:49', '2026-04-02 03:54:07', NULL, 1, 0, 'served', 10),
(468, 243, 'Special Burger', 21, 'menu', 8, 900.00, 231.00, 7200.00, 1848.00, 5352.00, '2026-04-02 03:53:51', '2026-04-02 03:54:08', NULL, 1, 0, 'served', 10),
(469, 243, 'Coca Cola', 12, 'item', 11, 100.00, 50.00, 1100.00, 550.00, 550.00, '2026-04-02 03:53:51', '2026-04-02 03:53:51', NULL, 2, 0, 'pending', NULL),
(470, 244, 'Special Burger', 21, 'menu', 14, 900.00, 231.00, 12600.00, 3234.00, 9366.00, '2026-04-09 07:16:22', '2026-04-15 17:02:06', NULL, 1, 0, 'served', 10),
(471, 244, 'Coca Cola', 12, 'item', 24, 100.00, 50.00, 2400.00, 1200.00, 1200.00, '2026-04-09 07:16:22', '2026-04-09 07:16:22', NULL, 2, 0, 'pending', NULL),
(472, 245, 'Special Burger', 21, 'menu', 4, 900.00, 231.00, 3600.00, 924.00, 2676.00, '2026-04-09 07:16:43', '2026-04-15 17:01:40', NULL, 1, 0, 'served', 10),
(473, 245, 'Coca Cola', 12, 'item', 20, 100.00, 50.00, 2000.00, 1000.00, 1000.00, '2026-04-09 07:16:43', '2026-04-09 07:16:43', NULL, 2, 0, 'pending', NULL),
(474, 246, 'Coca Cola', 12, 'item', 5, 100.00, 50.00, 500.00, 250.00, 250.00, '2026-04-15 11:32:53', '2026-04-15 11:32:53', NULL, 2, 0, 'pending', NULL),
(475, 246, 'Special Burger', 21, 'menu', 4, 900.00, 231.00, 3600.00, 924.00, 2676.00, '2026-04-15 11:32:53', '2026-04-15 17:02:09', NULL, 1, 0, 'served', 10),
(476, 247, 'Special Burger', 21, 'menu', 4, 900.00, 231.00, 3600.00, 924.00, 2676.00, '2026-04-15 11:33:21', '2026-04-15 17:02:10', NULL, 1, 0, 'served', 10),
(477, 247, 'Coca Cola', 12, 'item', 9, 100.00, 50.00, 900.00, 450.00, 450.00, '2026-04-15 11:33:21', '2026-04-15 11:33:21', NULL, 2, 0, 'pending', NULL),
(478, 248, 'Special Burger', 21, 'menu', 3, 900.00, 231.00, 2700.00, 693.00, 2007.00, '2026-04-15 16:46:21', '2026-04-15 17:02:17', NULL, 1, 0, 'served', 10),
(479, 248, 'Coca Cola', 12, 'item', 17, 100.00, 50.00, 1700.00, 850.00, 850.00, '2026-04-15 16:46:21', '2026-04-15 16:46:21', NULL, 2, 0, 'pending', NULL),
(480, 249, 'Special Burger', 21, 'menu', 5, 900.00, 231.00, 4500.00, 1155.00, 3345.00, '2026-04-15 16:59:25', '2026-04-15 17:02:17', NULL, 1, 0, 'served', 10),
(481, 249, 'Coca Cola', 12, 'item', 5, 100.00, 50.00, 500.00, 250.00, 250.00, '2026-04-15 16:59:25', '2026-04-15 16:59:25', NULL, 2, 0, 'pending', NULL),
(482, 250, 'Special Burger', 21, 'menu', 4, 900.00, 231.00, 3600.00, 924.00, 2676.00, '2026-04-22 07:24:51', '2026-05-15 11:57:34', NULL, 1, 0, 'served', 10),
(483, 250, 'Coca Cola', 12, 'item', 7, 100.00, 50.00, 700.00, 350.00, 350.00, '2026-04-22 07:24:51', '2026-04-22 07:24:51', NULL, 2, 0, 'pending', NULL),
(484, 251, 'Special Burger', 21, 'menu', 3, 900.00, 231.00, 2700.00, 693.00, 2007.00, '2026-04-23 10:32:03', '2026-05-15 11:57:35', NULL, 1, 0, 'served', 10),
(485, 251, 'Coca Cola', 12, 'item', 2, 100.00, 50.00, 200.00, 100.00, 100.00, '2026-04-23 10:32:03', '2026-04-23 10:32:03', NULL, 2, 0, 'pending', NULL),
(486, 252, 'Special Burger', 21, 'menu', 2, 900.00, 231.00, 1800.00, 462.00, 1338.00, '2026-04-25 06:09:12', '2026-05-15 11:57:33', NULL, 1, 0, 'served', 10),
(487, 252, 'Burger', 22, 'menu', 1, 700.00, 261.00, 700.00, 261.00, 439.00, '2026-04-25 06:09:12', '2026-05-15 11:57:33', NULL, 1, 0, 'served', 8),
(488, 252, 'Special Pizza', 23, 'menu', 1, 1000.00, 0.00, 1000.00, 0.00, 1000.00, '2026-04-25 06:09:12', '2026-05-15 11:57:33', NULL, 1, 0, 'served', 10),
(489, 253, 'Special Burger', 21, 'menu', 2, 900.00, 231.00, 1800.00, 462.00, 1338.00, '2026-04-25 08:58:46', '2026-05-15 11:57:36', NULL, 1, 0, 'served', 10),
(490, 253, 'Burger', 22, 'menu', 2, 700.00, 261.00, 1400.00, 522.00, 878.00, '2026-04-25 08:58:46', '2026-05-15 11:57:36', NULL, 1, 0, 'served', 8),
(491, 254, 'Special Burger', 21, 'menu', 1, 900.00, 231.00, 900.00, 231.00, 669.00, '2026-05-07 08:44:16', '2026-05-15 11:57:32', NULL, 1, 0, 'served', 10),
(492, 254, 'Burger', 22, 'menu', 1, 700.00, 261.00, 700.00, 261.00, 439.00, '2026-05-07 08:44:16', '2026-05-15 11:57:32', NULL, 1, 0, 'served', 8),
(493, 255, 'Special Burger', 21, 'menu', 1, 900.00, 231.00, 900.00, 231.00, 669.00, '2026-05-15 11:54:49', '2026-05-15 11:57:37', NULL, 1, 0, 'served', 10),
(494, 255, 'Coca Cola', 12, 'item', 3, 100.00, 50.00, 300.00, 150.00, 150.00, '2026-05-15 11:54:49', '2026-05-15 11:54:49', NULL, 2, 0, 'pending', NULL),
(495, 256, 'Special Burger', 21, 'menu', 1, 900.00, 231.00, 900.00, 231.00, 669.00, '2026-05-15 12:04:41', '2026-05-15 12:13:23', NULL, 1, 0, 'served', 10),
(496, 257, 'Burger', 22, 'menu', 1, 700.00, 261.00, 700.00, 261.00, 439.00, '2026-05-15 12:04:52', '2026-05-15 12:13:22', NULL, 1, 0, 'served', 8),
(497, 258, 'Coca Cola', 12, 'item', 9, 100.00, 50.00, 900.00, 450.00, 450.00, '2026-05-15 16:36:54', '2026-05-15 16:36:54', NULL, 2, 0, 'pending', NULL),
(498, 258, 'Special Burger', 21, 'menu', 3, 900.00, 231.00, 2700.00, 693.00, 2007.00, '2026-05-15 16:36:54', '2026-05-16 08:16:39', NULL, 1, 0, 'served', 10),
(499, 259, 'Special Burger', 21, 'menu', 1, 900.00, 231.00, 900.00, 231.00, 669.00, '2026-05-16 05:44:51', '2026-05-16 08:16:41', NULL, 1, 0, 'served', 10),
(500, 259, 'Burger', 22, 'menu', 1, 700.00, 261.00, 700.00, 261.00, 439.00, '2026-05-16 05:44:51', '2026-05-16 08:16:41', NULL, 1, 0, 'served', 8),
(501, 259, 'Margarita Pizza', 24, 'menu', 1, 1200.00, 0.00, 1200.00, 0.00, 1200.00, '2026-05-16 05:44:51', '2026-05-16 08:16:41', NULL, 1, 0, 'served', 10),
(502, 259, 'Special Pizza', 23, 'menu', 1, 1000.00, 0.00, 1000.00, 0.00, 1000.00, '2026-05-16 05:44:51', '2026-05-16 08:16:41', NULL, 1, 0, 'served', 10),
(503, 259, 'Coca Cola', 12, 'item', 5, 100.00, 50.00, 500.00, 250.00, 250.00, '2026-05-16 05:44:52', '2026-05-16 05:44:52', NULL, 2, 0, 'pending', NULL),
(504, 260, 'Coca Cola', 12, 'item', 7, 100.00, 50.00, 700.00, 350.00, 350.00, '2026-05-16 08:16:56', '2026-05-16 08:16:56', NULL, 2, 0, 'pending', NULL),
(505, 260, 'Special Burger', 21, 'menu', 6, 900.00, 231.00, 5400.00, 1386.00, 4014.00, '2026-05-16 08:16:56', '2026-05-16 08:17:34', NULL, 1, 0, 'served', 10),
(506, 261, 'Special Burger', 21, 'menu', 1, 900.00, 231.00, 900.00, 231.00, 669.00, '2026-05-16 14:50:34', '2026-05-16 14:50:34', NULL, 1, 0, 'pending', 10),
(507, 261, 'Burger', 22, 'menu', 1, 700.00, 261.00, 700.00, 261.00, 439.00, '2026-05-16 14:50:34', '2026-05-16 14:50:34', NULL, 1, 0, 'pending', 8),
(508, 261, 'Coca Cola', 12, 'item', 10, 100.00, 50.00, 1000.00, 500.00, 500.00, '2026-05-16 14:50:34', '2026-05-16 14:50:34', NULL, 2, 0, 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `method` enum('cash','bank') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` enum('in','out') DEFAULT 'in'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `method`, `amount`, `reference`, `created_at`, `updated_at`, `type`) VALUES
(75, 239, 'bank', 700.00, NULL, '2026-04-01 07:08:57', '2026-04-01 07:08:57', 'in'),
(76, 240, 'bank', 1000.00, NULL, '2026-04-01 07:09:10', '2026-04-01 07:09:10', 'in'),
(77, 240, 'cash', 100.00, NULL, '2026-04-01 07:09:10', '2026-04-01 07:09:10', 'out'),
(78, 241, 'cash', 3000.00, NULL, '2026-04-01 18:14:34', '2026-04-01 18:14:34', 'in'),
(79, 241, 'bank', 700.00, NULL, '2026-04-01 18:14:34', '2026-04-01 18:14:34', 'in'),
(80, 241, 'cash', 100.00, NULL, '2026-04-01 18:14:34', '2026-04-01 18:14:34', 'out'),
(81, 242, 'cash', 500.00, NULL, '2026-04-01 18:24:37', '2026-04-01 18:24:37', 'in'),
(82, 242, 'bank', 400.00, NULL, '2026-04-01 18:24:37', '2026-04-01 18:24:37', 'in'),
(83, 243, 'bank', 9000.00, NULL, '2026-04-02 03:54:45', '2026-04-02 03:54:45', 'in'),
(84, 243, 'cash', 700.00, NULL, '2026-04-02 03:54:45', '2026-04-02 03:54:45', 'out'),
(85, 245, 'bank', 5600.00, NULL, '2026-04-09 07:17:32', '2026-04-09 07:17:32', 'in'),
(86, 244, 'cash', 5000.00, NULL, '2026-04-09 07:17:57', '2026-04-09 07:17:57', 'in'),
(87, 244, 'bank', 11000.00, NULL, '2026-04-09 07:17:57', '2026-04-09 07:17:57', 'in'),
(88, 244, 'cash', 1000.00, NULL, '2026-04-09 07:17:57', '2026-04-09 07:17:57', 'out'),
(89, 246, 'cash', 3000.00, NULL, '2026-04-15 11:34:56', '2026-04-15 11:34:56', 'in'),
(90, 246, 'bank', 2000.00, NULL, '2026-04-15 11:34:56', '2026-04-15 11:34:56', 'in'),
(91, 246, 'cash', 900.00, NULL, '2026-04-15 11:34:56', '2026-04-15 11:34:56', 'out'),
(92, 247, 'bank', 5000.00, NULL, '2026-04-15 11:35:08', '2026-04-15 11:35:08', 'in'),
(93, 247, 'cash', 500.00, NULL, '2026-04-15 11:35:08', '2026-04-15 11:35:08', 'out'),
(94, 248, 'cash', 5000.00, NULL, '2026-04-15 16:48:30', '2026-04-15 16:48:30', 'in'),
(95, 248, 'cash', 600.00, NULL, '2026-04-15 16:48:30', '2026-04-15 16:48:30', 'out'),
(96, 249, 'bank', 6000.00, NULL, '2026-04-23 10:29:11', '2026-04-23 10:29:11', 'in'),
(97, 249, 'cash', 1000.00, NULL, '2026-04-23 10:29:11', '2026-04-23 10:29:11', 'out'),
(98, 250, 'cash', 4500.00, NULL, '2026-04-23 10:30:31', '2026-04-23 10:30:31', 'in'),
(99, 250, 'cash', 200.00, NULL, '2026-04-23 10:30:31', '2026-04-23 10:30:31', 'out'),
(100, 251, 'bank', 2900.00, NULL, '2026-04-23 10:32:57', '2026-04-23 10:32:57', 'in'),
(101, 252, 'bank', 4000.00, NULL, '2026-04-25 06:29:52', '2026-04-25 06:29:52', 'in'),
(102, 252, 'cash', 500.00, NULL, '2026-04-25 06:29:52', '2026-04-25 06:29:52', 'out'),
(103, 253, 'bank', 3200.00, NULL, '2026-04-25 08:59:35', '2026-04-25 08:59:35', 'in'),
(104, 254, 'cash', 1000.00, NULL, '2026-05-07 08:45:00', '2026-05-07 08:45:00', 'in'),
(105, 254, 'bank', 600.00, NULL, '2026-05-07 08:45:00', '2026-05-07 08:45:00', 'in'),
(106, 257, 'cash', 700.00, NULL, '2026-05-15 12:23:09', '2026-05-15 12:23:09', 'in'),
(107, 256, 'cash', 900.00, NULL, '2026-05-15 12:23:21', '2026-05-15 12:23:21', 'in'),
(108, 255, 'cash', 1000.00, NULL, '2026-05-15 12:23:38', '2026-05-15 12:23:38', 'in'),
(109, 255, 'bank', 200.00, NULL, '2026-05-15 12:23:38', '2026-05-15 12:23:38', 'in'),
(110, 258, 'cash', 600.00, NULL, '2026-05-15 16:37:38', '2026-05-15 16:37:38', 'in'),
(111, 258, 'bank', 3000.00, NULL, '2026-05-15 16:37:38', '2026-05-15 16:37:38', 'in'),
(112, 259, 'cash', 500.00, NULL, '2026-05-16 05:45:36', '2026-05-16 05:45:36', 'in'),
(113, 259, 'bank', 3800.00, NULL, '2026-05-16 05:45:36', '2026-05-16 05:45:36', 'in'),
(114, 260, 'bank', 6200.00, NULL, '2026-05-16 08:17:24', '2026-05-16 08:17:24', 'in'),
(115, 260, 'cash', 100.00, NULL, '2026-05-16 08:17:24', '2026-05-16 08:17:24', 'out'),
(116, 261, 'cash', 2000.00, NULL, '2026-05-16 14:51:10', '2026-05-16 14:51:10', 'in'),
(117, 261, 'bank', 600.00, NULL, '2026-05-16 14:51:10', '2026-05-16 14:51:10', 'in');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 6, 'mobile_app_token', 'ea4e47a47d65ddcbeab4e2eaebb1459fc63b4e932d2e8cc21c7a58f666a213f9', '[\"*\"]', NULL, NULL, '2026-04-21 19:30:42', '2026-04-21 19:30:42'),
(2, 'App\\Models\\User', 6, 'mobile_app_token', '7e79439b8783c5d59da2a4b656b67ba5311322b0a301b6493396538e8cfafc63', '[\"*\"]', NULL, NULL, '2026-04-21 19:35:06', '2026-04-21 19:35:06'),
(3, 'App\\Models\\User', 6, 'mobile_app_token', 'a8654a7f60b5990d9aec6a243b541a55e4a029aad931a052fc8134129036b0ca', '[\"*\"]', NULL, NULL, '2026-04-21 19:40:49', '2026-04-21 19:40:49'),
(4, 'App\\Models\\User', 6, 'mobile_app_token', '6f891ae74b8944747c02d3783bbfd7d2c0c6da17454e0f33546a8459f1cf0495', '[\"*\"]', NULL, NULL, '2026-04-21 19:47:56', '2026-04-21 19:47:56'),
(5, 'App\\Models\\User', 6, 'mobile_app_token', '439dee0cb809368c120a5616f103dbe655fbe67ad08b9f2cda640c4ecf58d59e', '[\"*\"]', NULL, NULL, '2026-04-21 20:01:06', '2026-04-21 20:01:06'),
(6, 'App\\Models\\User', 6, 'mobile_app_token', '1b5c0bc8d7ff650b7ef71f858ff36072903de2920f2bf9d948e340b1b282aa3b', '[\"*\"]', NULL, NULL, '2026-04-21 20:05:21', '2026-04-21 20:05:21'),
(7, 'App\\Models\\User', 6, 'mobile_app_token', '0dfc73e1c45fb4c9f3b8423479f6196f2e72e5cb247976b9d58188e4513e1655', '[\"*\"]', NULL, NULL, '2026-04-21 20:18:59', '2026-04-21 20:18:59'),
(8, 'App\\Models\\User', 6, 'mobile_app_token', 'f75d987b8e716f9147e68f26e6f8f9d1e7e5c466889740a95715bab69cad0cd1', '[\"*\"]', NULL, NULL, '2026-04-21 20:19:21', '2026-04-21 20:19:21'),
(9, 'App\\Models\\User', 6, 'mobile_app_token', '6730696507c11f5752113edd609512e1d3929a0bfdd000bf8753de647277b782', '[\"*\"]', NULL, NULL, '2026-04-21 20:19:50', '2026-04-21 20:19:50'),
(10, 'App\\Models\\User', 6, 'mobile_app_token', 'bac9cc88c55f092f6390e17d87697d5c341131f4d9f0202af240759113744132', '[\"*\"]', NULL, NULL, '2026-04-21 20:23:44', '2026-04-21 20:23:44'),
(11, 'App\\Models\\User', 6, 'mobile_app_token', 'a2f0db46a0ff2e5ffa9145d6170976285927e41cb145646aab734398f3fc8d7e', '[\"*\"]', NULL, NULL, '2026-04-21 20:30:21', '2026-04-21 20:30:21'),
(12, 'App\\Models\\User', 6, 'mobile_app_token', '6914dc54ee14772b4bed96c77285b7602a05ea16c55dc1dce6ae9413d52519b2', '[\"*\"]', NULL, NULL, '2026-04-22 06:30:39', '2026-04-22 06:30:39'),
(13, 'App\\Models\\User', 6, 'mobile_app_token', '8a648c98fdc3fdcbb7afe84bbb2a0dc72d6c316c08b014a8ac2d482d180eb889', '[\"*\"]', NULL, NULL, '2026-04-22 06:30:51', '2026-04-22 06:30:51'),
(14, 'App\\Models\\User', 6, 'mobile_app_token', '0bccf796fc5203e53ede4e8aa67140d542915eb707105d58458be9d9110632ba', '[\"*\"]', NULL, NULL, '2026-04-22 06:48:15', '2026-04-22 06:48:15'),
(15, 'App\\Models\\User', 6, 'mobile_app_token', '04910d804b5de1338a0059a6041eeb46eeab338343894bc515dee65e81a68a00', '[\"*\"]', NULL, NULL, '2026-04-22 06:55:31', '2026-04-22 06:55:31'),
(16, 'App\\Models\\User', 6, 'mobile_app_token', 'ec89c7525e42e0d986bdfee4213c007e7ab0e609958559e6f798260638e8c263', '[\"*\"]', NULL, NULL, '2026-04-22 06:57:05', '2026-04-22 06:57:05'),
(17, 'App\\Models\\User', 6, 'mobile_app_token', '507762f2304e97399cd3f3d697a2fce4fb6be516385a0b5aba8d3ccd9cf886b1', '[\"*\"]', NULL, NULL, '2026-04-22 07:05:33', '2026-04-22 07:05:33'),
(18, 'App\\Models\\User', 6, 'mobile_app_token', 'e84cfd46d2f07cb4ca668a381dfb67f035cccafba7fda439b3c3db45ba3a5d81', '[\"*\"]', NULL, NULL, '2026-04-22 07:06:35', '2026-04-22 07:06:35'),
(19, 'App\\Models\\User', 6, 'mobile_app_token', '988d2a2fa5de9ed689b04f72674a064b1ccc2a825554bc575d2aee8864aaf510', '[\"*\"]', NULL, NULL, '2026-04-23 10:37:21', '2026-04-23 10:37:21'),
(20, 'App\\Models\\User', 6, 'mobile_app_token', '7b56eee04b939933e6eba1d4b81ccd231a3a3677f379f87e170768484240a49f', '[\"*\"]', NULL, NULL, '2026-04-23 10:55:43', '2026-04-23 10:55:43'),
(21, 'App\\Models\\User', 6, 'mobile_app_token', 'db8e38a08f1e5db0d23250d9f4e046cd2c406a39f4d8d5677c1cd4cf3a78094a', '[\"*\"]', NULL, NULL, '2026-04-23 10:56:39', '2026-04-23 10:56:39'),
(22, 'App\\Models\\User', 6, 'mobile_app_token', 'c00392a5c1eb7ffe0a2aa42af13e7e14a5de6658fb45d7f6095a30b77ed6f77e', '[\"*\"]', NULL, NULL, '2026-04-23 10:58:38', '2026-04-23 10:58:38'),
(23, 'App\\Models\\User', 6, 'mobile_app_token', '1336493cdd4778179ebd79a7fa585fee81582c529559bc34ed5dd21a2d8c7e25', '[\"*\"]', NULL, NULL, '2026-04-23 11:04:11', '2026-04-23 11:04:11'),
(24, 'App\\Models\\User', 6, 'mobile_app_token', '41ad19ca46ca8c4b697ec3162bc8ad480220eb487fc00d66cdf53b0722e103ca', '[\"*\"]', NULL, NULL, '2026-04-23 11:13:49', '2026-04-23 11:13:49'),
(25, 'App\\Models\\User', 6, 'mobile_app_token', '073814f9f2c10d7818751c4e1410a8baa2d43c93e0e46c7205e400d97640c456', '[\"*\"]', NULL, NULL, '2026-04-23 11:21:04', '2026-04-23 11:21:04'),
(26, 'App\\Models\\User', 6, 'mobile_app_token', '46319fd0e6af70fc1775172b78149b784d6f4b027e8a03a73f2b05520380a42b', '[\"*\"]', NULL, NULL, '2026-04-23 11:32:27', '2026-04-23 11:32:27'),
(27, 'App\\Models\\User', 6, 'mobile_app_token', '92ddbe94b97b8653ff61acaabfdc2f3a0e7673be1de9a91659dd5cb5a3449f5b', '[\"*\"]', NULL, NULL, '2026-04-23 11:36:39', '2026-04-23 11:36:39'),
(28, 'App\\Models\\User', 6, 'mobile_app_token', 'c28ac6ef1c6109bf6fcd4b8f2a220a3854a32ca77b7e461c1c441e5b19c452ac', '[\"*\"]', NULL, NULL, '2026-04-25 06:13:21', '2026-04-25 06:13:21'),
(29, 'App\\Models\\User', 6, 'mobile_app_token', '986837459b3223ae267a2974c2c603e1b00d777853dbda04b567e30f4e771422', '[\"*\"]', NULL, NULL, '2026-04-25 08:06:47', '2026-04-25 08:06:47'),
(30, 'App\\Models\\User', 6, 'mobile_app_token', 'db813222999c02efe5c62e68d45e4928ddde4c2748eb511cc50047ca8ae7819a', '[\"*\"]', NULL, NULL, '2026-04-25 08:24:34', '2026-04-25 08:24:34'),
(31, 'App\\Models\\User', 6, 'mobile_app_token', '679724590830d6feb08576131052ad66399765388fe11a8be34d5d1f861cc0aa', '[\"*\"]', NULL, NULL, '2026-04-25 08:30:50', '2026-04-25 08:30:50'),
(32, 'App\\Models\\User', 6, 'mobile_app_token', 'b872c56eb229c49eca1ef89d238aea51c22b5851c86f34145cded35aa4e14078', '[\"*\"]', NULL, NULL, '2026-04-25 08:39:21', '2026-04-25 08:39:21'),
(33, 'App\\Models\\User', 6, 'mobile_app_token', '3feb22dc7ccad1c01653146966d4883e047a45a89eb631e11ae66867a92f5f42', '[\"*\"]', NULL, NULL, '2026-04-25 08:52:39', '2026-04-25 08:52:39'),
(34, 'App\\Models\\User', 6, 'mobile_app_token', 'cae7cfa7cc4c62df2265ad24e44a870720255e5b0a3cf5f360eae1efe234011f', '[\"*\"]', '2026-05-16 15:04:52', NULL, '2026-04-25 08:54:21', '2026-05-16 15:04:52'),
(35, 'App\\Models\\User', 6, 'mobile_app_token', '72b096ddf1da69286ccbb2286de71a4f70bc7455a65c46b56d3251451f47a23c', '[\"*\"]', NULL, NULL, '2026-05-16 05:44:12', '2026-05-16 05:44:12'),
(36, 'App\\Models\\User', 6, 'mobile_app_token', 'e82751b4a60109cd68b40a8735d211ded6dbcd93c814bf4ff8bf6d71caa9306d', '[\"*\"]', NULL, NULL, '2026-05-16 07:47:07', '2026-05-16 07:47:07'),
(37, 'App\\Models\\User', 6, 'mobile_app_token', '5b0e310d48d147365afdd2dd64c83d3f131c0e7cd924765995632fd61c72628b', '[\"*\"]', NULL, NULL, '2026-05-16 07:59:50', '2026-05-16 07:59:50'),
(38, 'App\\Models\\User', 6, 'mobile_app_token', '497283d38abbde596f2597a0ed7fbeb10589b79611bb2050ef57958682b9c735', '[\"*\"]', NULL, NULL, '2026-05-16 08:00:39', '2026-05-16 08:00:39'),
(39, 'App\\Models\\User', 6, 'mobile_app_token', '39dc178deaf6a70a1b99bec7fd6d0d49d465c4ee0413b8ceeca5cce348d945f1', '[\"*\"]', NULL, NULL, '2026-05-16 13:23:14', '2026-05-16 13:23:14'),
(40, 'App\\Models\\User', 6, 'mobile_app_token', '82d01332d9b9ebe1edde16c43bd73a461d70f71c0372f84e9caee4cf10b5d188', '[\"*\"]', NULL, NULL, '2026-05-16 13:30:30', '2026-05-16 13:30:30'),
(41, 'App\\Models\\User', 6, 'mobile_app_token', '6cba921b7b62600da9a4c28e62e695fbb0d09566996cb6c7316fa7c1d53f5bb6', '[\"*\"]', NULL, NULL, '2026-05-16 14:15:51', '2026-05-16 14:15:51'),
(42, 'App\\Models\\User', 6, 'mobile_app_token', '052c77e03b36638b92ac2502c022d4448d0194a68570d8bf3cfdd015b8df7e1a', '[\"*\"]', NULL, NULL, '2026-05-16 14:33:46', '2026-05-16 14:33:46'),
(43, 'App\\Models\\User', 6, 'mobile_app_token', '0bbd56647821a3e70081a3204bd653b3ac77a5b5ece8d9ca9312807e3d809e0d', '[\"*\"]', NULL, NULL, '2026-05-16 14:41:29', '2026-05-16 14:41:29'),
(44, 'App\\Models\\User', 6, 'mobile_app_token', 'ae9230d02d0094fdc423996a0886a155e52edb0be7de05be80df7ab113edb277', '[\"*\"]', NULL, NULL, '2026-05-16 14:43:13', '2026-05-16 14:43:13'),
(45, 'App\\Models\\User', 6, 'mobile_app_token', '27454b01026d83e3ff6937a6250a36cf767d251370eba4e5e9f6656ee0be9ee5', '[\"*\"]', NULL, NULL, '2026-05-16 14:49:44', '2026-05-16 14:49:44'),
(46, 'App\\Models\\User', 6, 'mobile_app_token', 'f86f81d6d25fb0d1b7f702fbadc6afd12522ab5447f054d85965b2794d228061', '[\"*\"]', NULL, NULL, '2026-05-16 14:58:24', '2026-05-16 14:58:24');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('IN','OUT') NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `direction` enum('IN','OUT') NOT NULL,
  `reason` varchar(100) NOT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `item_id`, `location_id`, `type`, `quantity`, `direction`, `reason`, `supplier_id`, `created_at`, `updated_at`) VALUES
(269, 13, 1, 'IN', 10.00, 'OUT', 'transfer', NULL, '2026-04-01 07:02:49', '2026-04-01 07:02:49'),
(270, 13, 2, 'IN', 10.00, 'IN', 'transfer', NULL, '2026-04-01 07:02:49', '2026-04-01 07:02:49'),
(271, 14, 1, 'IN', 10.00, 'OUT', 'transfer', NULL, '2026-04-01 07:02:57', '2026-04-01 07:02:57'),
(272, 14, 2, 'IN', 10.00, 'IN', 'transfer', NULL, '2026-04-01 07:02:57', '2026-04-01 07:02:57'),
(273, 15, 1, 'IN', 10.00, 'OUT', 'transfer', NULL, '2026-04-01 07:03:03', '2026-04-01 07:03:03'),
(274, 15, 2, 'IN', 10.00, 'IN', 'transfer', NULL, '2026-04-01 07:03:03', '2026-04-01 07:03:03'),
(275, 16, 1, 'IN', 10.00, 'OUT', 'transfer', NULL, '2026-04-01 07:03:17', '2026-04-01 07:03:17'),
(276, 16, 2, 'IN', 10.00, 'IN', 'transfer', NULL, '2026-04-01 07:03:17', '2026-04-01 07:03:17'),
(277, 13, 2, 'IN', 0.20, 'OUT', 'Order_Food', NULL, '2026-04-01 07:06:32', '2026-04-01 07:06:32'),
(278, 14, 2, 'IN', 1.00, 'OUT', 'Order_Food', NULL, '2026-04-01 07:06:32', '2026-04-01 07:06:32'),
(279, 15, 2, 'IN', 0.30, 'OUT', 'Order_Food', NULL, '2026-04-01 07:06:32', '2026-04-01 07:06:32'),
(280, 16, 2, 'IN', 0.40, 'OUT', 'Order_Food', NULL, '2026-04-01 07:06:32', '2026-04-01 07:06:32'),
(281, 13, 2, 'IN', 0.80, 'OUT', 'Order_Food', NULL, '2026-04-01 18:13:42', '2026-04-01 18:13:42'),
(282, 14, 2, 'IN', 4.00, 'OUT', 'Order_Food', NULL, '2026-04-01 18:13:42', '2026-04-01 18:13:42'),
(283, 15, 2, 'IN', 1.20, 'OUT', 'Order_Food', NULL, '2026-04-01 18:13:42', '2026-04-01 18:13:42'),
(284, 16, 2, 'IN', 1.60, 'OUT', 'Order_Food', NULL, '2026-04-01 18:13:42', '2026-04-01 18:13:42'),
(285, 13, 2, 'IN', 0.20, 'OUT', 'Order_Food', NULL, '2026-04-01 18:20:50', '2026-04-01 18:20:50'),
(286, 14, 2, 'IN', 1.00, 'OUT', 'Order_Food', NULL, '2026-04-01 18:20:50', '2026-04-01 18:20:50'),
(287, 15, 2, 'IN', 0.30, 'OUT', 'Order_Food', NULL, '2026-04-01 18:20:50', '2026-04-01 18:20:50'),
(288, 16, 2, 'IN', 0.40, 'OUT', 'Order_Food', NULL, '2026-04-01 18:20:50', '2026-04-01 18:20:50'),
(289, 12, 1, 'IN', 100.00, 'OUT', 'transfer', NULL, '2026-04-02 03:53:19', '2026-04-02 03:53:19'),
(290, 12, 3, 'IN', 100.00, 'IN', 'transfer', NULL, '2026-04-02 03:53:19', '2026-04-02 03:53:19'),
(291, 13, 2, 'IN', 1.60, 'OUT', 'Order_Food', NULL, '2026-04-02 03:53:51', '2026-04-02 03:53:51'),
(292, 14, 2, 'IN', 8.00, 'OUT', 'Order_Food', NULL, '2026-04-02 03:53:51', '2026-04-02 03:53:51'),
(293, 15, 2, 'IN', 2.40, 'OUT', 'Order_Food', NULL, '2026-04-02 03:53:51', '2026-04-02 03:53:51'),
(294, 16, 2, 'IN', 3.20, 'OUT', 'Order_Food', NULL, '2026-04-02 03:53:51', '2026-04-02 03:53:51'),
(295, 12, 3, 'IN', 11.00, 'OUT', 'Order_Drink', NULL, '2026-04-02 03:53:51', '2026-04-02 03:53:51'),
(296, 13, 2, 'IN', 2.80, 'OUT', 'Order_Food', NULL, '2026-04-09 07:16:22', '2026-04-09 07:16:22'),
(297, 14, 2, 'IN', 14.00, 'OUT', 'Order_Food', NULL, '2026-04-09 07:16:22', '2026-04-09 07:16:22'),
(298, 15, 2, 'IN', 4.20, 'OUT', 'Order_Food', NULL, '2026-04-09 07:16:22', '2026-04-09 07:16:22'),
(299, 16, 2, 'IN', 5.60, 'OUT', 'Order_Food', NULL, '2026-04-09 07:16:22', '2026-04-09 07:16:22'),
(300, 12, 3, 'IN', 24.00, 'OUT', 'Order_Drink', NULL, '2026-04-09 07:16:22', '2026-04-09 07:16:22'),
(301, 13, 2, 'IN', 0.80, 'OUT', 'Order_Food', NULL, '2026-04-09 07:16:43', '2026-04-09 07:16:43'),
(302, 14, 2, 'IN', 4.00, 'OUT', 'Order_Food', NULL, '2026-04-09 07:16:43', '2026-04-09 07:16:43'),
(303, 15, 2, 'IN', 1.20, 'OUT', 'Order_Food', NULL, '2026-04-09 07:16:43', '2026-04-09 07:16:43'),
(304, 16, 2, 'IN', 1.60, 'OUT', 'Order_Food', NULL, '2026-04-09 07:16:43', '2026-04-09 07:16:43'),
(305, 12, 3, 'IN', 20.00, 'OUT', 'Order_Drink', NULL, '2026-04-09 07:16:43', '2026-04-09 07:16:43'),
(306, 12, 3, 'IN', 5.00, 'OUT', 'Order_Drink', NULL, '2026-04-15 11:32:53', '2026-04-15 11:32:53'),
(307, 13, 2, 'IN', 0.80, 'OUT', 'Order_Food', NULL, '2026-04-15 11:32:53', '2026-04-15 11:32:53'),
(308, 14, 2, 'IN', 4.00, 'OUT', 'Order_Food', NULL, '2026-04-15 11:32:53', '2026-04-15 11:32:53'),
(309, 15, 2, 'IN', 1.20, 'OUT', 'Order_Food', NULL, '2026-04-15 11:32:53', '2026-04-15 11:32:53'),
(310, 16, 2, 'IN', 1.60, 'OUT', 'Order_Food', NULL, '2026-04-15 11:32:53', '2026-04-15 11:32:53'),
(311, 13, 2, 'IN', 0.80, 'OUT', 'Order_Food', NULL, '2026-04-15 11:33:21', '2026-04-15 11:33:21'),
(312, 14, 2, 'IN', 4.00, 'OUT', 'Order_Food', NULL, '2026-04-15 11:33:21', '2026-04-15 11:33:21'),
(313, 15, 2, 'IN', 1.20, 'OUT', 'Order_Food', NULL, '2026-04-15 11:33:21', '2026-04-15 11:33:21'),
(314, 16, 2, 'IN', 1.60, 'OUT', 'Order_Food', NULL, '2026-04-15 11:33:21', '2026-04-15 11:33:21'),
(315, 12, 3, 'IN', 9.00, 'OUT', 'Order_Drink', NULL, '2026-04-15 11:33:21', '2026-04-15 11:33:21'),
(316, 13, 2, 'IN', 0.60, 'OUT', 'Order_Food', NULL, '2026-04-15 16:46:21', '2026-04-15 16:46:21'),
(317, 14, 2, 'IN', 3.00, 'OUT', 'Order_Food', NULL, '2026-04-15 16:46:21', '2026-04-15 16:46:21'),
(318, 15, 2, 'IN', 0.90, 'OUT', 'Order_Food', NULL, '2026-04-15 16:46:21', '2026-04-15 16:46:21'),
(319, 16, 2, 'IN', 1.20, 'OUT', 'Order_Food', NULL, '2026-04-15 16:46:21', '2026-04-15 16:46:21'),
(320, 12, 3, 'IN', 17.00, 'OUT', 'Order_Drink', NULL, '2026-04-15 16:46:21', '2026-04-15 16:46:21'),
(321, 13, 2, 'IN', 1.00, 'OUT', 'Order_Food', NULL, '2026-04-15 16:59:25', '2026-04-15 16:59:25'),
(322, 14, 2, 'IN', 5.00, 'OUT', 'Order_Food', NULL, '2026-04-15 16:59:25', '2026-04-15 16:59:25'),
(323, 15, 2, 'IN', 1.50, 'OUT', 'Order_Food', NULL, '2026-04-15 16:59:25', '2026-04-15 16:59:25'),
(324, 16, 2, 'IN', 2.00, 'OUT', 'Order_Food', NULL, '2026-04-15 16:59:25', '2026-04-15 16:59:25'),
(325, 12, 3, 'IN', 5.00, 'OUT', 'Order_Drink', NULL, '2026-04-15 16:59:25', '2026-04-15 16:59:25'),
(326, 13, 2, 'IN', 0.80, 'OUT', 'Order_Food', NULL, '2026-04-22 07:24:51', '2026-04-22 07:24:51'),
(327, 14, 2, 'IN', 4.00, 'OUT', 'Order_Food', NULL, '2026-04-22 07:24:51', '2026-04-22 07:24:51'),
(328, 15, 2, 'IN', 1.20, 'OUT', 'Order_Food', NULL, '2026-04-22 07:24:51', '2026-04-22 07:24:51'),
(329, 16, 2, 'IN', 1.60, 'OUT', 'Order_Food', NULL, '2026-04-22 07:24:51', '2026-04-22 07:24:51'),
(330, 12, 3, 'IN', 7.00, 'OUT', 'Order_Drink', NULL, '2026-04-22 07:24:51', '2026-04-22 07:24:51'),
(331, 13, 2, 'IN', 0.60, 'OUT', 'Order_Food', NULL, '2026-04-23 10:32:03', '2026-04-23 10:32:03'),
(332, 14, 2, 'IN', 3.00, 'OUT', 'Order_Food', NULL, '2026-04-23 10:32:03', '2026-04-23 10:32:03'),
(333, 15, 2, 'IN', 0.90, 'OUT', 'Order_Food', NULL, '2026-04-23 10:32:03', '2026-04-23 10:32:03'),
(334, 16, 2, 'IN', 1.20, 'OUT', 'Order_Food', NULL, '2026-04-23 10:32:03', '2026-04-23 10:32:03'),
(335, 12, 3, 'IN', 2.00, 'OUT', 'Order_Drink', NULL, '2026-04-23 10:32:03', '2026-04-23 10:32:03'),
(336, 13, 2, 'IN', 0.40, 'OUT', 'Order_Food', NULL, '2026-04-25 06:09:12', '2026-04-25 06:09:12'),
(337, 14, 2, 'IN', 2.00, 'OUT', 'Order_Food', NULL, '2026-04-25 06:09:12', '2026-04-25 06:09:12'),
(338, 15, 2, 'IN', 0.60, 'OUT', 'Order_Food', NULL, '2026-04-25 06:09:12', '2026-04-25 06:09:12'),
(339, 16, 2, 'IN', 0.80, 'OUT', 'Order_Food', NULL, '2026-04-25 06:09:12', '2026-04-25 06:09:12'),
(340, 13, 2, 'IN', 0.10, 'OUT', 'Order_Food', NULL, '2026-04-25 06:09:12', '2026-04-25 06:09:12'),
(341, 14, 2, 'IN', 1.00, 'OUT', 'Order_Food', NULL, '2026-04-25 06:09:12', '2026-04-25 06:09:12'),
(342, 15, 2, 'IN', 0.30, 'OUT', 'Order_Food', NULL, '2026-04-25 06:09:12', '2026-04-25 06:09:12'),
(343, 16, 2, 'IN', 0.50, 'OUT', 'Order_Food', NULL, '2026-04-25 06:09:12', '2026-04-25 06:09:12'),
(344, 12, 1, 'IN', 100.00, 'OUT', 'transfer', NULL, '2026-04-25 06:48:41', '2026-04-25 06:48:41'),
(345, 12, 3, 'IN', 100.00, 'IN', 'transfer', NULL, '2026-04-25 06:48:41', '2026-04-25 06:48:41'),
(346, 13, 2, 'IN', 0.40, 'OUT', 'Order_Food', NULL, '2026-04-25 08:58:46', '2026-04-25 08:58:46'),
(347, 14, 2, 'IN', 2.00, 'OUT', 'Order_Food', NULL, '2026-04-25 08:58:46', '2026-04-25 08:58:46'),
(348, 15, 2, 'IN', 0.60, 'OUT', 'Order_Food', NULL, '2026-04-25 08:58:46', '2026-04-25 08:58:46'),
(349, 16, 2, 'IN', 0.80, 'OUT', 'Order_Food', NULL, '2026-04-25 08:58:46', '2026-04-25 08:58:46'),
(350, 13, 2, 'IN', 0.20, 'OUT', 'Order_Food', NULL, '2026-04-25 08:58:46', '2026-04-25 08:58:46'),
(351, 14, 2, 'IN', 2.00, 'OUT', 'Order_Food', NULL, '2026-04-25 08:58:46', '2026-04-25 08:58:46'),
(352, 15, 2, 'IN', 0.60, 'OUT', 'Order_Food', NULL, '2026-04-25 08:58:46', '2026-04-25 08:58:46'),
(353, 16, 2, 'IN', 1.00, 'OUT', 'Order_Food', NULL, '2026-04-25 08:58:46', '2026-04-25 08:58:46'),
(354, 13, 2, 'IN', 0.20, 'OUT', 'Order_Food', NULL, '2026-05-07 08:44:16', '2026-05-07 08:44:16'),
(355, 14, 2, 'IN', 1.00, 'OUT', 'Order_Food', NULL, '2026-05-07 08:44:16', '2026-05-07 08:44:16'),
(356, 15, 2, 'IN', 0.30, 'OUT', 'Order_Food', NULL, '2026-05-07 08:44:16', '2026-05-07 08:44:16'),
(357, 16, 2, 'IN', 0.40, 'OUT', 'Order_Food', NULL, '2026-05-07 08:44:16', '2026-05-07 08:44:16'),
(358, 13, 2, 'IN', 0.10, 'OUT', 'Order_Food', NULL, '2026-05-07 08:44:16', '2026-05-07 08:44:16'),
(359, 14, 2, 'IN', 1.00, 'OUT', 'Order_Food', NULL, '2026-05-07 08:44:16', '2026-05-07 08:44:16'),
(360, 15, 2, 'IN', 0.30, 'OUT', 'Order_Food', NULL, '2026-05-07 08:44:16', '2026-05-07 08:44:16'),
(361, 16, 2, 'IN', 0.50, 'OUT', 'Order_Food', NULL, '2026-05-07 08:44:16', '2026-05-07 08:44:16'),
(362, 13, 2, 'IN', 0.20, 'OUT', 'Order_Food', NULL, '2026-05-15 11:54:49', '2026-05-15 11:54:49'),
(363, 14, 2, 'IN', 1.00, 'OUT', 'Order_Food', NULL, '2026-05-15 11:54:49', '2026-05-15 11:54:49'),
(364, 15, 2, 'IN', 0.30, 'OUT', 'Order_Food', NULL, '2026-05-15 11:54:49', '2026-05-15 11:54:49'),
(365, 16, 2, 'IN', 0.40, 'OUT', 'Order_Food', NULL, '2026-05-15 11:54:49', '2026-05-15 11:54:49'),
(366, 12, 3, 'IN', 3.00, 'OUT', 'Order_Drink', NULL, '2026-05-15 11:54:49', '2026-05-15 11:54:49'),
(367, 13, 2, 'IN', 0.20, 'OUT', 'Order_Food', NULL, '2026-05-15 12:04:41', '2026-05-15 12:04:41'),
(368, 14, 2, 'IN', 1.00, 'OUT', 'Order_Food', NULL, '2026-05-15 12:04:42', '2026-05-15 12:04:42'),
(369, 15, 2, 'IN', 0.30, 'OUT', 'Order_Food', NULL, '2026-05-15 12:04:42', '2026-05-15 12:04:42'),
(370, 16, 2, 'IN', 0.40, 'OUT', 'Order_Food', NULL, '2026-05-15 12:04:42', '2026-05-15 12:04:42'),
(371, 13, 2, 'IN', 0.10, 'OUT', 'Order_Food', NULL, '2026-05-15 12:04:52', '2026-05-15 12:04:52'),
(372, 14, 2, 'IN', 1.00, 'OUT', 'Order_Food', NULL, '2026-05-15 12:04:52', '2026-05-15 12:04:52'),
(373, 15, 2, 'IN', 0.30, 'OUT', 'Order_Food', NULL, '2026-05-15 12:04:52', '2026-05-15 12:04:52'),
(374, 16, 2, 'IN', 0.50, 'OUT', 'Order_Food', NULL, '2026-05-15 12:04:52', '2026-05-15 12:04:52'),
(375, 12, 3, 'IN', 9.00, 'OUT', 'Order_Drink', NULL, '2026-05-15 16:36:54', '2026-05-15 16:36:54'),
(376, 13, 2, 'IN', 0.60, 'OUT', 'Order_Food', NULL, '2026-05-15 16:36:54', '2026-05-15 16:36:54'),
(377, 14, 2, 'IN', 3.00, 'OUT', 'Order_Food', NULL, '2026-05-15 16:36:54', '2026-05-15 16:36:54'),
(378, 15, 2, 'IN', 0.90, 'OUT', 'Order_Food', NULL, '2026-05-15 16:36:54', '2026-05-15 16:36:54'),
(379, 16, 2, 'IN', 1.20, 'OUT', 'Order_Food', NULL, '2026-05-15 16:36:54', '2026-05-15 16:36:54'),
(380, 13, 2, 'IN', 0.20, 'OUT', 'Order_Food', NULL, '2026-05-16 05:44:51', '2026-05-16 05:44:51'),
(381, 14, 2, 'IN', 1.00, 'OUT', 'Order_Food', NULL, '2026-05-16 05:44:51', '2026-05-16 05:44:51'),
(382, 15, 2, 'IN', 0.30, 'OUT', 'Order_Food', NULL, '2026-05-16 05:44:51', '2026-05-16 05:44:51'),
(383, 16, 2, 'IN', 0.40, 'OUT', 'Order_Food', NULL, '2026-05-16 05:44:51', '2026-05-16 05:44:51'),
(384, 13, 2, 'IN', 0.10, 'OUT', 'Order_Food', NULL, '2026-05-16 05:44:51', '2026-05-16 05:44:51'),
(385, 14, 2, 'IN', 1.00, 'OUT', 'Order_Food', NULL, '2026-05-16 05:44:51', '2026-05-16 05:44:51'),
(386, 15, 2, 'IN', 0.30, 'OUT', 'Order_Food', NULL, '2026-05-16 05:44:51', '2026-05-16 05:44:51'),
(387, 16, 2, 'IN', 0.50, 'OUT', 'Order_Food', NULL, '2026-05-16 05:44:51', '2026-05-16 05:44:51'),
(388, 12, 3, 'IN', 5.00, 'OUT', 'Order_Drink', NULL, '2026-05-16 05:44:52', '2026-05-16 05:44:52'),
(389, 12, 3, 'IN', 7.00, 'OUT', 'Order_Drink', NULL, '2026-05-16 08:16:56', '2026-05-16 08:16:56'),
(390, 13, 2, 'IN', 1.20, 'OUT', 'Order_Food', NULL, '2026-05-16 08:16:56', '2026-05-16 08:16:56'),
(391, 14, 2, 'IN', 6.00, 'OUT', 'Order_Food', NULL, '2026-05-16 08:16:56', '2026-05-16 08:16:56'),
(392, 15, 2, 'IN', 1.80, 'OUT', 'Order_Food', NULL, '2026-05-16 08:16:56', '2026-05-16 08:16:56'),
(393, 16, 2, 'IN', 2.40, 'OUT', 'Order_Food', NULL, '2026-05-16 08:16:56', '2026-05-16 08:16:56'),
(394, 13, 2, 'IN', 0.20, 'OUT', 'Order_Food', NULL, '2026-05-16 14:50:34', '2026-05-16 14:50:34'),
(395, 14, 2, 'IN', 1.00, 'OUT', 'Order_Food', NULL, '2026-05-16 14:50:34', '2026-05-16 14:50:34'),
(396, 15, 2, 'IN', 0.30, 'OUT', 'Order_Food', NULL, '2026-05-16 14:50:34', '2026-05-16 14:50:34'),
(397, 16, 2, 'IN', 0.40, 'OUT', 'Order_Food', NULL, '2026-05-16 14:50:34', '2026-05-16 14:50:34'),
(398, 13, 2, 'IN', 0.10, 'OUT', 'Order_Food', NULL, '2026-05-16 14:50:34', '2026-05-16 14:50:34'),
(399, 14, 2, 'IN', 1.00, 'OUT', 'Order_Food', NULL, '2026-05-16 14:50:34', '2026-05-16 14:50:34'),
(400, 15, 2, 'IN', 0.30, 'OUT', 'Order_Food', NULL, '2026-05-16 14:50:34', '2026-05-16 14:50:34'),
(401, 16, 2, 'IN', 0.50, 'OUT', 'Order_Food', NULL, '2026-05-16 14:50:34', '2026-05-16 14:50:34'),
(402, 12, 3, 'IN', 10.00, 'OUT', 'Order_Drink', NULL, '2026-05-16 14:50:34', '2026-05-16 14:50:34');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `phone`, `email`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Abenu', '0987654321', 'Abenu@gmail.com', 1, '2026-02-13 15:11:42', '2026-02-13 15:41:17'),
(2, 'Mogi', '1234567890', 'moji@gmail.com', 1, '2026-02-13 15:13:35', '2026-02-13 15:41:27'),
(3, 'meles', '0900129798', 'meles@gmail.com', 1, '2026-02-13 15:40:54', '2026-02-13 15:41:07');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `symbol` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `symbol`, `created_at`, `updated_at`) VALUES
(1, 'Litter', 'Li', '2026-02-13 14:39:04', '2026-02-13 14:42:25'),
(2, 'Kilogram', 'Kg', '2026-02-13 14:42:38', '2026-02-13 14:42:38'),
(4, 'Unit', 'pc', '2026-02-14 10:04:18', '2026-02-14 10:04:18'),
(5, 'Can', 'can', '2026-02-15 11:11:31', '2026-02-15 11:11:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` int(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `active` int(10) DEFAULT NULL,
  `salary` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `role`, `created_at`, `updated_at`, `phone`, `email`, `username`, `active`, `salary`) VALUES
(6, 'admin', '$2y$12$wJynTVWi2mLziAC6jjM8Q.0EwK2qLu1Un.FBm8z29kYO5fhU4jwQa', 'admin', '2026-02-05 15:38:22', '2026-03-10 11:29:45', 900129798, 'abenezer2310@gmail.com', 'admin', 1, 10000),
(12, 'Waiter 1', '$2y$12$Ld3/lwksJdAYEyZd0OJxhu1OqBxS2L.2F1r7tGqPU8fnv/WNNe5FC', 'waiter', '2026-04-01 06:15:48', '2026-05-15 11:01:19', 1234567890, 'waiter@gmail.com', 'waiter 1', 0, 4000),
(13, 'Waiter 2', '$2y$12$6bxL/46V2DCHZRTvmWCWd.EXAcQOfSAJ/T.8I09mIgWIuiq73jwh.', 'waiter', '2026-04-01 06:16:26', '2026-04-01 06:16:26', 1234567809, 'waiter2@gmail.com', 'waiter 2', 1, 4000),
(14, 'Casher', '$2y$12$63Ts1YP4IyE4AuIGruFt9Os/b..KhwwuHZuvvpM3xwW1L3oo7EPEu', 'casher', '2026-04-01 06:16:54', '2026-04-01 06:16:54', 1234567098, 'casher@gmail.com', 'casher', 1, 6000),
(15, 'Waiter 3', '$2y$12$qYXdh73jsm4TxP4UQqUJLOvUPpPiGQmPX7UioEw3QiSuot5Ly5wrC', 'waiter', '2026-05-15 11:01:09', '2026-05-15 11:01:09', 98651234, 'Waiter3@gmail.com', 'Waiter 3', 1, 5000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cats`
--
ALTER TABLE `cats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_items_unit` (`unit_id`),
  ADD KEY `items_cat_id_foreign` (`cat_id`);

--
-- Indexes for table `kitchen_stats`
--
ALTER TABLE `kitchen_stats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number_date_unique` (`order_number`,`date_created`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menus_cat` (`cat_id`);

--
-- Indexes for table `menu_ingredients`
--
ALTER TABLE `menu_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menu_ingredients_menu_id_item_id_unique` (`menu_id`,`item_id`),
  ADD KEY `menu_ingredients_item_id_foreign` (`item_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stocks_item_id_location_id_unique` (`item_id`,`location_id`),
  ADD KEY `stocks_location_id_foreign` (`location_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stock_supplier` (`supplier_id`),
  ADD KEY `idx_stock_item` (`item_id`),
  ADD KEY `idx_stock_type` (`type`),
  ADD KEY `fk_stock_location` (`location_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cats`
--
ALTER TABLE `cats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `kitchen_stats`
--
ALTER TABLE `kitchen_stats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `menu_ingredients`
--
ALTER TABLE `menu_ingredients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=262;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=509;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=403;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `fk_items_unit` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `items_cat_id_foreign` FOREIGN KEY (`cat_id`) REFERENCES `cats` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `fk_menus_cat` FOREIGN KEY (`cat_id`) REFERENCES `cats` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_ingredients`
--
ALTER TABLE `menu_ingredients`
  ADD CONSTRAINT `menu_ingredients_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_ingredients_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stocks_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `fk_stock_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stock_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
  ADD CONSTRAINT `fk_stock_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
