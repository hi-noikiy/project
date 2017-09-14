/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.71
Source Server Version : 50539
Source Host           : 192.168.1.71:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-08-25 11:56:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_exchange_gift_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_exchange_gift_log`;
CREATE TABLE `pre_exchange_gift_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL COMMENT '兑换码',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `expireTime` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间',
  `giftPackageId` int(11) NOT NULL DEFAULT '0' COMMENT '礼包id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '领取人',
  `getTime` int(11) NOT NULL DEFAULT '0' COMMENT '领取时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='兑换礼包记录表';

-- ----------------------------
-- Records of pre_exchange_gift_log
-- ----------------------------
