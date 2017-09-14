/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.71_3306
Source Server Version : 50539
Source Host           : 192.168.1.71:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-07-24 15:24:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_guard_right
-- ----------------------------
DROP TABLE IF EXISTS `pre_guard_right`;
CREATE TABLE `pre_guard_right` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `des` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '0',
  `img` varchar(255) DEFAULT NULL,
  `lasttime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lasttime` (`lasttime`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
