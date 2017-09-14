/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-08-21 14:49:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `inv_notice_log`
-- ----------------------------
DROP TABLE IF EXISTS `inv_notice_log`;
CREATE TABLE `inv_notice_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `content` varchar(500) NOT NULL COMMENT '通知的内容',
  `createTime` int(11) NOT NULL DEFAULT '0',
  `operator` varchar(30) NOT NULL COMMENT '操作者',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='gm后台给用户发送通知日志';

-- ----------------------------
-- Records of inv_notice_log
-- ----------------------------
