/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.71
Source Server Version : 50539
Source Host           : 192.168.1.71:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-11-24 20:21:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_bet_points_result_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_bet_points_result_log`;
CREATE TABLE `pre_bet_points_result_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT 'uid',
  `times` int(11) DEFAULT '0' COMMENT '开奖期数',
  `type` tinyint(3) DEFAULT '0' COMMENT '夺宝类型',
  `createTime` int(11) DEFAULT '0' COMMENT '时间',
  `remark` varchar(32) DEFAULT NULL COMMENT '中奖礼物',
  `status` tinyint(3) DEFAULT '0' COMMENT '开奖状态',
  `openTime` int(11) unsigned DEFAULT '0' COMMENT '开奖时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='夺宝开奖记录表';

-- ----------------------------
-- Records of pre_bet_points_result_log
-- ----------------------------
INSERT INTO `pre_bet_points_result_log` VALUES ('1', '0', '1', '1', '1448353042', '', '0', '0');
INSERT INTO `pre_bet_points_result_log` VALUES ('2', '0', '1', '2', '1448353042', '', '0', '0');
INSERT INTO `pre_bet_points_result_log` VALUES ('3', '0', '1', '3', '1448353042', '', '0', '0');
