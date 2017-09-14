/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-11 14:41:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_rank_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_rank_log`;
CREATE TABLE `pre_rank_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataType` varchar(20) DEFAULT NULL,
  `rankType` varchar(20) DEFAULT 'day',
  `content` text,
  `lastTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`rankType`),
  KEY `lastTime` (`lastTime`),
  KEY `method` (`dataType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_rank_log
-- ----------------------------
