/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-11-18 18:14:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_month_rank_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_month_rank_log`;
CREATE TABLE `pre_month_rank_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL COMMENT '月榜大作战主播排行榜',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型：1：主播收益排行 2：用户消费排行',
  `month` int(11) NOT NULL DEFAULT '0' COMMENT '月份',
  `isGet` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发放奖励 1：发放',
  `getTime` int(11) NOT NULL DEFAULT '0' COMMENT '发放奖励时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `month,type` (`month`,`type`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='月榜大作战记录表';

-- ----------------------------
-- Records of pre_month_rank_log
-- ----------------------------
