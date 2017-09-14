/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-04-23 22:20:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_user_information`
-- ----------------------------
DROP TABLE IF EXISTS `pre_user_information`;
CREATE TABLE `pre_user_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `content` varchar(500) DEFAULT '' COMMENT '通知内容',
  `type` smallint(4) DEFAULT '0' COMMENT '通知类型：消息、申请、审批',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态：0未读 1已读',
  `createTime` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户通知表';

-- ----------------------------
-- Records of pre_user_information
-- ----------------------------
