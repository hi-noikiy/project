/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-17 17:52:01
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_cash_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_cash_log`;
CREATE TABLE `pre_cash_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '聊币数量',
  `source` smallint(4) NOT NULL DEFAULT '0' COMMENT '钱币来源',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `orderId` varchar(30) CHARACTER SET latin1 NOT NULL DEFAULT '' COMMENT '订单号',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_cash_log
-- ----------------------------
