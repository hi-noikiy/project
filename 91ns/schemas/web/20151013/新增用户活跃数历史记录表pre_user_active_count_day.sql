/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-10-13 15:05:46
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_user_active_count_day`
-- ----------------------------
DROP TABLE IF EXISTS `pre_user_active_count_day`;
CREATE TABLE `pre_user_active_count_day` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` tinyint(1) NOT NULL DEFAULT '1' COMMENT '平台 1：pc 2:ios 3:android',
  `date` int(11) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '人数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `platform,date` (`platform`,`date`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户活跃记录表';

-- ----------------------------
-- Records of pre_user_active_count_day
-- ----------------------------
