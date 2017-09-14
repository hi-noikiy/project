/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.71
Source Server Version : 50539
Source Host           : 192.168.1.71:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-09-18 13:44:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_moon_energy`
-- ----------------------------
DROP TABLE IF EXISTS `pre_moon_energy`;
CREATE TABLE `pre_moon_energy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:月光值 2:能量值',
  `totalNum` int(11) NOT NULL DEFAULT '0' COMMENT '月光值/能量值',
  `leftNum` int(11) NOT NULL,
  `rank` tinyint(1) DEFAULT '0',
  `reward` text,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='中秋活动用户月光值/能量值数据表';

-- ----------------------------
-- Records of pre_moon_energy
-- ----------------------------

