/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-20 11:03:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_activities_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_activities_log`;
CREATE TABLE `pre_activities_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `activityId` smallint(4) DEFAULT '0' COMMENT '活动id 写在配置文件中',
  `expireTime` int(11) DEFAULT '0' COMMENT '领取 过期时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '领取状态 1:可领取 2:已领取',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_activities_log
-- ----------------------------
