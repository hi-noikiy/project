/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns1120

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-12-16 16:27:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_christmas_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_christmas_log`;
CREATE TABLE `pre_christmas_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sequence` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '中奖序号',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '中奖用户id',
  `editTime` int(11) NOT NULL DEFAULT '0' COMMENT '中奖时间',
  PRIMARY KEY (`id`),
  KEY `sequence` (`sequence`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='圣诞礼物日志表';

-- ----------------------------
-- Records of pre_christmas_log
-- ----------------------------
