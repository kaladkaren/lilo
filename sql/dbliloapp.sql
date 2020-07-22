-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2020 at 12:44 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbliloapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(9) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `super_admin` int(1) NOT NULL DEFAULT 0,
  `is_deleted` int(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `super_admin`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Elline Ocampo', 'edocampo@myoptimind.com', '$2y$10$XqEnkMdX015LsSekTnhxOu9DLSyHVc0n50Cp.V7synSJddfmzJhYe', 1, 0, '2020-06-29 16:42:00', '2020-07-21 09:23:11'),
(2, 'Steven Moses', 'foxuja@mailinator.com', '$2y$10$5Maap7GS3ABucg9w5uuoceNESUamL/7yP6C4QhwZczcr0./5aIeSW', 0, 1, '2020-07-20 13:11:42', '2020-07-20 13:12:11'),
(3, 'Shaeleigh Pugh', 'todirihyca@mailinator.com', '$2y$10$zSE8pG/uPrBGmsatTbGOS.DBP6ecQusZaXnJboit6S3idBbtDzTv6', 0, 0, '2020-07-20 13:12:28', '2020-07-20 13:33:10');

-- --------------------------------------------------------

--
-- Table structure for table `agency`
--

CREATE TABLE `agency` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agency`
--

INSERT INTO `agency` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'DTI', 1, '2020-07-20 16:26:11', '0000-00-00 00:00:00'),
(2, 'DOST', 1, '2020-07-20 16:26:19', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `cesbie_visitors`
--

CREATE TABLE `cesbie_visitors` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `temperature` varchar(300) NOT NULL,
  `place_of_origin` varchar(300) NOT NULL,
  `pin_code` varchar(300) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cesbie_visitors`
--

INSERT INTO `cesbie_visitors` (`id`, `staff_id`, `temperature`, `place_of_origin`, `pin_code`, `created_at`, `updated_at`) VALUES
(1, 1, '37.5', 'San Mateo, Rizal', 'zXc12365gfd', '2020-07-22 13:50:23', '0000-00-00 00:00:00'),
(2, 1, '37.5', 'San Mateo, Rizal', 'zXc12365gfd', '2020-07-22 13:52:22', '0000-00-00 00:00:00'),
(3, 1, '37.5', 'San Mateo, Rizal', 'zXc12365gfd', '2020-07-22 13:57:43', '0000-00-00 00:00:00'),
(4, 1, '37.5', 'San Mateo, Rizal', 'zXc12365gfd', '2020-07-22 13:58:01', '0000-00-00 00:00:00'),
(5, 1, '37.5', 'San Mateo, Rizal', 'zXc12365gfd', '2020-07-22 13:58:38', '0000-00-00 00:00:00'),
(6, 1, '37.5', 'San Mateo, Rizal', 'zXc12365gfd', '2020-07-22 13:58:56', '0000-00-00 00:00:00'),
(7, 1, '37.5', 'San Mateo, Rizal', 'zXc12365gfd', '2020-07-22 14:00:45', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `division`
--

CREATE TABLE `division` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `division`
--

INSERT INTO `division` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Dost', 0, '2020-07-20 15:01:44', '0000-00-00 00:00:00'),
(2, 'DFA', 1, '2020-07-20 15:04:54', '0000-00-00 00:00:00'),
(3, 'DepEd', 1, '2020-07-20 15:43:36', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `pin_code` varchar(300) NOT NULL,
  `overall_experience` int(1) NOT NULL COMMENT '3 = good, 2 = okay, 1 = bad',
  `feedback` longtext NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `staffs`
--

CREATE TABLE `staffs` (
  `id` int(11) NOT NULL,
  `fullname` varchar(300) NOT NULL,
  `division_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staffs`
--

INSERT INTO `staffs` (`id`, `fullname`, `division_id`, `created_at`, `updated_at`) VALUES
(1, 'Lorenzo Salamante', 1, '2020-07-21 11:55:58', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int(11) NOT NULL,
  `fullname` varchar(300) NOT NULL,
  `agency` int(11) NOT NULL,
  `attached_agency` int(11) NOT NULL,
  `email_address` varchar(300) NOT NULL,
  `is_have_ecopy` int(1) NOT NULL DEFAULT 1,
  `photo` varchar(300) NOT NULL COMMENT 'filename only',
  `division_to_visit` int(11) NOT NULL,
  `purpose` int(11) NOT NULL,
  `person_to_visit` int(11) NOT NULL,
  `temperature` varchar(300) NOT NULL,
  `place_of_origin` varchar(300) NOT NULL,
  `mobile_number` varchar(300) NOT NULL,
  `health_condition` varchar(300) NOT NULL,
  `pin_code` varchar(300) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `fullname`, `agency`, `attached_agency`, `email_address`, `is_have_ecopy`, `photo`, `division_to_visit`, `purpose`, `person_to_visit`, `temperature`, `place_of_origin`, `mobile_number`, `health_condition`, `pin_code`, `created_at`, `updated_at`) VALUES
(1, 'Elline Ocampo', 2, 1, 'edocampo@myoptimind.com', 1, '1-1595320977_5400290-polaroid-polaroid-frame-png-polaroid-template-marco-polaroid-polaroid-picture-frame-png-820_685_preview.jpg', 3, 0, 1, '36.5', 'San Mateo, Rizal', '09497912581', 'Normal', 'zXc12365gfd', '2020-07-21 12:42:57', '2020-07-21 12:42:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agency`
--
ALTER TABLE `agency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cesbie_visitors`
--
ALTER TABLE `cesbie_visitors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `division`
--
ALTER TABLE `division`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staffs`
--
ALTER TABLE `staffs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `agency`
--
ALTER TABLE `agency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cesbie_visitors`
--
ALTER TABLE `cesbie_visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `division`
--
ALTER TABLE `division`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staffs`
--
ALTER TABLE `staffs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
