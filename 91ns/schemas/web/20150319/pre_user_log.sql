/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-03-19 12:04:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_user_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_user_log`;
CREATE TABLE `pre_user_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `roomId` int(11) DEFAULT NULL,
  `updateTime` int(11) DEFAULT NULL COMMENT '用户访问该房间的最后时间',
  `count` int(11) DEFAULT NULL COMMENT '用户访问该房间次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_user_log
-- ----------------------------
