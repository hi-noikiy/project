/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-05-04 17:36:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_sign_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_sign_log`;
CREATE TABLE `pre_sign_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '签到时间',
  `conTimes` int(11) NOT NULL DEFAULT '1' COMMENT '本月持续签到次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_sign_log
-- ----------------------------
