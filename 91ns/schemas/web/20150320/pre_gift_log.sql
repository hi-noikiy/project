/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-03-20 16:44:30
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_gift_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_gift_log`;
CREATE TABLE `pre_gift_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `giftId` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `consumeLogId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Index_giftId` (`giftId`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='礼物日志表';

