/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-03-15 21:10:00
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_family_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_family_log`;
CREATE TABLE `pre_family_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `joinTime` int(11) DEFAULT NULL,
  `outOfTime` int(11) DEFAULT NULL,
  `familyId` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL COMMENT '0:家族进出记录，1:当前所处家族。',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_family_log
-- ----------------------------
