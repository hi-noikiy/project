/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-04-14 11:04:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_task_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_task_log`;
CREATE TABLE `pre_task_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `taskId` int(11) DEFAULT NULL COMMENT '任务id',
  `status` tinyint(1) DEFAULT '0' COMMENT '任务完成状态',
  `finishRate` int(11) DEFAULT '0' COMMENT '完成进度',
  `finishTime` int(11) DEFAULT '0' COMMENT '完成时间',
  `receiveTime` int(11) DEFAULT '0' COMMENT '领取时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_task_log
-- ----------------------------
