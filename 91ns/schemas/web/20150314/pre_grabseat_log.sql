/*
Navicat MySQL Data Transfer

Source Server         : mySQL
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-15 11:00:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_grabseat_log
-- ----------------------------
DROP TABLE IF EXISTS `pre_grabseat_log`;
CREATE TABLE `pre_grabseat_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anchorUid` int(11) DEFAULT NULL,
  `seatUid` int(11) DEFAULT NULL,
  `seatPos` int(11) DEFAULT NULL,
  `seatCount` int(11) DEFAULT NULL,
  `updateTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Index_anchorUid` (`anchorUid`),
  KEY `Index_seatUid` (`seatUid`),
  KEY `Index_seatPos` (`seatPos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='pre_grabseat_log';
