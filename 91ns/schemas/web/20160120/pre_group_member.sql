/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns1120

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2016-01-19 18:40:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_group_member`
-- ----------------------------
DROP TABLE IF EXISTS `pre_group_member`;
CREATE TABLE `pre_group_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupId` int(11) NOT NULL DEFAULT '0' COMMENT '对应pre_group表的id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '加入时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='用户军团成员表';

-- ----------------------------
-- Records of pre_group_member
-- ----------------------------
