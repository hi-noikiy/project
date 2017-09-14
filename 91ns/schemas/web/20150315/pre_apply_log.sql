/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-03-15 22:34:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_apply_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_apply_log`;
CREATE TABLE `pre_apply_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '申请者ID',
  `targetId` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `type` tinyint(1) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_apply_log
-- ----------------------------
