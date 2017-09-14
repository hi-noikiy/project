/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns1120

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2016-01-19 18:40:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_group`
-- ----------------------------
DROP TABLE IF EXISTS `pre_group`;
CREATE TABLE `pre_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL DEFAULT '' COMMENT '用户小团体名称',
  `shortName` varchar(10) NOT NULL DEFAULT '' COMMENT '徽章名称',
  `leaderUid` int(11) NOT NULL DEFAULT '0' COMMENT '团长uid',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户团体表';

-- ----------------------------
-- Records of pre_group
-- ----------------------------
