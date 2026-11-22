-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2026 at 10:32 AM
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
-- Database: `ecommerce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(28, 3, 8, 1, '2026-06-20 08:21:55');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`) VALUES
(3, 'Sofas', 'Comfortable sofas and couches'),
(4, 'Tables', 'Dining and coffee tables'),
(5, 'Beds', 'Bedroom furniture'),
(6, 'Chairs', 'Office and home chairs'),
(7, 'Storage', 'Wardrobes and cabinets');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `message_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`message_id`, `name`, `email`, `subject`, `message`, `is_read`, `submitted_at`) VALUES
(1, 'Yumna AbuMusameh', 'ya.musameh@gmail.com', 'qury', 'do yu have sofas wth diferent colors', 1, '2026-06-14 12:47:08');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','shipped','delivered','cancelled') DEFAULT 'pending',
  `shipping_address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_amount`, `status`, `shipping_address`) VALUES
(1, 1, '2026-06-14 12:35:50', 3099.97, 'pending', 'Customer Address'),
(2, 1, '2026-06-14 12:39:03', 899.99, 'pending', 'Customer Address'),
(3, 1, '2026-06-14 13:22:22', 1799.98, 'pending', 'Customer Address'),
(4, 1, '2026-06-14 13:23:41', 899.99, 'pending', 'Customer Address'),
(5, 1, '2026-06-15 09:03:51', 899.99, 'pending', 'Customer Address'),
(6, 1, '2026-06-17 10:45:04', 1549.98, 'pending', 'Customer Address'),
(7, 1, '2026-06-17 12:05:51', 899.99, 'pending', 'Customer Address'),
(8, 2, '2026-06-17 12:11:23', 1549.98, 'pending', 'Customer Address'),
(9, 2, '2026-06-17 14:47:05', 749.99, 'pending', 'Customer Address'),
(10, 2, '2026-06-17 14:51:49', 899.99, 'pending', 'Customer Address'),
(11, 2, '2026-06-18 06:16:32', 899.99, 'paid', 'Customer Address'),
(12, 2, '2026-06-18 10:37:46', 899.99, 'paid', 'Customer Address'),
(13, 2, '2026-06-18 17:57:28', 2549.97, 'paid', 'Customer Address'),
(14, 3, '2026-06-20 08:21:39', 2049.97, 'pending', 'Customer Address');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(1, 1, 10, 1, 1299.99),
(2, 1, 8, 2, 899.99),
(3, 2, 8, 1, 899.99),
(4, 3, 8, 2, 899.99),
(5, 4, 8, 1, 899.99),
(6, 5, 8, 1, 899.99),
(7, 6, 9, 1, 249.99),
(8, 6, 10, 1, 1299.99),
(9, 7, 8, 1, 899.99),
(10, 8, 9, 1, 249.99),
(11, 8, 10, 1, 1299.99),
(12, 9, 12, 1, 749.99),
(13, 10, 8, 1, 899.99),
(14, 11, 8, 1, 899.99),
(15, 12, 8, 1, 899.99),
(16, 13, 8, 2, 899.99),
(17, 13, 12, 1, 749.99),
(18, 14, 8, 2, 899.99),
(19, 14, 9, 1, 249.99);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `stock_quantity`, `image_url`, `category_id`, `created_at`, `image`) VALUES
(8, 'Modern Sofa', 'Comfortable sofa', 899.99, 10, '', 3, '2026-06-13 08:41:10', 'sofa.jpg'),
(9, 'Coffee Table', 'Wooden coffee table', 249.99, 15, '', 4, '2026-06-13 08:41:10', 'table.jpg'),
(10, 'King Size Bed', 'Luxury king-size bed ', 1299.99, 8, '', 5, '2026-06-13 08:41:10', 'bed.jpg'),
(11, 'Office Chair', 'Ergonomic office chair', 199.99, 20, '', 6, '2026-06-13 08:41:10', 'chair.jpg'),
(12, 'Wardrobe', 'Large wooden wardrobe', 749.99, 5, '', 7, '2026-06-13 08:41:10', 'wardrobe.jpg'),
(13, 'Dining Table Set', 'Modern dining table with matching chairs.', 899.99, 8, NULL, 4, '2026-06-18 08:38:46', 'dining.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `phone`, `address`, `role`, `created_at`) VALUES
(1, 'mmm', 'mm@test.com', '$2y$10$MQy.v7Rr0DyKxFvz.4giEe5iWVE.VXtjQwxj6NU02h3cp.Fd13Rpu', '02555', 'fghkksd', 'customer', '2026-06-11 07:30:45'),
(2, 'Yumna AbuMusameh', 'ya.musameh@gmail.com', '$2y$10$PDr471YQz/jen7MQAgbuYer8Lroy.x9kX4Ixt5FH1RL4XhpwXMyJS', '05559988421', 'dfgv', 'admin', '2026-06-17 12:10:34'),
(3, 'naaan', 'naa@test.com', '$2y$10$zudJoNR4H19n1xTNCaUGlOjB25Z/rFyLzbiGUYe4BDsB5e1W4/5He', '055997885464', 'hdsgfj', 'customer', '2026-06-20 08:20:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
