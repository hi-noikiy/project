/*
Navicat MySQL Data Transfer

Source Server         : mySQL
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-13 13:30:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_user_item
-- ----------------------------
DROP TABLE IF EXISTS `pre_user_item`;
CREATE TABLE `pre_user_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `itemType` varchar(10) NOT NULL,
  `itemId` int(11) NOT NULL,
  `itemCount` int(11) DEFAULT NULL,
  `itemExpireTime` int(11) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index_item` (`uid`,`itemId`,`itemType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='pre_user_item';
