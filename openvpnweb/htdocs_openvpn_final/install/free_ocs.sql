-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2019 at 11:33 AM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `free_ocs`
--

-- --------------------------------------------------------

--
-- Table structure for table `ref_no`
--

CREATE TABLE `ref_no` (
  `id` int(55) NOT NULL,
  `ref_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `point` decimal(10,2) NOT NULL,
  `phone_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `server`
--

CREATE TABLE `server` (
  `s_id` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `s_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `s_ip` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `s_pass` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `s_price` int(4) NOT NULL,
  `s_expire` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `s_limit` int(5) NOT NULL,
  `s_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ssh_user`
--

CREATE TABLE `ssh_user` (
  `ssh_u_id` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `ssh_user` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ssh_pass` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `s_id` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_at` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expire_at` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expire_day` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `user` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL,
  `email` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `admin_` int(1) DEFAULT NULL,
  `active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallet_user`
--

CREATE TABLE `wallet_user` (
  `id` int(1) NOT NULL,
  `mobile` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `pin` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(6) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ref_no`
--
ALTER TABLE `ref_no`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `server`
--
ALTER TABLE `server`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `ssh_user`
--
ALTER TABLE `ssh_user`
  ADD PRIMARY KEY (`ssh_u_id`),
  ADD KEY `ssh_user_fk_2` (`s_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`,`user`);

--
-- Indexes for table `wallet_user`
--
ALTER TABLE `wallet_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ref_no`
--
ALTER TABLE `ref_no`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallet_user`
--
ALTER TABLE `wallet_user`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
