-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2024 at 02:48 PM
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
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Milkteas'),
(2, 'Fruiteas'),
(3, 'Lemonades'),
(4, 'Add Ons'),
(5, 'Iced Coffee'),
(6, 'Non-Coffee/Milk'),
(7, 'Hot Drinks'),
(8, 'Coffee/Milk Add Ons');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `discount_id` int(11) NOT NULL,
  `discount_code` varchar(50) NOT NULL,
  `discount_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`discount_id`, `discount_code`, `discount_amount`) VALUES
(3, 'Hey!', 5),
(7, 'Suki', 3);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_name` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `category_id` int(50) NOT NULL,
  `product_id` int(8) UNSIGNED ZEROFILL NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_name`, `price`, `category_id`, `product_id`) VALUES
('Americano', 80, 5, 00000001),
('Blueberry', 80, 2, 00000002),
('Coffee', 100, 5, 00000003),
('Cookies N Cream (M)', 80, 1, 00000004),
('Fresh Lemon', 80, 3, 00000005),
('Mocha', 120, 5, 00000006),
('Pearl', 15, 4, 00000007),
('Lychee', 80, 2, 00000008),
('Matcha (M)', 80, 1, 00000010),
('Matcha (L)', 90, 1, 00000011),
('Wintermelon (M)', 80, 1, 00000012),
('Wintermelon (L)', 90, 1, 00000013),
('Honey Lemon', 90, 3, 00000015),
('Lemonade w/ Chia', 80, 3, 00000016),
('Lemonade w/ Yakult', 85, 3, 00000017),
('Green Apple', 80, 2, 00000018),
('Strawberry', 80, 2, 00000019),
('Mango', 80, 2, 00000020),
('Cookies N Cream (L)', 90, 1, 00000021),
('Dark Choco (M)', 80, 1, 00000022),
('Dark Choco (L)', 90, 1, 00000023),
('Creamy Taro (L)', 90, 1, 00000024),
('Creamy Taro (M)', 80, 1, 00000025),
('Okinawa (L)', 90, 1, 00000026),
('Okinawa (M)', 80, 1, 00000027),
('Salted Caramel (L)', 90, 1, 00000028),
('Salted Caramel (M)', 80, 1, 00000029),
('Americano ', 60, 7, 00000030),
('Chocolate', 70, 7, 00000031),
('Coffee', 80, 7, 00000032),
('3 in 1', 20, 7, 00000033),
('Spanish Latte', 120, 5, 00000034),
('French Vanilla', 120, 5, 00000035),
('Caramel Macchiato', 120, 5, 00000036),
('White Mocha', 140, 5, 00000037),
('Matcha', 100, 6, 00000038),
('Blueberry Milk', 100, 6, 00000039),
('Mango Milk', 100, 6, 00000040),
('Ube Milk', 100, 6, 00000041),
('Strawberry Milk', 110, 6, 00000042),
('Berry-Matcha', 120, 6, 00000043),
('Nata', 15, 4, 00000044),
('Yakult', 15, 4, 00000045),
('Syrup', 20, 4, 00000046),
('Milk', 25, 8, 00000047),
('Extra Syrup', 25, 8, 00000048),
('Single Shot', 25, 8, 00000049);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `order_details` varchar(9999) NOT NULL,
  `date_time` datetime NOT NULL,
  `discount_code` varchar(50) DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `final_total_amount` decimal(10,2) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `amount_received` decimal(10,2) NOT NULL,
  `change_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `username`, `order_details`, `date_time`, `discount_code`, `discount_amount`, `final_total_amount`, `payment_type`, `amount_received`, `change_amount`) VALUES
(25, 'new', '[{\"productName\":\"Cookies N Cream (L)  x2\",\"productPrice\":180},{\"productName\":\"Cookies N Cream (M)  x1\",\"productPrice\":80},{\"productName\":\"3 in 1  x2\",\"productPrice\":40},{\"productName\":\"Coffee  x1\",\"productPrice\":100},{\"productName\":\"Extra Syrup Add On x1\",\"productPrice\":25},{\"productName\":\"Milk Add On x1\",\"productPrice\":25},{\"productName\":\"Single Shot Add On x1\",\"productPrice\":25}]', '2024-03-19 21:38:46', 'Hey!', 5.00, 451.25, 'cash', 500.00, 48.75),
(26, 'new', '[{\"productName\":\"Creamy Taro (M)  x7\",\"productPrice\":560}]', '2024-03-19 21:41:53', '', 0.00, 560.00, 'cash', 600.00, 40.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(8) UNSIGNED ZEROFILL NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `account_type`) VALUES
(00000001, 'Emman', '$2y$10$Imz2C2AUQVoKx/YSDwnB9eB4ehVdRDceHjZY/MaWmTkQwZjeIVc1e', 'admin'),
(00000002, 'NEW', '$2y$10$YWgTvaWD2ElVlQA1qAprsOYgWPp083ar//aCAjVffHgqWPSgKXCtW', 'staff'),
(00000008, 'adwa', '$2y$10$bNvBzku3IaRvR.b.9zYxWu2YMxnxE228qc4.dyGJdR8WvizfzZJ1K', 'staff'),
(00000009, 'luhhhh', '$2y$10$O013ulxi7JlQWql/zhjU8.EncOllaYmDApwKP6qmYE.wH0XapXKTC', 'staff'),
(00000010, 'adaw213', '$2y$10$NKDSgdq..0HVhL8QxP1obeo4T6O.zY8ETB7WGD/Yo.Wve/ZVXTePy', 'staff');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`discount_id`),
  ADD UNIQUE KEY `discount_code` (`discount_code`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
