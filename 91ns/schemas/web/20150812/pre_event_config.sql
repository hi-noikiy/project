/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.70_3306
Source Server Version : 50539
Source Host           : 192.168.1.70:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-08-12 22:47:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_event_config
-- ----------------------------
DROP TABLE IF EXISTS `pre_event_config`;
CREATE TABLE `pre_event_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etype` tinyint(1) DEFAULT '0',
  `bannerurl` varchar(255) DEFAULT NULL,
  `extracontent` varchar(255) DEFAULT NULL,
  `description` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
