/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-10-21 21:58:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_anchor_poster`
-- ----------------------------
DROP TABLE IF EXISTS `pre_anchor_poster`;
CREATE TABLE `pre_anchor_poster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `imageUrl` varchar(100) NOT NULL DEFAULT '' COMMENT '图片路径',
  `isShow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1显示，0删除',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0：审核中 1：审核通过 2：审核不通过',
  `auditor` varchar(20) DEFAULT '' COMMENT '审核人',
  `auditTime` int(11) DEFAULT '0' COMMENT '审核时间',
  `isUsed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '使用状态：1：使用中',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='主播封面表';

-- ----------------------------
-- Records of pre_anchor_poster
-- ----------------------------
