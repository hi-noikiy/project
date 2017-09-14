/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-03-19 12:04:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_room_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_room_log`;
CREATE TABLE `pre_room_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roomId` int(11) DEFAULT NULL,
  `publicTime` int(11) DEFAULT NULL COMMENT '该场次、开播时间',
  `count` int(11) DEFAULT NULL COMMENT '当前开播场次、被有效访问次数',
  `endTime` int(11) DEFAULT NULL COMMENT '该场次、关播时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_room_log
-- ----------------------------
