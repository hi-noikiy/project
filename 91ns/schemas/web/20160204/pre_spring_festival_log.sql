/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns1120

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2016-02-04 21:29:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_spring_festival_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_spring_festival_log`;
CREATE TABLE `pre_spring_festival_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `round` int(11) NOT NULL DEFAULT '1' COMMENT '第几轮',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '赠送数量',
  `getCash` int(11) NOT NULL DEFAULT '0' COMMENT '获得的聊币',
  `getCar` int(11) NOT NULL DEFAULT '0' COMMENT '获得座驾',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='春节活动记录表';

-- ----------------------------
-- Records of pre_spring_festival_log
-- ----------------------------
