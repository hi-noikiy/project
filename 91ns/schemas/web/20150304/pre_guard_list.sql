/*
Navicat MySQL Data Transfer

Source Server         : mySQL
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-04 21:13:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_guard_list
-- ----------------------------
DROP TABLE IF EXISTS `pre_guard_list`;
CREATE TABLE `pre_guard_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guardUid` int(11) DEFAULT NULL,
  `beGuardedUid` int(11) DEFAULT NULL,
  `guardLevel` tinyint(3) DEFAULT NULL,
  `addTime` int(11) DEFAULT NULL,
  `expireTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Index_guardUid` (`guardUid`),
  KEY `Index_beGuardedUid` (`beGuardedUid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
