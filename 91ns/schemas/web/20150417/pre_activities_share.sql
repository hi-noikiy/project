/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-04-17 15:07:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_activities_share`
-- ----------------------------
DROP TABLE IF EXISTS `pre_activities_share`;
CREATE TABLE `pre_activities_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `anchorId` int(11) DEFAULT NULL COMMENT '主播id',
  `type` tinyint(1) DEFAULT NULL COMMENT '分享类型',
  `createTime` int(11) DEFAULT NULL COMMENT '分享时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_activities_share
-- ----------------------------
