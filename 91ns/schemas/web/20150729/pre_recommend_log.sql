/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-07-29 14:25:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_recommend_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_recommend_log`;
CREATE TABLE `pre_recommend_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `beRecUid` int(11) NOT NULL DEFAULT '0' COMMENT '被推荐用户id',
  `telephone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `recUid` int(11) NOT NULL DEFAULT '0' COMMENT '推荐人uid',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户推荐';

-- ----------------------------
-- Records of pre_recommend_log
-- ----------------------------
