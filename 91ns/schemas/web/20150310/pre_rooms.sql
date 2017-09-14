/*
Navicat MySQL Data Transfer

Source Server         : MySQL
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-10 13:47:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_rooms
-- ----------------------------
DROP TABLE IF EXISTS `pre_rooms`;
CREATE TABLE `pre_rooms` (
  `roomId` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `isRecommend` tinyint(1) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `announcement` varchar(100) DEFAULT NULL,
  `publicTime` int(11) DEFAULT NULL,
  `syncTime` int(11) DEFAULT NULL,
  `liveStatus` tinyint(1) DEFAULT NULL,
  `poster` varchar(255) DEFAULT NULL COMMENT '海报路径',
  `onlineNum` int(11) DEFAULT '0' COMMENT '房间在线人数',
  PRIMARY KEY (`roomId`),
  UNIQUE KEY `Index_uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='pre_rooms';
