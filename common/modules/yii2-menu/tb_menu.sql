-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2016 at 02:26 AM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ikhlas_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL COMMENT 'รหัสเมนู',
  `menu_category_id` int(11) NOT NULL COMMENT 'รหัสหมวดเมนู',
  `parent_id` int(11) DEFAULT NULL COMMENT 'ภายใต้เมนู',
  `title` varchar(200) NOT NULL COMMENT 'ชื่อเมนู',
  `router` varchar(250) NOT NULL COMMENT 'ลิงค์',
  `parameter` varchar(250) DEFAULT NULL COMMENT 'พารามิเตอร์',
  `icon` varchar(30) DEFAULT NULL COMMENT 'ไอคอน',
  `status` enum('2','1','0') DEFAULT '0' COMMENT 'สถานะ',
  `item_name` varchar(64) DEFAULT NULL COMMENT 'บทบาท',
  `target` varchar(30) DEFAULT NULL COMMENT 'เป้าหมาย',
  `protocol` varchar(20) DEFAULT NULL COMMENT 'โปรโตคอล',
  `home` enum('1','0') DEFAULT '0' COMMENT 'หน้าแรก',
  `sort` int(3) DEFAULT NULL COMMENT 'เรียง',
  `language` varchar(7) DEFAULT '*' COMMENT 'ภาษา',
  `params` mediumtext COMMENT 'ลักษณะพิเศษ',
  `assoc` varchar(12) DEFAULT NULL COMMENT 'ชุดเมนู',
  `created_at` int(11) DEFAULT NULL COMMENT 'สร้างเมื่อ',
  `created_by` int(11) DEFAULT NULL COMMENT 'สร้างโดย',
  `name` varchar(128) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(256) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ระบบเมนู';

-- --------------------------------------------------------

--
-- Table structure for table `menu_auth`
--

CREATE TABLE `menu_auth` (
  `menu_id` int(11) NOT NULL,
  `item_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `menu_category`
--

CREATE TABLE `menu_category` (
  `id` int(11) NOT NULL COMMENT 'รหัสหมวดเมนู',
  `title` varchar(50) NOT NULL COMMENT 'ชื่อหมวดเมนู',
  `discription` varchar(255) DEFAULT NULL COMMENT 'คำอธิบาย',
  `status` enum('1','0') DEFAULT NULL COMMENT 'สถานะ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='หมวดเมนู';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_cate_id` (`menu_category_id`) USING BTREE,
  ADD KEY `menu_parent` (`parent_id`);

--
-- Indexes for table `menu_auth`
--
ALTER TABLE `menu_auth`
  ADD PRIMARY KEY (`menu_id`,`item_name`);

--
-- Indexes for table `menu_category`
--
ALTER TABLE `menu_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_cate_id` (`id`,`title`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสเมนู', AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `menu_category`
--
ALTER TABLE `menu_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสหมวดเมนู', AUTO_INCREMENT=6;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`menu_category_id`) REFERENCES `menu_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_auth`
--
ALTER TABLE `menu_auth`
  ADD CONSTRAINT `menu_auth_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
