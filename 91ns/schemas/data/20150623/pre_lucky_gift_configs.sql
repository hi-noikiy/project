/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-06-24 10:04:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_lucky_gift_configs`
-- ----------------------------
DROP TABLE IF EXISTS `pre_lucky_gift_configs`;
CREATE TABLE `pre_lucky_gift_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `giftId` int(11) DEFAULT '0' COMMENT '礼物id',
  `multiple` int(11) DEFAULT '0' COMMENT '中奖倍数',
  `limit` int(11) DEFAULT '0' COMMENT '大盘中奖额度',
  `count` int(11) DEFAULT '0' COMMENT '幸运礼物赠送数累计',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='幸运礼物配置表';

-- ----------------------------
-- Records of pre_lucky_gift_configs
-- ----------------------------
INSERT INTO `pre_lucky_gift_configs` VALUES ('1', '40', '1000', '300000', '0');
INSERT INTO `pre_lucky_gift_configs` VALUES ('2', '41', '1000', '60000', '0');
INSERT INTO `pre_lucky_gift_configs` VALUES ('3', '42', '1000', '30000', '0');
INSERT INTO `pre_lucky_gift_configs` VALUES ('4', '43', '1000', '6000', '0');
INSERT INTO `pre_lucky_gift_configs` VALUES ('5', '44', '1000', '3000', '0');
