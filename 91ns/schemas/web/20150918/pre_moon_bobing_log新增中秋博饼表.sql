/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-09-17 15:19:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_moon_bobing_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_moon_bobing_log`;
CREATE TABLE `pre_moon_bobing_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `points` int(11) NOT NULL DEFAULT '0' COMMENT '点数',
  `moonValue` int(11) NOT NULL DEFAULT '0' COMMENT '消耗月光值',
  `resultCode` int(11) NOT NULL DEFAULT '0' COMMENT '中奖结果',
  `isChampion` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否状元',
  `createTime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='中秋活动博饼掷骰子日志表';

-- ----------------------------
-- Records of pre_moon_bobing_log
-- ----------------------------
