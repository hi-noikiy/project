/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-07-29 14:25:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_activity_income_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_activity_income_log`;
CREATE TABLE `pre_activity_income_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `money` decimal(32,3) DEFAULT NULL COMMENT '聊币',
  `remark` varchar(64) DEFAULT NULL COMMENT '活动奖励来源',
  `type` tinyint(3) DEFAULT '1' COMMENT '活动类型：1-周星，2-推荐充值',
  `createTime` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动收入日志表';

-- ----------------------------
-- Records of pre_activity_income_log
-- ----------------------------
