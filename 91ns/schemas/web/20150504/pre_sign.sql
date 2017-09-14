/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-05-04 17:49:06
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_sign`
-- ----------------------------
DROP TABLE IF EXISTS `pre_sign`;
CREATE TABLE `pre_sign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `month` int(11) DEFAULT '0' COMMENT '月份',
  `type` smallint(4) DEFAULT '0' COMMENT '类型',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 1可领取 2已领取',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_sign
-- ----------------------------
