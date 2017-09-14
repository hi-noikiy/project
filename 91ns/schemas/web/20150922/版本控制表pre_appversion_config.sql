/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.70
Source Server Version : 50539
Source Host           : 192.168.1.70:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-09-23 20:14:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_appversion_config
-- ----------------------------
DROP TABLE IF EXISTS `pre_appversion_config`;
CREATE TABLE `pre_appversion_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device` tinyint(1) DEFAULT '0',
  `updateContent` text,
  `size` float(10,2) DEFAULT '0.00',
  `version` varchar(255) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `addtime` (`addtime`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_appversion_config
-- ----------------------------
INSERT INTO `pre_appversion_config` VALUES ('2', '0', '●新手引导完善;●各种已知bug修复', '20.70', '1.1.4', '1442784600', '1');
INSERT INTO `pre_appversion_config` VALUES ('3', '1', '中秋活动来啦，大家一起来刷月饼;修复了旧版本的大量bug，提高稳定性', '22.90', '1.2.2', '1442784600', '1');
