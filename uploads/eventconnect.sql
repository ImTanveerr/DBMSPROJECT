-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 26, 2025 at 08:57 PM
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
-- Database: `eventconnect`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `ADMIN_NAME` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `ADMIN_PASS` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `ADMIN_NAME`, `email`, `ADMIN_PASS`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin123', 'admin@example.com', 'password123', 'active', '2025-01-07 18:07:46', '2025-01-07 18:07:46'),
(2, 'john_doe', 'john.doe@example.com', 'johnpassword', 'inactive', '2025-01-07 18:07:46', '2025-01-07 18:07:46'),
(3, 'superuser', 'superuser@example.com', 'superpassword', 'active', '2025-01-07 18:07:46', '2025-01-07 18:07:46'),
(4, 'alice2025', 'alice2025@example.com', 'alicepassword', 'active', '2025-01-07 18:07:46', '2025-01-07 18:07:46'),
(5, 'admin_jane', 'admin.jane@example.com', 'janepassword', 'inactive', '2025-01-07 18:07:46', '2025-01-07 18:07:46'),
(6, 'admin', 'admin@gmail.com', '123456', 'active', '2025-01-07 18:15:06', '2025-01-07 18:15:06');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `duration` int(11) GENERATED ALWAYS AS (to_days(`end_date`) - to_days(`start_date`) + 1) STORED,
  `total_cost` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('confirmed','cancelled','pending') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `service_id`, `service_name`, `user_id`, `start_date`, `end_date`, `total_cost`, `created_at`, `updated_at`, `status`) VALUES
(26, 47, 'Wedding Photography', 7, '2025-01-30', '2025-02-01', 15000.00, '2025-01-24 23:23:17', '2025-01-24 23:26:21', 'confirmed'),
(27, 56, 'Cinematic Vediography', 7, '2025-01-29', '2025-01-30', 24444.00, '2025-01-26 23:50:52', '2025-01-26 23:50:52', 'pending'),
(28, 55, 'Cinematic Videography', 7, '2025-01-21', '2025-01-30', 9990.00, '2025-01-26 23:51:13', '2025-01-26 23:51:13', 'pending'),
(29, 47, 'Wedding Photography', 7, '2025-01-25', '2025-01-29', 25000.00, '2025-01-27 01:40:30', '2025-01-27 01:40:30', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `total_cost` decimal(10,2) DEFAULT NULL,
  `status` enum('in_cart','booked') DEFAULT 'in_cart',
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `invoice_date` datetime DEFAULT current_timestamp(),
  `payment_method` enum('cash','card','online') NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `invoice_status` enum('paid','unpaid','cancelled') DEFAULT 'unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manageservices`
--

CREATE TABLE `manageservices` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `service_date` datetime DEFAULT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `organizer` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `service_category` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manageservices`
--

INSERT INTO `manageservices` (`id`, `service_name`, `description`, `cost`, `service_date`, `venue`, `organizer`, `status`, `created_at`, `updated_at`, `service_category`, `image_path`) VALUES
(47, 'Wedding Photography', 'Capture the most memorable moments of your wedding day with professional photography services.', 5000.00, '2025-06-15 09:00:00', 'Rose Garden Resort', 6, 'approved', '2025-01-24 17:10:51', '2025-01-26 15:14:19', 'Photography', NULL),
(48, 'Catering Service', 'Delicious food for your event, from appetizers to desserts. We cater to all dietary preferences.', 3000.00, '2025-06-15 10:00:00', 'Blue Sky Banquet Hall', 6, 'approved', '2025-01-24 17:10:51', '2025-01-26 15:14:20', 'Catering', NULL),
(49, 'Event Decoration', 'Transform your venue with stunning decor. From flowers to centerpieces, we create magical atmospheres.', 1500.00, '2025-06-15 09:00:00', 'City Convention Center', 6, 'approved', '2025-01-24 17:10:51', '2025-01-26 15:14:20', 'Decoration', NULL),
(50, 'Music Band', 'Live music to set the perfect mood for your event. Our band specializes in weddings, corporate events, and private parties.', 2500.00, '2025-06-16 20:00:00', 'Sunset Garden', 6, 'approved', '2025-01-24 17:10:51', '2025-01-26 16:47:06', 'Entertainment', NULL),
(51, 'Venue Rental', 'Spacious venue for weddings, parties, and corporate events. Includes tables, chairs, and basic decoration.', 10000.00, '2025-07-01 09:00:00', 'Grand Palace Hotel', 6, 'approved', '2025-01-24 17:10:51', '2025-01-26 16:47:07', 'Venue', NULL),
(52, 'Car Rental', 'Rent luxury cars for your special event. We offer a wide selection of vehicles including limousines, sports cars, and more.', 2000.00, '2025-06-10 10:00:00', 'Royal Car Rentals', 6, 'approved', '2025-01-24 17:10:51', '2025-01-26 16:47:08', 'Transport', NULL),
(53, 'DJ Service', 'Professional DJ services for weddings, parties, and corporate events. We provide sound systems and music tailored to your event.', 1800.00, '2025-06-20 18:00:00', 'City Party Hall', 6, 'approved', '2025-01-24 17:10:51', '2025-01-26 17:49:36', 'Entertainment', NULL),
(54, 'Makeup Artist', 'Expert makeup services for brides, guests, and special occasions. We provide on-site makeup application.', 1500.00, '2025-06-14 08:00:00', 'Beauty Studio', 6, 'approved', '2025-01-24 17:10:51', '2025-01-24 17:10:51', 'Beauty', NULL),
(55, 'Videography', 'the art of creating visually striking videos that evoke strong emotions and immerse viewers in a story', 999.00, '2025-02-06 23:44:00', 'Gulshan Part', 8, 'approved', '2025-01-26 17:44:40', '2025-01-26 19:05:06', 'Videography', NULL),
(56, 'Cinematic Vediography', 'Videography', 100.00, '2025-01-27 17:45:00', 'Gulshan boating club', 8, 'rejected', '2025-01-26 17:45:11', '2025-01-26 19:08:57', 'Videography', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `partner_id` int(11) NOT NULL,
  `partner_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(15) NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `partner_pass` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`partner_id`, `partner_name`, `email`, `contact_phone`, `service_type`, `description`, `status`, `created_at`, `updated_at`, `partner_pass`) VALUES
