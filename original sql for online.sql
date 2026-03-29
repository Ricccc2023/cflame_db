-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2026 at 09:50 PM
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
-- Database: `cflame_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `archive_users`
--

CREATE TABLE `archive_users` (
  `id` int(11) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `availability` int(11) DEFAULT NULL,
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archive_users`
--

INSERT INTO `archive_users` (`id`, `fullname`, `username`, `password`, `role`, `availability`, `archived_at`) VALUES
(6, 'qweqwe', 'qweqwe', 'qweqwe', 'staff', 0, '2026-03-10 09:30:37'),
(5, 'qwer qwer', 'qwer qwer', 'qwer qwer', 'staff', 0, '2026-03-10 09:30:40');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('IN','OUT') NOT NULL,
  `time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `type`, `time`) VALUES
(1, 2, 'IN', '2026-03-09 02:43:19'),
(2, 1, 'IN', '2026-03-09 02:43:22'),
(3, 1, 'OUT', '2026-03-09 02:43:27'),
(4, 2, 'OUT', '2026-03-09 04:40:07'),
(5, 2, 'IN', '2026-03-09 04:40:24'),
(6, 2, 'OUT', '2026-03-09 04:40:29'),
(7, 2, 'IN', '2026-03-09 05:58:11'),
(8, 2, 'OUT', '2026-03-09 18:36:23'),
(9, 2, 'IN', '2026-03-09 18:36:47'),
(10, 2, 'OUT', '2026-03-09 18:36:49'),
(11, 4, 'IN', '2026-03-10 21:12:39'),
(12, 2, 'IN', '2026-03-10 21:12:42'),
(13, 4, 'OUT', '2026-03-11 13:54:19'),
(14, 4, 'IN', '2026-03-11 13:54:23'),
(15, 4, 'OUT', '2026-03-14 01:51:22'),
(16, 2, 'OUT', '2026-03-14 01:51:24'),
(17, 4, 'IN', '2026-03-14 01:51:25'),
(18, 2, 'IN', '2026-03-14 01:51:26');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_name`, `contact`, `address`, `created_at`) VALUES
(1, 'ABC Corporation', '09171234567', 'Laguna Industrial Park', '2026-03-07 19:59:43'),
(2, 'Global Tech Manufacturing', '09182345678', 'Technopark Industrial Estate, Biñan Laguna', '2026-03-08 18:54:07'),
(3, 'San Pedro Commercial Center', '09193456789', 'National Highway, San Pedro Laguna', '2026-03-08 18:54:07'),
(4, 'Laguna Medical Supply', '09204567891', 'Pulo Diezmo Road, Cabuyao Laguna', '2026-03-08 18:54:07'),
(5, 'Audra Salas', 'Quidem nulla ratione', 'Ea atque illo dolor ', '2026-03-08 21:35:47'),
(7, 'The Vineyard', '09854002367', 'Brgy. Gonzales', '2026-03-08 22:24:25');

-- --------------------------------------------------------

--
-- Table structure for table `declined_orders_archive`
--

CREATE TABLE `declined_orders_archive` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `declined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `declined_orders_archive`
--

INSERT INTO `declined_orders_archive` (`id`, `customer_name`, `contact`, `address`, `product_id`, `quantity`, `message`, `declined_at`) VALUES
(1, 'Ric Ilagan', '09854002367', 'Trapiche Tanauan City Batangs', NULL, NULL, '', '2026-03-26 11:35:27');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `invoice_no` varchar(50) DEFAULT NULL,
  `printed_time` datetime DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `mode_of_payment` varchar(50) DEFAULT NULL,
  `receipt_image` varchar(255) DEFAULT NULL,
  `payment_status` enum('paid','unpaid') NOT NULL DEFAULT 'unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `order_date`, `total`, `created_at`, `invoice_no`, `printed_time`, `total_amount`, `mode_of_payment`, `receipt_image`, `payment_status`) VALUES
(32, 2, '2026-03-15', 0.00, '2026-03-13 17:50:51', 'INV-20260313-32', NULL, NULL, 'GCash', '1773424251_gcash receipt.png', 'paid'),
(33, 1, '2026-03-14', 7500.00, '2026-03-13 19:03:21', 'INV-TEST-001', NULL, NULL, NULL, NULL, 'paid'),
(34, 2, '2026-03-14', 12000.00, '2026-03-13 19:03:21', 'INV-TEST-002', NULL, NULL, NULL, NULL, 'paid'),
(35, 3, '2026-03-13', 3200.00, '2026-03-13 19:03:21', 'INV-TEST-003', NULL, NULL, NULL, NULL, 'paid'),
(36, 2, '2026-03-12', 15000.00, '2026-03-13 19:03:21', 'INV-TEST-004', NULL, NULL, NULL, NULL, 'paid'),
(37, 4, '2026-03-11', 8000.00, '2026-03-13 19:03:21', 'INV-TEST-005', NULL, NULL, NULL, NULL, 'paid'),
(38, NULL, '2026-03-29', 0.00, '2026-03-29 19:14:38', 'INV-20260329-38', NULL, NULL, 'Cash', NULL, 'unpaid'),
(39, NULL, '2026-03-29', 96000.00, '2026-03-29 19:15:00', 'INV-20260329-39', NULL, NULL, 'Cash', NULL, 'unpaid'),
(40, NULL, '2026-03-29', 180000.00, '2026-03-29 19:18:23', 'INV-20260329-40', NULL, NULL, 'Cash', NULL, 'unpaid'),
(41, NULL, '2026-03-29', 0.00, '2026-03-29 19:25:09', 'INV-20260329-41', NULL, NULL, 'Cash', NULL, 'unpaid');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `subtotal`) VALUES
(35, 39, 5, 12, 8000.00, 96000.00),
(36, 40, 4, 12, 15000.00, 180000.00),
(51, 41, 4, 5, 15000.00, 75000.00);

-- --------------------------------------------------------

--
-- Table structure for table `pending_orders`
--

CREATE TABLE `pending_orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `mode_of_payment` varchar(50) DEFAULT NULL,
  `receipt_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pending_order_items`
