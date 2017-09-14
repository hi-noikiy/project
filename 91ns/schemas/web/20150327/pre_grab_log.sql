/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91nsdb

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-27 21:06:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_grab_log
-- ----------------------------
DROP TABLE IF EXISTS `pre_grab_log`;
CREATE TABLE `pre_grab_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seatPos` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `consumeLogId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
