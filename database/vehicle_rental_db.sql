-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 06, 2025 at 04:55 AM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vehicle_rental_db`
--
CREATE DATABASE IF NOT EXISTS `vehicle_rental_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `vehicle_rental_db`;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `rental_id` int NOT NULL,
  `amount` decimal(10,2) DEFAULT '0.00',
  `method` varchar(50) DEFAULT NULL,
  `reference_note` text,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`),
  KEY `rental_id` (`rental_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `rental_id`, `amount`, `method`, `reference_note`, `status`, `created_at`) VALUES
(2, 3, 0.00, 'Cash', 'zdfg', 'Pending', '2025-04-22 08:36:16'),
(3, 7, 0.00, 'Cash', '', 'Pending', '2025-04-22 11:33:24'),
(4, 8, 0.00, 'Cash', '', 'Pending', '2025-04-22 11:33:54'),
(5, 10, 45.00, 'Cash', '', 'Pending', '2025-04-22 11:53:03'),
(6, 10, 45.00, 'Cash', '', 'Pending', '2025-04-22 11:56:09'),
(7, 12, 750.00, 'Cash', '', 'Approved', '2025-04-22 12:17:21'),
(8, 8, 90.00, 'Cash', '', 'Pending', '2025-04-22 12:17:38'),
(9, 14, 135.00, 'Cash', '345', 'Approved', '2025-04-23 08:03:08'),
(13, 1, 45.00, 'Cash', '', 'Pending', '2025-04-23 12:30:29');

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

DROP TABLE IF EXISTS `rentals`;
CREATE TABLE IF NOT EXISTS `rentals` (
  `rental_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `vehicle_id` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('Pending','Approved','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`rental_id`),
  KEY `user_id` (`user_id`),
  KEY `vehicle_id` (`vehicle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`rental_id`, `user_id`, `vehicle_id`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, 3, 1, '2025-04-23', '2025-04-23', 'Pending', '2025-04-22 08:04:20'),
