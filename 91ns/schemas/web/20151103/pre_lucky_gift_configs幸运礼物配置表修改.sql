/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-11-03 16:32:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_lucky_gift_odds`
-- ----------------------------
DROP TABLE IF EXISTS `pre_lucky_gift_odds`;
CREATE TABLE `pre_lucky_gift_odds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `giftId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '礼物id',
  `sequence` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '中奖序号',
  `multiple` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '中奖倍数',
  PRIMARY KEY (`id`),
  KEY `giftId` (`giftId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='幸运礼物概率表';

-- ----------------------------
-- Records of pre_lucky_gift_odds
-- ----------------------------