(6, 'Akram', 'info@eventfuldecor.com', '1885648257', 'Decoration', 'We specialize in creating stunning event decorations including floral arrangements, themed setups, and lighting designs.', 'active', '2025-01-07 17:16:32', '2025-01-26 19:45:02', 'akram'),
(7, 'Samir', 'tanveer.mt129@gmail.com', '1874935346', 'photogrphy', 'creating stunning event  ', 'active', '2025-01-26 15:41:31', '2025-01-26 19:45:33', '1234'),
(8, 'tanveer', 'iamtanveer@yahoo.com', '1874930346', 'Cateringg', ' providing food and drink for a large number of people', 'active', '2025-01-26 15:43:12', '2025-01-26 19:08:29', '1234'),
(9, 'partner', 'tanvee9@gmail.com', '1874935344', 'photogrphy', 'We specialize in creating stunning event decorations including floral arrangements, themed setups, and lighting designs.', 'active', '2025-01-26 15:47:37', '2025-01-26 19:45:38', '1234'),
(10, 'Maruf', 'maruf@gmail.com', '1874936854', 'photogrphy', 'We specialize in creating stunning event decorations including floral arrangements, themed setups, and lighting designs.', 'active', '2025-01-26 15:58:14', '2025-01-26 19:08:30', '1234'),
(11, 'Maruf1', 'mauffr@gmail.com', '1874935322', 'photogrphy', 'We specialize in creating stunning event decorations including floral arrangements, themed setups, and lighting designs.', 'active', '2025-01-26 16:01:49', '2025-01-26 19:08:30', '1234'),
(12, 'Maruf12', 'mauff2r@gmail.com', '1874935222', 'photogrphy', 'We specialize in creating stunning event decorations including floral arrangements, themed setups, and lighting designs.', 'active', '2025-01-26 16:02:26', '2025-01-26 19:08:30', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','banned','pending') NOT NULL DEFAULT 'pending',
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `status`, `reg_date`) VALUES
(7, 'Akram', 'Tanveer', 'user01', 'user01@gmail.com', '123456', 'banned', '2024-11-05 20:06:37'),
(1013, 'Akram Hossain', 'Tanveer', 'ADMIN001', 'tanveer.mt129@gmail.com', 'ADMIN001', 'banned', '2024-11-12 13:40:21'),
(1014, 'Akram Hossain', 'Tanveer', 'admin', 'mtanveer2231023@bscse.uiu.ac.bd', 'mtanveer2231023@bscse.uiu.ac.bd', 'pending', '2025-01-24 17:29:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`ADMIN_NAME`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `manageservices`
--
ALTER TABLE `manageservices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_organizer` (`organizer`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`partner_id`),
  ADD UNIQUE KEY `contact_email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `manageservices`
--
ALTER TABLE `manageservices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `partner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1015;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `manageservices` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `manageservices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `manageservices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `manageservices`
--
ALTER TABLE `manageservices`
  ADD CONSTRAINT `fk_organizer` FOREIGN KEY (`organizer`) REFERENCES `partners` (`partner_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
