-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 22, 2026 at 02:11 PM
-- Server version: 8.0.45-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `arien_bakery`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$zDvuFN8n9wow5mV/GVChpOP557LAnjxsw6WqMzVMs33YmbZr1/nzq');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`) VALUES
(31, 'Brunt Cheesecake Series', '1769275048_6974fea84fbf8.webp'),
(32, 'Custom Chiffon Cake', '1769275567_697500afc5658.webp'),
(33, 'Mile Creaps Series', '1769275629_697500ed4bc1d.webp'),
(34, 'Minicake Series', '1769275607_697500d7d0d5e.webp'),
(35, 'Premium Cake Series', '1769275621_697500e5e5be5.webp');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int NOT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `address` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int NOT NULL,
  `question` text,
  `answer` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`) VALUES
(2, 'example', 'example');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `description` text,
  `price` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `best_sell` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `image`, `best_sell`) VALUES
(73, 31, 'Bunny Cheesecake', NULL, '', NULL, 0),
(74, 31, 'Bento Cheesecake', NULL, '', NULL, 0),
(75, 31, 'Mochi Cheesecake', NULL, '', NULL, 0),
(76, 32, 'Bento Cake', NULL, '', NULL, 1),
(77, 32, 'Petite cake', NULL, '', NULL, 0),
(78, 32, 'Simple cakes', NULL, '', NULL, 0),
(79, 32, 'Vintage cake', NULL, '', NULL, 1),
(80, 32, 'Flower cakes', NULL, '', NULL, 0),
(81, 32, 'Bouquet cake', NULL, '', NULL, 0),
(82, 32, 'Half cake', NULL, '', NULL, 0),
(83, 33, 'Strawberry milecreap', NULL, '', NULL, 0),
(84, 33, 'Matcha milecreap', NULL, '', NULL, 0),
(85, 33, 'Choco milecreap', NULL, '', NULL, 0),
(86, 33, 'Vanilla milecreap', NULL, '', NULL, 0),
(87, 34, 'Mini cake', NULL, '', NULL, 0),
(88, 34, 'Mini cakes', NULL, '', NULL, 0),
(89, 35, 'Strawberry shortcake', NULL, '', NULL, 0),
(90, 35, 'Matchaberry cake', NULL, '', NULL, 0),
(91, 35, 'Chocoberry cake', NULL, '', NULL, 0),
(92, 35, 'Lemon cake', NULL, '', NULL, 0),
(93, 35, 'Shinemuscat cake', NULL, '', NULL, 0),
(94, 35, 'Manggo cake', NULL, '', NULL, 0),
(95, 35, 'Choco tiramisu cake', NULL, '', NULL, 1),
(96, 35, 'Redvelvet cake', NULL, '', NULL, 0),
(97, 35, 'Tartlet series', NULL, '', NULL, 1),
(98, 35, 'chiffon Cheesebrulee cake', NULL, '', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_descriptions`
--

