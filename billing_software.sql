-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 27, 2025 at 05:04 AM
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
-- Database: `billing_software`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `customer_name` varchar(200) NOT NULL,
  `customer_address` varchar(250) NOT NULL,
  `customer_number` bigint(15) NOT NULL,
  `customer_pan` bigint(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `user_id`, `created_date`, `customer_name`, `customer_address`, `customer_number`, `customer_pan`) VALUES
(6, 1, '2019-10-02 21:50:00', 'John', 'Wasington DC, USA', 9851674265, 0),
(7, 1, '2019-10-02 21:53:07', 'Sam', 'Germany', 8569745215, 0),
(8, 1, '2019-10-02 21:53:50', 'Dikpal', 'Kathmandu', 9865415786, 0),
(9, 1, '2025-08-01 08:44:26', 'mohammad', 'sevalani', 7383829440, 0);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_order`
--

CREATE TABLE `invoice_order` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_total_before_tax` decimal(10,2) NOT NULL,
  `order_total_tax` decimal(10,2) NOT NULL,
  `order_tax_per` varchar(250) NOT NULL,
  `order_total_after_tax` double(10,2) NOT NULL,
  `order_amount_paid` decimal(10,2) NOT NULL,
  `order_total_amount_due` decimal(10,2) NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `invoice_order`
--

INSERT INTO `invoice_order` (`order_id`, `user_id`, `customer_id`, `order_date`, `order_total_before_tax`, `order_total_tax`, `order_tax_per`, `order_total_after_tax`, `order_amount_paid`, `order_total_amount_due`, `note`) VALUES
(12, 1, 6, '2019-10-02 16:43:38', 2005.00, 260.65, '13', 2265.65, 1000.00, 53.65, ''),
(13, 1, 7, '2025-08-01 03:11:05', 0.00, 0.00, '', 0.00, 0.00, 0.00, ''),
(14, 1, 7, '2025-08-01 03:12:03', 100.00, 18.00, '18', 118.00, 110.00, 8.00, 'son mana apine jajo'),
(15, 1, 7, '2025-08-01 03:45:31', 0.00, 0.00, '', 0.00, 0.00, 0.00, ''),
(16, 1, 7, '2025-08-01 04:14:51', 9.00, 0.00, '', 9.00, 0.00, 9.00, ''),
(17, 1, 6, '2025-08-07 16:20:05', 6118.00, 0.00, '', 6118.00, 0.00, 6118.00, ''),
(18, 1, 9, '2025-08-16 17:28:29', 19700.00, 0.00, '', 19700.00, 0.00, 0.00, 'Aagal na pisa pan baki hata'),
(19, 1, 7, '2025-08-23 10:14:01', 1275.00, 0.00, '', 1275.00, 0.00, 1275.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_order_item`
--

CREATE TABLE `invoice_order_item` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_code` varchar(250) NOT NULL,
  `item_name` varchar(250) NOT NULL,
  `order_item_quantity` decimal(10,2) NOT NULL,
  `order_item_price` decimal(10,2) NOT NULL,
  `order_item_final_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `invoice_order_item`
--

INSERT INTO `invoice_order_item` (`order_item_id`, `order_id`, `item_code`, `item_name`, `order_item_quantity`, `order_item_price`, `order_item_final_amount`) VALUES
(4, 11, '15', 'Apple', 5.00, 50.00, 250.00),
(5, 12, '26', 'Kingfisher', 3.00, 125.00, 375.00),
(6, 12, '28', 'Tuborg', 2.00, 120.00, 240.00),
(7, 12, '29', 'Bira91', 2.00, 220.00, 440.00),
(8, 12, '30', 'Heineken', 5.00, 190.00, 950.00),
(9, 13, '39', '', 0.00, 0.00, 0.00),
(10, 14, '39', 'sino softek', 10.00, 10.00, 100.00),
(11, 15, '38', '', 0.00, 0.00, 0.00),
(12, 16, '38', 'Apsara ereser', 3.00, 3.00, 9.00),
(13, 17, '31', 'Jameson', 2.00, 3059.00, 6118.00),
(14, 18, '41', 'Basmati Rice', 90.00, 120.00, 10800.00),
(15, 18, '42', 'Wheat Flour', 10.00, 45.00, 450.00),
(16, 18, '44', 'Red Lentils', 10.00, 95.00, 950.00),
(17, 18, '60', 'Pasta', 100.00, 75.00, 7500.00),
(18, 19, '39', 'sino softek', 2.00, 10.00, 20.00),
(19, 19, '43', 'Sugar', 11.00, 55.00, 605.00),
(20, 19, '55', 'Almonds', 0.00, 650.00, 650.00),
(21, 19, '', '', 0.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_user`
--

CREATE TABLE `invoice_user` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `mobile` bigint(20) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `invoice_user`
--

INSERT INTO `invoice_user` (`id`, `email`, `password`, `first_name`, `last_name`, `mobile`, `address`) VALUES
(1, 'admin@dev.com', '123456789', 'Admin', 'Developer', 9876543210, 'Hello World');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_number` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_name` varchar(200) NOT NULL,
  `item_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_number`, `user_id`, `item_name`, `item_price`) VALUES
