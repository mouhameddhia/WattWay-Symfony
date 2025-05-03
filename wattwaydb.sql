-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2025 at 06:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wattwaydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `function_admin` varchar(100) NOT NULL,
  `name_admin` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

CREATE TABLE `assignment` (
  `idAssignment` int(11) NOT NULL,
  `descriptionAssignment` varchar(500) NOT NULL,
  `statusAssignment` varchar(30) NOT NULL,
  `idCar` int(11) NOT NULL,
  `dateAssignment` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignment`
--

INSERT INTO `assignment` (`idAssignment`, `descriptionAssignment`, `statusAssignment`, `idCar`, `dateAssignment`) VALUES
(110, 'Oil change and filter replacement', 'Pending', 1, '2024-04-15 09:00:00'),
(111, 'Brake system inspection and maintenance', 'In Progress', 2, '2024-04-16 10:30:00'),
(112, 'Engine diagnostic and tune-up', 'Completed', 3, '2024-04-14 14:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `assignment_mechanics`
--

CREATE TABLE `assignment_mechanics` (
  `assignment_id` int(11) NOT NULL,
  `mechanic_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assignment_mechanics`
--

INSERT INTO `assignment_mechanics` (`assignment_id`, `mechanic_id`) VALUES
(110, 902),
(111, 902),
(112, 902);

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `id_bill` int(11) NOT NULL,
  `date_bill` date NOT NULL,
  `total_amount_bill` double NOT NULL,
  `id_car` int(11) NOT NULL,
  `status_bill` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `idCar` int(11) NOT NULL,
  `status_car` varchar(20) NOT NULL,
  `vin_code` varchar(17) NOT NULL,
  `id_warehouse` int(11) NOT NULL,
  `model_car` varchar(100) NOT NULL,
  `brand_car` varchar(100) NOT NULL,
  `year_car` int(11) NOT NULL,
  `price_car` double NOT NULL,
  `kilometrage_car` double NOT NULL,
  `img_car` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`idCar`, `status_car`, `vin_code`, `id_warehouse`, `model_car`, `brand_car`, `year_car`, `price_car`, `kilometrage_car`, `img_car`) VALUES
(1, '', '', 0, '', '', 0, 0, 0, ''),
(2, '', '', 0, '', '', 0, 0, 0, ''),
(3, '', '', 0, '', '', 0, 0, 0, ''),
(4, '', '', 0, '', '', 0, 0, 0, ''),
(5, '', '', 0, '', '', 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250409191059', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id_feedback` int(11) NOT NULL,
  `content_feedback` longtext NOT NULL,
  `rating_feedback` int(11) NOT NULL,
  `date_feedback` datetime NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id_item` int(11) NOT NULL,
  `name_item` varchar(255) NOT NULL,
  `quantity_item` int(11) NOT NULL,
  `price_per_unit_item` double NOT NULL,
  `category_item` varchar(100) NOT NULL,
  `orderId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mechanic`
--

CREATE TABLE `mechanic` (
  `idMechanic` int(11) NOT NULL,
  `nameMechanic` varchar(255) NOT NULL,
  `specialityMechanic` varchar(255) NOT NULL,
  `imgMechanic` varchar(255) DEFAULT NULL,
  `emailMechanic` varchar(255) NOT NULL,
  `carsRepaired` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mechanic`
--

INSERT INTO `mechanic` (`idMechanic`, `nameMechanic`, `specialityMechanic`, `imgMechanic`, `emailMechanic`, `carsRepaired`) VALUES
(11, 'name mechanic', 'ELECTRICIAN', NULL, '', 3),
(12, 'namemec', 'MECHANIC', NULL, '', 3),
(14, 'John Doe', 'ELECTRICIAN', NULL, '', 0),
(15, 'Jane Smith', 'MECHANIC', NULL, '', 1),
(16, 'Michael Johnson', 'SOFTWARE', NULL, '', 1),
(17, 'Emily Davis', 'ELECTRICIAN', NULL, '', 0),
(18, 'Daniel Brown', 'MECHANIC', NULL, '', 0),
(19, 'Jessica Wilson', 'SOFTWARE', NULL, '', 0),
(20, 'Christopher Martinez', 'ELECTRICIAN', NULL, '', 0),
(21, 'Sarah Taylor', 'MECHANIC', NULL, '', 1),
(22, 'David Anderson', 'SOFTWARE', NULL, '', 0),
(24, 'James White', 'MECHANIC', NULL, '', 0),
(25, 'Sophia Harris', 'SOFTWARE', NULL, '', 0),
(26, 'Robert Clark', 'ELECTRICIAN', NULL, '', 0),
(27, 'Olivia Lewis', 'MECHANIC', NULL, '', 0),
(28, 'William Walker', 'SOFTWARE', NULL, 'email@m.fr', 0),
(901, 'TBD', 'ngnrk', NULL, 'evkj', 4),
(902, 'ninja', 'software', '67fbe482b863b.jpg', 'hind.chouaib@esprit.tn', 8);

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id_order` int(11) NOT NULL,
  `supplier_order` varchar(255) NOT NULL,
  `date_order` varchar(55) NOT NULL,
  `total_amount_order` double NOT NULL,
  `status_order` varchar(255) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `address_supplier_order` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `response`
--

CREATE TABLE `response` (
  `id_response` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `date_response` date NOT NULL,
  `type_response` varchar(255) NOT NULL,
  `idUser` int(11) DEFAULT NULL,
  `idSubmission` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `submission`
--

CREATE TABLE `submission` (
  `id_submission` int(11) NOT NULL,
  `description` longtext NOT NULL,
  `status` varchar(255) NOT NULL,
  `urgency_level` varchar(255) NOT NULL,
  `date_submission` date NOT NULL,
  `id_car` int(11) NOT NULL,
  `last_modified` datetime NOT NULL,
  `preferred_contact_method` varchar(50) NOT NULL,
  `preferred_appointment_date` date NOT NULL,
  `idUser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `email_user` varchar(100) NOT NULL,
  `password_user` varchar(255) NOT NULL,
  `first_name_user` varchar(50) NOT NULL,
  `last_name_user` varchar(50) NOT NULL,
  `role_user` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `payment_details` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `function_admin` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `id_warehouse` int(11) NOT NULL,
  `capacity_warehouse` int(11) NOT NULL,
  `city` varchar(100) NOT NULL,
  `street` varchar(255) NOT NULL,
  `postal_code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `assignment`
--
ALTER TABLE `assignment`
  ADD PRIMARY KEY (`idAssignment`),
  ADD KEY `IDX_30C544BA5675FB1A` (`idCar`);

--
-- Indexes for table `assignment_mechanics`
--
ALTER TABLE `assignment_mechanics`
  ADD PRIMARY KEY (`assignment_id`,`mechanic_id`),
  ADD KEY `IDX_FFB535AAD19302F8` (`assignment_id`),
  ADD KEY `IDX_FFB535AA9A67DB00` (`mechanic_id`);

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`id_bill`);

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`idCar`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id_feedback`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `IDX_1F1B251EFA237437` (`orderId`);

--
-- Indexes for table `mechanic`
--
ALTER TABLE `mechanic`
  ADD PRIMARY KEY (`idMechanic`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id_order`);

--
-- Indexes for table `response`
--
ALTER TABLE `response`
  ADD PRIMARY KEY (`id_response`),
  ADD KEY `IDX_3E7B0BFBFE6E88D7` (`idUser`),
  ADD KEY `IDX_3E7B0BFB59F14419` (`idSubmission`);

--
-- Indexes for table `submission`
--
ALTER TABLE `submission`
  ADD PRIMARY KEY (`id_submission`),
  ADD KEY `IDX_DB055AF3FE6E88D7` (`idUser`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`id_warehouse`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignment`
--
ALTER TABLE `assignment`
  MODIFY `idAssignment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `mechanic`
--
ALTER TABLE `mechanic`
  MODIFY `idMechanic` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=903;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignment`
--
ALTER TABLE `assignment`
  ADD CONSTRAINT `fk_assignment_car` FOREIGN KEY (`idCar`) REFERENCES `car` (`idCar`) ON DELETE CASCADE;

--
-- Constraints for table `assignment_mechanics`
--
ALTER TABLE `assignment_mechanics`
  ADD CONSTRAINT `FK_FFB535AA9A67DB00` FOREIGN KEY (`mechanic_id`) REFERENCES `mechanic` (`idMechanic`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_FFB535AAD19302F8` FOREIGN KEY (`assignment_id`) REFERENCES `assignment` (`idAssignment`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