CREATE TABLE `product_descriptions` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `description` text NOT NULL,
  `is_large_font` tinyint(1) DEFAULT '0',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_descriptions`
--

INSERT INTO `product_descriptions` (`id`, `product_id`, `description`, `is_large_font`, `sort_order`, `created_at`) VALUES
(39, 73, 'diameter 14cm start from 120k', 0, 0, '2026-02-19 18:10:59'),
(40, 73, 'diameter 16cm start from 150k', 0, 99, '2026-02-19 18:10:59'),
(41, 73, 'diameter 18cm start from 180k', 0, 99, '2026-02-19 18:10:59'),
(42, 74, 'diameter 12cm start from 40k', 0, 0, '2026-02-19 18:14:05'),
(43, 75, 'diameter 12cm start from 45k', 0, 0, '2026-02-19 18:36:39'),
(44, 75, 'diameter 14cm start from 130k', 0, 99, '2026-02-19 18:36:39'),
(45, 75, 'diameter 16cm start from 160k', 0, 99, '2026-02-19 18:36:39'),
(46, 75, 'Base Cheesecake', 1, 99, '2026-02-19 18:36:39'),
(47, 75, 'matcha 15k', 0, 99, '2026-02-19 18:36:39'),
(48, 75, 'chocolate 15k', 0, 99, '2026-02-19 18:36:39'),
(49, 76, 'bentuk bulat start from 35k', 0, 0, '2026-02-19 18:42:41'),
(50, 76, 'bentuk hati start from 35k', 0, 99, '2026-02-19 18:42:41'),
(51, 76, '.', 0, 99, '2026-02-19 18:42:41'),
(52, 76, 'base cake :', 0, 99, '2026-02-19 18:42:41'),
(53, 76, 'vanilla', 0, 99, '2026-02-19 18:42:41'),
(54, 76, 'matcha', 0, 99, '2026-02-19 18:42:41'),
(55, 76, 'chocolate', 0, 99, '2026-02-19 18:42:41'),
(56, 76, '.', 0, 99, '2026-02-19 18:42:41'),
(57, 76, '2 layer with tinggi +-5cm', 0, 99, '2026-02-19 18:42:41'),
(58, 76, 'free candle, sendok', 0, 99, '2026-02-19 18:42:41'),
(59, 76, 'add gambar, buah, bunga (5k - 20k)', 0, 99, '2026-02-19 18:42:41'),
(60, 77, 'bentuk bulat start from 50k', 0, 0, '2026-02-19 18:48:05'),
(61, 77, 'bentuk hati start from 50k', 0, 99, '2026-02-19 18:48:05'),
(62, 77, '.', 0, 99, '2026-02-19 18:48:05'),
(63, 77, '12cm', 0, 99, '2026-02-19 18:48:05'),
(64, 77, '2 layer with tinggi +- 5cm', 0, 99, '2026-02-19 18:48:05'),
(65, 77, 'free candle, sendok', 0, 99, '2026-02-19 18:48:05'),
(66, 77, 'add gambar, buah, bunga (5k - 20k)', 0, 99, '2026-02-19 18:48:05'),
(67, 78, 'Tinggi +- 5cm', 1, 0, '2026-02-19 18:58:59'),
(68, 78, 'diameter 12cm - 50k', 0, 99, '2026-02-19 18:58:59'),
(69, 78, 'diameter 14cm - 55k', 0, 99, '2026-02-19 18:58:59'),
(70, 78, 'diameter 16cm - 65k', 0, 99, '2026-02-19 18:58:59'),
(71, 78, 'diameter 18cm - 85k', 0, 99, '2026-02-19 18:58:59'),
(72, 78, 'Tinggi +- 8cm', 1, 99, '2026-02-19 18:58:59'),
(73, 78, 'diameter 12cm - 65k', 0, 99, '2026-02-19 18:58:59'),
(74, 78, 'diameter 14cm - 75k', 0, 99, '2026-02-19 18:58:59'),
(75, 78, 'diameter 16cm - 90k', 0, 99, '2026-02-19 18:58:59'),
(76, 78, 'diameter 18cm - 120k', 0, 99, '2026-02-19 18:58:59'),
(77, 78, 'diameter 20cm - 165k', 0, 99, '2026-02-19 18:58:59'),
(78, 79, 'Tinggi +- 5cm', 1, 0, '2026-02-20 13:22:54'),
(79, 79, 'diameter 12cm - 55k', 0, 99, '2026-02-20 13:22:54'),
(80, 79, 'diameter 14cm - 60k', 0, 99, '2026-02-20 13:22:54'),
(81, 79, 'diameter 16cm - 70k', 0, 99, '2026-02-20 13:22:54'),
(82, 79, 'diameter 18cm - 90k', 0, 99, '2026-02-20 13:22:54'),
(83, 79, 'Tinggi +- 8cm', 1, 99, '2026-02-20 13:22:54'),
(84, 79, 'diameter 12cm - 70k', 0, 99, '2026-02-20 13:22:54'),
(85, 79, 'diameter 14cm - 80k', 0, 99, '2026-02-20 13:22:55'),
(86, 79, 'diameter 16cm - 95k', 0, 99, '2026-02-20 13:22:55'),
(87, 79, 'diameter 18cm - 125k', 0, 99, '2026-02-20 13:22:55'),
(88, 79, 'diameter 20cm - 170k', 0, 99, '2026-02-20 13:22:55'),
(89, 80, 'Tinggi +- 8cm', 1, 0, '2026-02-20 13:27:04'),
(90, 80, 'diameter 12cm start from 95k', 0, 99, '2026-02-20 13:27:04'),
(91, 80, 'diameter 14cm start from 105k', 0, 99, '2026-02-20 13:27:04'),
(92, 80, 'diameter 16cm start from 130k', 0, 99, '2026-02-20 13:27:04'),
(93, 80, 'diameter 18cm start from 165k', 0, 99, '2026-02-20 13:27:04'),
(94, 80, 'diameter 20cm start from 200k', 0, 99, '2026-02-20 13:27:04'),
(95, 81, 'Tinggi +- 8cm', 1, 0, '2026-02-20 13:31:44'),
(96, 81, 'diameter 12cm start from 75k', 0, 99, '2026-02-20 13:31:44'),
(97, 81, 'diameter 14cm start from 95k', 0, 99, '2026-02-20 13:31:44'),
(98, 81, 'diameter 16cm start from 110k', 0, 99, '2026-02-20 13:31:44'),
(99, 81, 'diameter 18cm start from 145k', 0, 99, '2026-02-20 13:31:44'),
(100, 81, 'diameter 20cm start from 190k', 0, 99, '2026-02-20 13:31:44'),
(101, 82, 'Tinggi +- 8cm', 1, 0, '2026-02-20 13:34:55'),
(102, 82, 'diameter 14cm start from 60k', 0, 99, '2026-02-20 13:34:55'),
(103, 82, 'diameter 16cm start from 70k', 0, 99, '2026-02-20 13:34:55'),
(104, 82, 'diameter 14cm start from 90k', 0, 99, '2026-02-20 13:34:55'),
(105, 82, 'Base cake', 1, 99, '2026-02-20 13:34:55'),
(106, 82, 'vanilla', 0, 99, '2026-02-20 13:34:55'),
(107, 82, 'matcha', 0, 99, '2026-02-20 13:34:55'),
(108, 82, 'strawberry + 10k', 0, 99, '2026-02-20 13:34:55'),
(109, 82, 'pandan + 10k', 0, 99, '2026-02-20 13:34:55'),
(110, 82, 'taro + 10k', 0, 99, '2026-02-20 13:34:55'),
(111, 83, 'diameter 16cm 130k', 0, 0, '2026-02-20 13:42:57'),
(112, 84, 'Diameter 16cm -132k', 0, 0, '2026-02-20 14:02:54'),
(113, 85, 'diameter 16cm - 130k', 0, 0, '2026-02-20 14:06:34'),
(114, 86, 'diameter 16cm - 130k', 0, 0, '2026-02-20 14:36:08'),
(115, 87, 'minicakes 1pcs - 40k', 0, 0, '2026-02-20 21:48:41'),
(116, 87, 'minicakes 2pcs - 70k', 0, 99, '2026-02-20 21:48:41'),
(117, 87, 'minicakes 3pcs - 100k', 0, 99, '2026-02-20 21:48:41'),
(118, 87, 'minicakes 4pcs - 120k', 0, 99, '2026-02-20 21:48:41'),
(119, 88, 'Tiramisu - 40k', 0, 0, '2026-02-20 21:58:04'),
(120, 88, 'strawberry mini - 40k', 0, 99, '2026-02-20 21:58:04'),
(121, 88, 'blueberry mini - 40k', 0, 99, '2026-02-20 21:58:04'),
(122, 88, 'matchamissu mini - 40k', 0, 99, '2026-02-20 21:58:04'),
(123, 88, 'chocoberry mini - 40k', 0, 99, '2026-02-20 21:58:04'),
(124, 88, 'mangga mini - 40k', 0, 99, '2026-02-20 21:58:04'),
(125, 88, 'matchaberry mini - 40k', 0, 99, '2026-02-20 21:58:04'),
(126, 88, 'shinemuscat mini - 40k', 0, 99, '2026-02-20 21:58:04'),
(127, 89, 'diameter 14cm - 124k', 0, 0, '2026-02-20 22:06:55'),
(128, 89, 'diameter 16cm - 154k', 0, 99, '2026-02-20 22:06:55'),
(129, 89, 'diameter 18cm - 184k', 0, 99, '2026-02-20 22:06:55'),
(130, 90, 'diameter 14cm - 133k', 0, 0, '2026-02-20 22:09:05'),
(131, 90, 'diameter 16cm - 163k', 0, 99, '2026-02-20 22:09:06'),
(132, 90, 'diameter 18cm - 193k', 0, 99, '2026-02-20 22:09:06'),
(133, 91, 'diameter 14cm - 136k', 0, 0, '2026-02-20 22:11:23'),
(134, 91, 'diameter 16cm - 166k', 0, 99, '2026-02-20 22:11:23'),
(135, 91, 'diameter 18cm - 196k', 0, 99, '2026-02-20 22:11:23'),
(136, 92, 'diameter 14cm - 132k', 0, 0, '2026-02-20 22:14:20'),
(137, 92, 'diameter 16cm - 162k', 0, 99, '2026-02-20 22:14:20'),
(138, 92, 'diameter 18cm - 192k', 0, 99, '2026-02-20 22:14:20'),
(139, 93, 'diameter 14cm - 142k', 0, 0, '2026-02-20 22:18:26'),
(140, 93, 'diameter 16cm - 172k', 0, 99, '2026-02-20 22:18:26'),
(141, 93, 'diameter 18cm - 202k', 0, 99, '2026-02-20 22:18:26'),
(142, 94, 'diameter 14cm - 136k', 0, 0, '2026-02-20 22:23:24'),
(143, 94, 'diameter 16cm - 166k', 0, 99, '2026-02-20 22:23:24'),
(144, 94, 'diameter 18cm - 196k', 0, 99, '2026-02-20 22:23:24'),
(145, 95, 'diameter 14cm - 170k', 0, 0, '2026-02-20 22:25:20'),
(146, 95, 'diameter 16cm - 200k', 0, 99, '2026-02-20 22:25:20'),
(147, 95, 'diameter 18cm - 230k', 0, 99, '2026-02-20 22:25:20'),
(148, 96, 'diameter 14cm - 120k', 0, 0, '2026-02-20 22:26:51'),
(149, 96, 'diameter 16cm - 150k', 0, 99, '2026-02-20 22:26:51'),
(150, 96, 'diameter 18cm - 180k', 0, 99, '2026-02-20 22:26:51'),
(151, 97, 'isi 2 - 110k', 0, 0, '2026-02-20 22:30:15'),
(152, 97, 'Base Tarlet', 1, 99, '2026-02-20 22:30:15'),
(153, 97, 'fla strawberrry', 0, 99, '2026-02-20 22:30:15'),
(154, 97, 'fla shinemuscat + 5k', 0, 99, '2026-02-20 22:30:15'),
(155, 97, 'matcha mouse', 0, 99, '2026-02-20 22:30:15'),
(156, 97, 'choco mouse', 0, 99, '2026-02-20 22:30:15'),
(157, 97, 'lemooncurd', 0, 99, '2026-02-20 22:30:15'),
(158, 98, 'diameter 14cm start from 75k', 0, 0, '2026-02-20 22:33:47'),
(159, 98, 'diameter 16cm start from 85k', 0, 99, '2026-02-20 22:33:47'),
(160, 98, 'diameter 18cm start from 105k', 0, 99, '2026-02-20 22:33:47'),
(161, 98, 'Base Cake', 1, 99, '2026-02-20 22:33:47'),
(162, 98, 'vanilla', 0, 99, '2026-02-20 22:33:47'),
(163, 98, 'matcha', 0, 99, '2026-02-20 22:33:47'),
(164, 98, 'chocolatte', 0, 99, '2026-02-20 22:33:47'),
(165, 98, 'strawberry + 10k', 0, 99, '2026-02-20 22:33:47'),
(166, 98, 'pandan + 10k', 0, 99, '2026-02-20 22:33:47'),
(167, 98, 'taro + 10k', 0, 99, '2026-02-20 22:33:47');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `sort_order`, `created_at`) VALUES
(30, 73, 'product_73_1771524654_6997522e87860.webp', 0, '2026-02-19 18:10:59'),
(32, 74, 'product_74_1771524762_6997529a4cfc2.webp', 0, '2026-02-19 18:13:01'),
(33, 74, 'product_74_1771524781_699752ada1491.webp', 99, '2026-02-19 18:13:22'),
(34, 74, 'product_74_1771524802_699752c22dbd6.webp', 99, '2026-02-19 18:13:43'),
(35, 74, 'product_74_1771524823_699752d79b0e1.webp', 99, '2026-02-19 18:14:05'),
(37, 75, 'product_75_1771526168_699758185dc37.webp', 99, '2026-02-19 18:36:25'),
(38, 75, 'product_75_1771526185_6997582962c38.webp', 1, '2026-02-19 18:36:39'),
(39, 76, 'product_76_1771526502_69975966016c6.webp', 0, '2026-02-19 18:41:56'),
(40, 76, 'product_76_1771526516_69975974cf4ad.webp', 99, '2026-02-19 18:42:10'),
(41, 76, 'product_76_1771526530_699759823d3d8.webp', 99, '2026-02-19 18:42:25'),
(42, 76, 'product_76_1771526545_699759911f442.webp', 99, '2026-02-19 18:42:41'),
(43, 77, 'product_77_1771526822_69975aa6dcc9a.webp', 2, '2026-02-19 18:47:21'),
(44, 77, 'product_77_1771526841_69975ab998c9b.webp', 1, '2026-02-19 18:47:52'),
(45, 77, 'product_77_1771526872_69975ad82eb95.webp', 99, '2026-02-19 18:48:05'),
(46, 78, 'product_78_1771527347_69975cb363ae6.webp', 0, '2026-02-19 18:56:04'),
(47, 78, 'product_78_1771527364_69975cc460e1f.webp', 99, '2026-02-19 18:56:21'),
(48, 78, 'product_78_1771527381_69975cd5825e2.webp', 99, '2026-02-19 18:56:41'),
(49, 78, 'product_78_1771527401_69975ce9819c0.webp', 99, '2026-02-19 18:56:57'),
(50, 78, 'product_78_1771527417_69975cf966035.webp', 99, '2026-02-19 18:57:30'),
(51, 78, 'product_78_1771527450_69975d1ad4ecf.webp', 99, '2026-02-19 18:57:45'),
(52, 78, 'product_78_1771527465_69975d2981c76.webp', 99, '2026-02-19 18:58:05'),
(53, 78, 'product_78_1771527485_69975d3d14455.webp', 99, '2026-02-19 18:58:20'),
(54, 78, 'product_78_1771527500_69975d4c90108.webp', 99, '2026-02-19 18:58:41'),
(55, 78, 'product_78_1771527521_69975d6115696.webp', 99, '2026-02-19 18:58:59'),
(56, 79, 'product_79_1771593655_69985fb7cf6c4.webp', 0, '2026-02-20 13:21:12'),
(57, 79, 'product_79_1771593672_69985fc8cc279.webp', 99, '2026-02-20 13:21:28'),
(58, 79, 'product_79_1771593688_69985fd8b3a41.webp', 99, '2026-02-20 13:21:45'),
(59, 79, 'product_79_1771593705_69985fe9456dc.webp', 99, '2026-02-20 13:22:08'),
(60, 79, 'product_79_1771593728_69986000c1646.webp', 99, '2026-02-20 13:22:24'),
(61, 79, 'product_79_1771593744_69986010cfb7a.webp', 99, '2026-02-20 13:22:40'),
(62, 79, 'product_79_1771593760_69986020493f8.webp', 99, '2026-02-20 13:22:54'),
(63, 80, 'product_80_1771593952_699860e03e5cb.webp', 0, '2026-02-20 13:26:14'),
(64, 80, 'product_80_1771593974_699860f602420.webp', 99, '2026-02-20 13:26:32'),
(66, 80, 'product_80_1771594006_699861167826b.webp', 99, '2026-02-20 13:27:04'),
(67, 81, 'product_81_1771594243_6998620351d8a.webp', 0, '2026-02-20 13:31:15'),
(68, 81, 'product_81_1771594275_699862236f091.webp', 99, '2026-02-20 13:31:30'),
(69, 81, 'product_81_1771594290_699862328c985.webp', 99, '2026-02-20 13:31:44'),
(70, 82, 'product_82_1771594462_699862de6d847.webp', 0, '2026-02-20 13:34:38'),
(71, 82, 'product_82_1771594478_699862eeda82a.webp', 99, '2026-02-20 13:34:55'),
(72, 83, 'product_83_1771594960_699864d0ce7b8.webp', 0, '2026-02-20 13:42:57'),
(73, 84, 'product_84_1771596080_699869305aed2.webp', 0, '2026-02-20 14:02:05'),
(74, 84, 'product_84_1771596125_6998695d57a0f.webp', 99, '2026-02-20 14:02:20'),
(75, 84, 'product_84_1771596140_6998696c5c2b1.webp', 99, '2026-02-20 14:02:37'),
(77, 85, 'product_85_1771596330_69986a2a4f6e5.webp', 0, '2026-02-20 14:05:47'),
(78, 85, 'product_85_1771596347_69986a3b8a2b6.webp', 99, '2026-02-20 14:06:34'),
(79, 86, 'product_86_1771598051_699870e3d262c.webp', 2, '2026-02-20 14:34:59'),
(80, 86, 'product_86_1771598099_6998711324c14.webp', 99, '2026-02-20 14:35:20'),
(82, 87, 'product_87_1771624104_6998d6a8bd405.webp', 0, '2026-02-20 21:48:41'),
(83, 88, 'product_88_1771624520_6998d848186c8.webp', 0, '2026-02-20 21:55:40'),
(84, 88, 'product_88_1771624540_6998d85ce65fe.webp', 99, '2026-02-20 21:56:01'),
(85, 88, 'product_88_1771624561_6998d87134b8e.webp', 99, '2026-02-20 21:56:21'),
(86, 88, 'product_88_1771624581_6998d88520a5f.webp', 99, '2026-02-20 21:56:40'),
(87, 88, 'product_88_1771624600_6998d898540fc.webp', 99, '2026-02-20 21:57:00'),
(88, 88, 'product_88_1771624620_6998d8aca6b4e.webp', 99, '2026-02-20 21:57:22'),
(89, 88, 'product_88_1771624642_6998d8c2aaf8d.webp', 99, '2026-02-20 21:57:42'),
(90, 88, 'product_88_1771624662_6998d8d61abde.webp', 99, '2026-02-20 21:58:04'),
(91, 89, 'product_89_1771625161_6998dac906f6d.webp', 0, '2026-02-20 22:06:18'),
(92, 89, 'product_89_1771625178_6998dada1a19e.webp', 99, '2026-02-20 22:06:34'),
(93, 89, 'product_89_1771625194_6998daeaea269.webp', 99, '2026-02-20 22:06:55'),
(94, 90, 'product_90_1771625306_6998db5a318c6.webp', 0, '2026-02-20 22:08:45'),
(95, 90, 'product_90_1771625325_6998db6d0a747.webp', 99, '2026-02-20 22:09:05'),
(97, 91, 'product_91_1771625447_6998dbe7e8078.webp', 99, '2026-02-20 22:11:23'),
(98, 92, 'product_92_1771625579_6998dc6b323bb.webp', 2, '2026-02-20 22:13:41'),
(102, 93, 'product_93_1771625850_6998dd7a24315.webp', 99, '2026-02-20 22:18:11'),
(104, 94, 'product_94_1771626157_6998dead407e6.webp', 0, '2026-02-20 22:23:24'),
(106, 95, 'product_95_1771626300_6998df3c971f5.webp', 99, '2026-02-20 22:25:20'),
(108, 96, 'product_96_1771626401_6998dfa11dd66.webp', 99, '2026-02-20 22:26:51'),
(109, 97, 'product_97_1771626584_6998e0588b644.webp', 0, '2026-02-20 22:30:15'),
(110, 98, 'product_98_1771626812_6998e13cde0ac.webp', 0, '2026-02-20 22:33:47'),
(112, 95, 'product_95_1771626871_6998e17708723.webp', 0, '2026-02-20 22:34:52'),
(113, 96, 'product_96_1771627532_6998e40ca952f.webp', 0, '2026-02-20 22:45:45'),
(115, 73, 'product_73_1771662444_69996c6c6e15d.webp', 0, '2026-02-21 08:27:24'),
(116, 80, 'product_80_1771663021_69996ead55be4.webp', 99, '2026-02-21 08:37:01'),
(117, 91, 'product_91_1771663324_69996fdc644b6.webp', 0, '2026-02-21 08:42:05'),
(118, 92, 'product_92_1771663426_69997042d3e5d.webp', 1, '2026-02-21 08:43:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_descriptions`
--
ALTER TABLE `product_descriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `product_descriptions`
--
ALTER TABLE `product_descriptions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_descriptions`
--
ALTER TABLE `product_descriptions`
  ADD CONSTRAINT `product_descriptions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
