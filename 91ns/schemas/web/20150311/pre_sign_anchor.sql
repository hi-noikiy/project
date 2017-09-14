/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-03-11 15:39:01
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_sign_anchor`
-- ----------------------------
DROP TABLE IF EXISTS `pre_sign_anchor`;
CREATE TABLE `pre_sign_anchor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `familyId` int(11) DEFAULT '0',
  `realName` varchar(100) DEFAULT '',
  `gender` tinyint(1) DEFAULT '0',
  `photo` varchar(255) DEFAULT '',
  `bank` varchar(100) DEFAULT '',
  `birth` varchar(100) DEFAULT '',
  `cardNumber` varchar(30) DEFAULT '',
  `accountName` varchar(100) DEFAULT '',
  `idCard` varchar(30) DEFAULT '',
  `telephone` varchar(30) DEFAULT '',
  `qq` varchar(20) DEFAULT '',
  `birthday` int(11) DEFAULT '0',
  `address` text,
  `status` tinyint(1) DEFAULT '0',
  `createTime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `Index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='签约主播表';

-- ----------------------------

