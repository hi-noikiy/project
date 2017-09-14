/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-10-13 15:05:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_user_active_count`
-- ----------------------------
DROP TABLE IF EXISTS `pre_user_active_count`;
CREATE TABLE `pre_user_active_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` int(11) NOT NULL COMMENT '平台',
  `roomId` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `createTime` int(11) NOT NULL,
  `endTime` int(11) DEFAULT '0',
  `timeCount` int(11) NOT NULL DEFAULT '0' COMMENT '已在直播间停留的时间（单位：秒）',
  `tempCount` int(11) DEFAULT '0' COMMENT '用于记录跨过第二天零点的数据的在线时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `platform,roomId,uid,date` (`platform`,`roomId`,`uid`,`date`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户活跃记录表';

-- ----------------------------
-- Records of pre_user_active_count
-- ----------------------------
