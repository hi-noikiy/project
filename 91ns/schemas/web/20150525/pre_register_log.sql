/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-05-25 20:31:59
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_register_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_register_log`;
CREATE TABLE `pre_register_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentType` smallint(4) DEFAULT '0',
  `subType` smallint(4) DEFAULT NULL,
  `uuid` varchar(50) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `createTime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='注册日志表';

-- ----------------------------
-- Records of pre_register_log
-- ----------------------------
