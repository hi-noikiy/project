/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.71_3306
Source Server Version : 50539
Source Host           : 192.168.1.71:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-08-12 22:46:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inv_banner_config
-- ----------------------------
DROP TABLE IF EXISTS `inv_banner_config`;
CREATE TABLE `inv_banner_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `btype` tinyint(1) DEFAULT '0',
  `bannerurl` varchar(255) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `backgroundcolor` varchar(20) DEFAULT NULL,
  `extracontent` varchar(255) DEFAULT NULL,
  `description` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `type` (`btype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
