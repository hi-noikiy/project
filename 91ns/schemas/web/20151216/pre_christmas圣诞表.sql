/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns1120

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-12-16 16:27:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_christmas`
-- ----------------------------
DROP TABLE IF EXISTS `pre_christmas`;
CREATE TABLE `pre_christmas` (
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '主播id',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '收到圣诞礼物总数量',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='主播收到圣诞礼物数量记录表';

-- ----------------------------
-- Records of pre_christmas
-- ----------------------------
