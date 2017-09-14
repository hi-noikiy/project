/*
Navicat MySQL Data Transfer

Source Server         : MySQL
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-12 16:23:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_consume_to_anchor_log
-- ----------------------------
DROP TABLE IF EXISTS `pre_consume_to_anchor_log`;
CREATE TABLE `pre_consume_to_anchor_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `familyId` int(11) DEFAULT NULL,
  `anchorUid` int(11) DEFAULT NULL,
  `consumeUid` int(11) DEFAULT NULL,
  `amount` bigint(11) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='pre_consume_to_anchor_log';
