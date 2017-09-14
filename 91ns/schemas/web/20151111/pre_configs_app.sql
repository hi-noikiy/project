/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.70
Source Server Version : 50539
Source Host           : 192.168.1.70:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-11-12 05:16:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_configs_app`
-- ----------------------------
DROP TABLE IF EXISTS `pre_configs_app`;
CREATE TABLE `pre_configs_app` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(32) DEFAULT NULL COMMENT '键',
  `value` varchar(64) DEFAULT NULL COMMENT '值',
  `remark` varchar(64) DEFAULT NULL COMMENT '备用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_configs_app
-- ----------------------------
INSERT INTO `pre_configs_app` VALUES ('1', 'isShowQrCode', '0', null);
INSERT INTO `pre_configs_app` VALUES ('2', '1.2.6', 'http://app.91ns.com/', null);
