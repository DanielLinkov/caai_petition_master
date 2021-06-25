-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 02, 2019 at 08:18 PM
-- Server version: 5.5.64-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `petition_master`
--

-- --------------------------------------------------------

--
-- Table structure for table `petition`
--

CREATE TABLE IF NOT EXISTS `petition` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `hostname` varchar(64) COLLATE latin1_bin NOT NULL,
  `code` varchar(16) COLLATE latin1_bin NOT NULL,
  `config` varchar(1024) COLLATE latin1_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE IF NOT EXISTS `section` (
  `id` int(10) unsigned NOT NULL,
  `petition_id` int(10) unsigned NOT NULL,
  `mask_pages` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `parent_id` int(10) unsigned DEFAULT NULL,
  `order_ind` int(10) unsigned NOT NULL,
  `title` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `type` varchar(16) COLLATE latin1_bin NOT NULL,
  `tag_id` varchar(16) COLLATE latin1_bin DEFAULT NULL,
  `tag_additional_classes` varchar(64) COLLATE latin1_bin DEFAULT NULL,
  `background_image` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `background_image_mobile` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL,
  `role` enum('superadmin','admin','publisher') COLLATE latin1_bin NOT NULL DEFAULT 'publisher',
  `permission_flags` int(10) unsigned NOT NULL DEFAULT '0',
  `flag_enabled` bit(1) NOT NULL DEFAULT b'1',
  `username` varchar(16) COLLATE latin1_bin NOT NULL,
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(128) COLLATE latin1_bin NOT NULL,
  `preferences` varchar(1024) COLLATE latin1_bin NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `petition`
--
ALTER TABLE `petition`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`),
  ADD KEY `petition_id` (`petition_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `petition`
--
ALTER TABLE `petition`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
