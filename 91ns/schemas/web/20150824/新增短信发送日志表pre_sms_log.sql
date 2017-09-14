/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.71
Source Server Version : 50539
Source Host           : 192.168.1.71:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-08-25 15:40:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_sms_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_sms_log`;
CREATE TABLE `pre_sms_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `telephone` varchar(20) NOT NULL COMMENT '手机号',
  `content` varchar(100) NOT NULL COMMENT '短信内容',
  `type` smallint(4) NOT NULL DEFAULT '0' COMMENT '类型',
  `resultcode` varchar(20) NOT NULL DEFAULT '' COMMENT '接口返回值',
  `createTime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='短信发送日志表';

-- ----------------------------
-- Records of pre_sms_log
-- ----------------------------
