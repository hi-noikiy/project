/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns1120

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-12-29 20:33:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_gift_collect_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_gift_collect_log`;
CREATE TABLE `pre_gift_collect_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '主播id',
  `giftId` int(11) NOT NULL DEFAULT '0' COMMENT '礼物id',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '收到数量',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid,giftId` (`uid`,`giftId`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动期间主播收到礼物数量记录';

-- ----------------------------
-- Records of pre_gift_collect_log
-- ----------------------------
