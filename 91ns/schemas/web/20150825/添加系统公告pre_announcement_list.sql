/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.71_3306
Source Server Version : 50539
Source Host           : 192.168.1.71:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-08-25 13:55:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_announcement_list
-- ----------------------------
DROP TABLE IF EXISTS `pre_announcement_list`;
CREATE TABLE `pre_announcement_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text,
  `url` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `addtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `addtime` (`addtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
