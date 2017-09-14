/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-09-29 17:58:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_mobile_token`
-- ----------------------------
DROP TABLE IF EXISTS `pre_mobile_token`;
CREATE TABLE `pre_mobile_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `token` varchar(32) DEFAULT '',
  `device` varchar(50) DEFAULT '' COMMENT '设备信息',
  `expireTime` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='app自动登录token信息存储';

-- ----------------------------
-- Records of pre_mobile_token
-- ----------------------------
