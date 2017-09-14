/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns1120

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-12-29 20:33:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_newyear_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_newyear_log`;
CREATE TABLE `pre_newyear_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL DEFAULT '0' COMMENT '日期 格式：yyyymmdd',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型：1：凌晨1点，2：下午1点',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '中奖用户id',
  `getTime` int(11) NOT NULL DEFAULT '0' COMMENT '中奖时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='元旦活动记录表';

-- ----------------------------
-- Records of pre_newyear_log
-- ----------------------------
