/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.23
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-04-08 17:09:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_activities_share
-- ----------------------------
DROP TABLE IF EXISTS `pre_activities_share`;
CREATE TABLE `pre_activities_share` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT '0',
  `anchorId` int(11) DEFAULT NULL COMMENT '主播ID',
  `type` tinyint(4) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  `fromuid` int(11) DEFAULT NULL COMMENT '由谁分享的',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_activities_share
-- ----------------------------
