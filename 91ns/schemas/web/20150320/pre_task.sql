/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-20 17:54:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_task`
-- ----------------------------
DROP TABLE IF EXISTS `pre_task`;
CREATE TABLE `pre_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taskId` int(11) DEFAULT NULL COMMENT '任务id',
  `taskName` varchar(30) DEFAULT '' COMMENT '任务名称',
  `taskDes` varchar(255) DEFAULT '' COMMENT '任务描述',
  `taskType` smallint(4) DEFAULT '1' COMMENT '任务类型',
  `taskReward` int(11) DEFAULT '0' COMMENT '任务报酬 ',
  `taskSort` smallint(4) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '任务状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_task
-- ----------------------------
