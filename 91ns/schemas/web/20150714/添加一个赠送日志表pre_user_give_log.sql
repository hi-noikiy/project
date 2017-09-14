/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-07-13 11:17:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_user_give_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_user_give_log`;
CREATE TABLE `pre_user_give_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '赠送类型',
  `itemId` int(11) NOT NULL DEFAULT '0' COMMENT '物品id',
  `itemTime` int(11) NOT NULL DEFAULT '0' COMMENT '物品有效时间',
  `receiveUid` int(11) NOT NULL DEFAULT '0' COMMENT '接收者的uid',
  `createTime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='赠送记录表';

-- ----------------------------
-- Records of pre_user_give_log
-- ----------------------------
