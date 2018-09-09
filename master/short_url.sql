-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2018 at 04:19 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.0.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `short_url`
--

CREATE TABLE `short_url` (
  `id` int(10) UNSIGNED NOT NULL,
  `long_url` varchar(255) NOT NULL,
  `short_code` varbinary(6) NOT NULL,
  `date_created` datetime NOT NULL,
  `counter` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `short_url`
--

INSERT INTO `short_url` (`id`, `long_url`, `short_code`, `date_created`, `counter`) VALUES
(1, 'https://www.flipkart.com/mobiles/~asus-zenfone-max-pro-m1/pr?sid=tyy,4io&fm=neo/merchandising&iid=M_a6d3f58d-1ee1-4a44-8822-9b04a7f9e6ab_14.6XL8ZUO9K72M&ppt=Homepage&ppn=Homepage&otracker=hp_omu_Dual+Camera+Phones_1_Upto+16MP%2B5MP+Camera_6XL8ZUO9K72M_1&c', 0x32, '0000-00-00 00:00:00', 1),
(2, 'https://www.flipkart.com/vivo-x21-black-128-gb/p/itmf5g4jncexzfgt?pid=MOBF5G4JFCYRDQZB&lid=LSTMOBF5G4JFCYRDQZBEKRZES&fm=neo/merchandising&iid=M_a6d3f58d-1ee1-4a44-8822-9b04a7f9e6ab_14.NQ4LWRWM5MIR&ppt=Homepage&ppn=Homepage&otracker=hp_omu_Dual+Camera+Phon', 0x33, '0000-00-00 00:00:00', 1),
(5, 'https://www.flipkart.com/redmi-note-5-pro-black-64-gb/p/itmf2fc3xgmxnhpx?pid=MOBF28FTQPHUPX83&lid=LSTMOBF28FTQPHUPX83BUJJ2C&marketplace=FLIPKART&fm=productRecommendation/similar&iid=s:MOBF6GZTM8BDQZGG:8a1875e4-144d-3d01-0295-226bc14db6a6.MOBF28FTQPHUPX83.', 0x36, '0000-00-00 00:00:00', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `short_url`
--
ALTER TABLE `short_url`
  ADD PRIMARY KEY (`id`),
  ADD KEY `short_code` (`short_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `short_url`
--
ALTER TABLE `short_url`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
