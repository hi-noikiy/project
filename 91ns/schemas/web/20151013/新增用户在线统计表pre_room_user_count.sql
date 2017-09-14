/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-10-13 15:05:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_room_user_count`
-- ----------------------------
DROP TABLE IF EXISTS `pre_room_user_count`;
CREATE TABLE `pre_room_user_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` tinyint(1) NOT NULL DEFAULT '1' COMMENT '平台 1:pc 2:ios 3:android',
  `count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `platform` (`platform`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户进入房间记录表';

-- ----------------------------
-- Records of pre_room_user_count
-- ----------------------------
