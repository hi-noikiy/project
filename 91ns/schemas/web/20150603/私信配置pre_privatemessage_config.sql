-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-06-03 13:59:46
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
-- 表的结构 `pre_privatemessage_config`
--

CREATE TABLE IF NOT EXISTS `pre_privatemessage_config` (
`id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `toUid` int(11) NOT NULL,
  `top` tinyint(1) NOT NULL DEFAULT '0',
  `shield` tinyint(1) NOT NULL DEFAULT '0',
  `lastTime` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='私信配置表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pre_privatemessage_config`
--
ALTER TABLE `pre_privatemessage_config`
 ADD PRIMARY KEY (`id`), ADD KEY `uid` (`uid`,`lastTime`), ADD KEY `top` (`top`), ADD KEY `toUid` (`toUid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pre_privatemessage_config`
--
ALTER TABLE `pre_privatemessage_config`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
