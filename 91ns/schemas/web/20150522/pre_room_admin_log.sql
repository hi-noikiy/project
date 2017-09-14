/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-05-22 15:08:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_room_admin_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_room_admin_log`;
CREATE TABLE `pre_room_admin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operateUid` int(11) NOT NULL DEFAULT '0' COMMENT '操作者的id',
  `roomId` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `type` smallint(4) NOT NULL DEFAULT '0' COMMENT '操作类型：1禁言、2踢人、3设管理、',
  `beOperateUid` int(11) NOT NULL DEFAULT '0' COMMENT '被操作的用户id',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='直播间超级管理员操作日志';

