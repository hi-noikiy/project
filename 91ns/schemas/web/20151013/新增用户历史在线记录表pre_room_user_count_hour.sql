/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-10-13 15:05:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_room_user_count_hour`
-- ----------------------------
DROP TABLE IF EXISTS `pre_room_user_count_hour`;
CREATE TABLE `pre_room_user_count_hour` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` tinyint(1) NOT NULL DEFAULT '1' COMMENT '平台 1：pc 2:ios 3:android',
  `createTime` int(11) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '人数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='直播间用户历史在线数记录';

-- ----------------------------
-- Records of pre_room_user_count_hour
-- ----------------------------