(26, 1, 'Kingfisher', 125.00),
(27, 1, 'Carlsberg', 120.00),
(28, 1, 'Tuborg', 120.00),
(29, 1, 'Bira91', 220.00),
(30, 1, 'Heineken', 190.00),
(31, 1, 'Jameson', 3059.00),
(32, 1, 'Magic Moments', 586.00),
(33, 1, 'Romanov', 310.00),
(34, 1, 'sino softek', 10.00),
(35, 1, 'mouse', 100.00),
(36, 1, 'pencil', 7.00),
(37, 1, 'shapner', 5.00),
(38, 1, 'Apsara ereser', 3.00),
(39, 1, 'sino softek', 10.00),
(40, 1, 'P001', 120.00),
(41, 1, 'Basmati Rice', 120.00),
(42, 1, 'Wheat Flour', 45.00),
(43, 1, 'Sugar', 55.00),
(44, 1, 'Red Lentils', 95.00),
(45, 1, 'Green Tea', 250.00),
(46, 1, 'Cooking Oil', 140.00),
(47, 1, 'Tomatoes', 40.00),
(48, 1, 'Onions', 30.00),
(49, 1, 'Potatoes', 25.00),
(50, 1, 'Chicken Breast', 280.00),
(51, 1, 'Milk Powder', 350.00),
(52, 1, 'Black Pepper', 800.00),
(53, 1, 'Turmeric Powder', 120.00),
(54, 1, 'Salt', 20.00),
(55, 1, 'Almonds', 650.00),
(56, 1, 'Cashews', 750.00),
(57, 1, 'Apples', 80.00),
(58, 1, 'Bananas', 60.00),
(59, 1, 'Orange Juice', 85.00),
(60, 1, 'Pasta', 75.00);

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `receipt_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `Customer_id` int(11) NOT NULL,
  `invoice_id` int(20) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `amount_paid` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `receipt`
--

INSERT INTO `receipt` (`receipt_id`, `user_id`, `Customer_id`, `invoice_id`, `created_date`, `amount_paid`) VALUES
(1, 1, 6, 12, '2019-10-02 16:44:59', 1200.00),
(2, 1, 6, 12, '2025-08-07 16:36:41', 12.00),
(3, 1, 9, 18, '2025-08-16 17:31:25', 19700.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `invoice_order`
--
ALTER TABLE `invoice_order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `invoice_customer_key` (`customer_id`);

--
-- Indexes for table `invoice_order_item`
--
ALTER TABLE `invoice_order_item`
  ADD PRIMARY KEY (`order_item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_number`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD PRIMARY KEY (`receipt_id`),
  ADD KEY `foreign_key` (`invoice_id`),
  ADD KEY `key` (`Customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `invoice_order`
--
ALTER TABLE `invoice_order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `invoice_order_item`
--
ALTER TABLE `invoice_order_item`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
  MODIFY `receipt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice_order`
--
ALTER TABLE `invoice_order`
  ADD CONSTRAINT `invoice_customer_key` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `receipt`
--
ALTER TABLE `receipt`
  ADD CONSTRAINT `foreign_key` FOREIGN KEY (`invoice_id`) REFERENCES `invoice_order` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `key` FOREIGN KEY (`Customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