--

CREATE TABLE `pending_order_items` (
  `id` int(11) NOT NULL,
  `pending_order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_order_items`
--

INSERT INTO `pending_order_items` (`id`, `pending_order_id`, `product_id`, `quantity`, `price`, `subtotal`) VALUES
(3, 3, 3, 1000, 2500.00, 2500000.00),
(12, 10, 7, 1, 1800.00, 1800.00),
(13, 12, 3, 1, 2500.00, 2500.00),
(14, 13, 6, 1000, 3200.00, 3200000.00),
(15, 13, 5, 1, 8000.00, 8000.00),
(16, 14, 3, 1, 2500.00, 2500.00),
(19, 17, 6, 1, 3200.00, 3200.00),
(20, 18, 5, 1, 8000.00, 8000.00),
(21, 19, 6, 1, 3200.00, 3200.00),
(22, 20, 6, 1, 3200.00, 3200.00),
(23, 21, 6, 1, 3200.00, 3200.00),
(24, 22, 6, 1, 3200.00, 3200.00),
(27, 25, 7, 1, 1800.00, 1800.00),
(35, 32, 6, 1, 3200.00, 3200.00),
(36, 32, 4, 1, 15000.00, 15000.00),
(37, 32, 5, 1, 8000.00, 8000.00),
(38, 32, 7, 1, 1800.00, 1800.00),
(39, 32, 3, 1, 2500.00, 2500.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model_number` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(20) DEFAULT NULL,
  `storage_location` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Available',
  `date_purchased` date DEFAULT NULL,
  `last_inspection_date` date DEFAULT NULL,
  `next_inspection_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_id`, `product_name`, `category`, `brand`, `model_number`, `quantity`, `price`, `unit`, `storage_location`, `status`, `date_purchased`, `last_inspection_date`, `next_inspection_date`, `created_at`) VALUES
(3, 'FE-001', 'ABC Dry Chemical Fire Extinguisher', 'Fire Extinguisher', 'Kidde', 'FX-ABC-10', 1, 2500.00, 'pcs', 'Building A - Safety Room', 'Available', '2024-01-15', '2025-01-10', '2026-01-10', '2026-03-07 18:30:31'),
(4, 'FA-001', 'Addressable Fire Alarm Control Panel', 'Fire Alarm', 'Honeywell', 'HFA-3200', 83, 15000.00, 'units', 'Main Control Room', 'In Use', '2023-08-10', '2025-02-05', '2026-02-05', '2026-03-07 18:30:31'),
(5, 'PPE-001', 'Firefighter Protective Suit', 'PPE', 'Dräger', 'FPS-7000', 86, 8000.00, 'sets', 'Warehouse B - Locker Area', 'Available', '2024-05-20', '2025-03-01', '2026-03-01', '2026-03-07 18:30:31'),
(6, 'FE-002', 'Carbon Dioxide Fire Extinguisher', 'Fire Extinguisher', 'Kidde', 'CO2-5LB', 98, 3200.00, 'pcs', 'Building A - Safety Room', 'Available', '2025-02-10', '2026-02-01', '2027-02-01', '2026-03-07 19:37:54'),
(7, 'FD-001', 'Smoke Fire Detector', 'Fire Detector', 'Honeywell', 'SD-100', 99, 1800.00, 'pcs', 'Warehouse B - Shelf 3', 'Available', '2025-05-15', '2026-05-10', '2027-05-10', '2026-03-07 19:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `availability` int(11) DEFAULT 0,
  `per_day` decimal(10,2) DEFAULT 500.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `password`, `role`, `availability`, `per_day`) VALUES
(1, 'Administrator', 'admin', 'admin123', 'admin', 0, 500.00),
(2, 'Winnie Kaith Gnilo', 'gnilo', 'gnilo', 'staff', 0, 500.00),
(4, 'Renjie Escultura', 'escultura', 'escultura', 'staff', 0, 400.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_customer` (`customer_name`,`contact`);

--
-- Indexes for table `declined_orders_archive`
--
ALTER TABLE `declined_orders_archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_no` (`invoice_no`),
  ADD KEY `fk_customer` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order` (`order_id`),
  ADD KEY `fk_product` (`product_id`);

--
-- Indexes for table `pending_orders`
--
ALTER TABLE `pending_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_order_items`
--
ALTER TABLE `pending_order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`),
  ADD UNIQUE KEY `product_id_2` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `declined_orders_archive`
--
ALTER TABLE `declined_orders_archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `pending_orders`
--
ALTER TABLE `pending_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `pending_order_items`
--
ALTER TABLE `pending_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
