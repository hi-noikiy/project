/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-03-17 13:52:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_rank_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_rank_log`;
CREATE TABLE `pre_rank_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text,
  `index` tinyint(1) DEFAULT NULL,
  `lastTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_rank_log
-- ----------------------------
