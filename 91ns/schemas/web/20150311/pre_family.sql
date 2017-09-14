/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-03-12 15:27:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_family`
-- ----------------------------
DROP TABLE IF EXISTS `pre_family`;
CREATE TABLE `pre_family` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `shortName` varchar(100) DEFAULT NULL,
  `announcement` varchar(255) DEFAULT NULL,
  `description` text,
  `logo` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  `creatorUid` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='pre_family';

