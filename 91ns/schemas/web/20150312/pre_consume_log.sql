/*
Navicat MySQL Data Transfer

Source Server         : mySQL
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-12 21:24:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_consume_log
-- ----------------------------
DROP TABLE IF EXISTS `pre_consume_log`;
CREATE TABLE `pre_consume_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `anchorId` int(11) DEFAULT NULL,
  `familyId` int(11) DEFAULT NULL,
  `amount` bigint(11) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
