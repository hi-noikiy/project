-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-06-09 14:49:50
-- 服务器版本： 5.6.21
-- PHP Version: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `91ns`
--

-- --------------------------------------------------------

--
-- 表的结构 `pre_device_info`
--
drop table pre_device_info;
CREATE TABLE IF NOT EXISTS `pre_device_info` (
`id` int(11) NOT NULL,
  `platform` tinyint(1) NOT NULL,
  `deviceid` varchar(255) DEFAULT NULL,
  `devicetoken` varchar(255) DEFAULT NULL,
  `clientID` varchar(255) NOT NULL,
  `uid` int(11) DEFAULT '0',
  `lasttime` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `pre_device_info`
--


--
-- Indexes for dumped tables
--

--
-- Indexes for table `pre_device_info`
--
ALTER TABLE `pre_device_info`
 ADD PRIMARY KEY (`id`), ADD KEY `platform` (`platform`,`uid`,`lasttime`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pre_device_info`
--
ALTER TABLE `pre_device_info`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