(2, 3, 1, '2025-04-22', '2025-04-22', 'Cancelled', '2025-04-22 08:31:59'),
(3, 3, 1, '2025-04-22', '2025-04-22', 'Cancelled', '2025-04-22 08:36:08'),
(4, 3, 2, '2025-04-22', '2025-04-25', 'Cancelled', '2025-04-22 08:37:04'),
(5, 3, 1, '2025-04-22', '2025-04-22', 'Cancelled', '2025-04-22 08:50:38'),
(6, 3, 1, '2025-04-22', '2025-04-24', 'Cancelled', '2025-04-22 11:31:10'),
(7, 3, 1, '2025-04-22', '2025-04-24', 'Cancelled', '2025-04-22 11:31:42'),
(8, 3, 1, '2025-04-22', '2025-04-24', 'Cancelled', '2025-04-22 11:33:51'),
(9, 3, 1, '2025-04-22', '2025-04-23', 'Cancelled', '2025-04-22 11:52:49'),
(10, 3, 1, '2025-04-22', '2025-04-23', 'Cancelled', '2025-04-22 11:52:53'),
(11, 3, 1, '2025-04-22', '2025-04-25', 'Cancelled', '2025-04-22 11:56:33'),
(12, 3, 2, '2025-04-22', '2025-05-07', 'Pending', '2025-04-22 11:59:55'),
(13, 3, 1, '2025-04-23', '2025-04-24', 'Cancelled', '2025-04-23 06:47:24'),
(14, 3, 1, '2025-04-20', '2025-04-22', 'Approved', '2025-04-23 07:38:26'),
(15, 3, 1, '2025-04-27', '2025-04-28', 'Cancelled', '2025-04-23 08:17:07');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `vehicle_id` int NOT NULL,
  `rating` int NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  UNIQUE KEY `unique_review` (`user_id`,`vehicle_id`),
  KEY `vehicle_id` (`vehicle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `vehicle_id`, `rating`, `comment`, `created_at`) VALUES
(1, 3, 1, 5, 'dsfgs', '2025-04-22 09:03:34'),
(2, 4, 1, 4, 'Nice Car', '2025-04-23 06:18:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `license_number` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `status` enum('pending','active','inactive') DEFAULT 'pending',
  `role` enum('user','admin') DEFAULT 'user',
  `verified` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `contact`, `license_number`, `password`, `token`, `status`, `role`, `verified`, `created_at`) VALUES
(2, 'Aashish Roka', 'aashish.roka64@gmail.com', '0450592357', 'D12345678', '$2y$10$43c9esjirDA8DDZ.SVNWZu/IMsO0K0YjQzrFY10uWYFGC5T9EZIOa', NULL, 'active', 'admin', 1, '2025-04-17 12:05:29'),
(3, 'Aashish Roka', 'q@gmail.com', '0450592357', 'D12345678', '$2y$10$zgU6IKztnC3MLJ3WUkfAUOk4xVdJRkaSVN.mI9RrFcs1TR.TJ9f2i', NULL, 'active', 'user', 1, '2025-04-17 12:27:33'),
(4, 'Hello World', 'hello@gmail.com', '0450592357', 'D12345678', '$2y$10$KewK2gn4G85uJW4Eig4beOEQmw48zbE2wrvFc.p.PLyV06zuGKUhS', NULL, 'active', 'user', 1, '2025-04-17 13:01:58');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE IF NOT EXISTS `vehicles` (
  `vehicle_id` int NOT NULL AUTO_INCREMENT,
  `model` varchar(100) NOT NULL,
  `type` enum('Car','Bike','Van','Truck') NOT NULL,
  `rental_price` decimal(10,2) NOT NULL,
  `availability` enum('Available','Unavailable') DEFAULT 'Available',
  `image` varchar(255) DEFAULT 'default.jpg',
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`vehicle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `model`, `type`, `rental_price`, `availability`, `image`, `description`, `created_at`) VALUES
(1, 'Toyota Corolla', 'Car', 45.00, 'Available', 'corolla.png', 'A reliable and fuel-efficient sedan, perfect for city drives and long trips.', '2025-04-22 05:17:56'),
(2, 'Honda Civic', 'Car', 50.00, 'Available', 'civic.jpg', 'Sporty and efficient, the Civic is known for its smooth handling.', '2025-04-22 05:17:56'),
(3, 'Hyundai Elantra', 'Car', 42.00, 'Available', 'elantra.jpg', 'Comfortable and compact sedan with great mileage and space.', '2025-04-22 05:17:56'),
(4, 'BMW 3 Series', 'Car', 120.00, 'Unavailable', 'bmw_3.jpg', 'Luxury sedan with top-tier features and performance.', '2025-04-22 05:17:56'),
(5, 'Tesla Model 3', 'Car', 150.00, 'Available', 'tesla_model3.jpg', 'All-electric, autopilot-ready vehicle with impressive range.', '2025-04-22 05:17:56'),
(6, 'Honda CBR 250R', 'Bike', 30.00, 'Available', 'cbr250.jpg', 'A powerful sports bike perfect for weekend getaways and solo rides.', '2025-04-22 05:17:56'),
(7, 'Royal Enfield Classic 350', 'Bike', 28.00, 'Available', 'classic350.jpg', 'Iconic cruiser motorcycle known for its rugged design.', '2025-04-22 05:17:56'),
(8, 'Yamaha MT-15', 'Bike', 25.00, 'Available', 'mt15.jpg', 'Lightweight and agile streetfighter-style bike for quick commutes.', '2025-04-22 05:17:56'),
(9, 'KTM Duke 390', 'Bike', 35.00, 'Unavailable', 'duke390.jpg', 'Performance-focused motorcycle for thrill seekers.', '2025-04-22 05:17:56'),
(10, 'Bajaj Pulsar 220F', 'Bike', 20.00, 'Available', 'pulsar220.jpg', 'Budget-friendly and powerful commuter bike.', '2025-04-22 05:17:56'),
(11, 'Ford Transit Van', 'Van', 80.00, 'Available', 'transit.jpg', 'Spacious and sturdy cargo van ideal for transporting goods.', '2025-04-22 05:17:56'),
(12, 'Mercedes-Benz Sprinter', 'Van', 95.00, 'Unavailable', 'sprinter.jpg', 'Luxury van with excellent capacity and comfort.', '2025-04-22 05:17:56'),
(13, 'Toyota HiAce', 'Van', 85.00, 'Available', 'hiace.jpg', 'Reliable van suited for business or travel.', '2025-04-22 05:17:56'),
(14, 'Volkswagen Transporter', 'Van', 90.00, 'Available', 'transporter.jpg', 'Versatile commercial vehicle with smooth performance.', '2025-04-22 05:17:56'),
(15, 'Nissan NV350', 'Van', 75.00, 'Available', 'nv350.jpg', 'Practical and affordable van with great load space.', '2025-04-22 05:17:56');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`rental_id`);

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rentals_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
