/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.71
Source Server Version : 50539
Source Host           : 192.168.1.71:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-10-14 16:52:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_base_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_base_configs`;
CREATE TABLE `pre_base_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='pre_base_configs';

-- ----------------------------
-- Records of pre_base_configs
-- ----------------------------
INSERT INTO `pre_base_configs` VALUES ('17', 'exchangeLimit', '10000');
INSERT INTO `pre_base_configs` VALUES ('16', 'chatTime', '5');
INSERT INTO `pre_base_configs` VALUES ('15', 'richerLimitLevel', '1');
INSERT INTO `pre_base_configs` VALUES ('18', 'exchangeLimit', '10000.000');
INSERT INTO `pre_base_configs` VALUES ('19', 'ratioNum', '80');
INSERT INTO `pre_base_configs` VALUES ('20', 'roomRichRank', '5');
INSERT INTO `pre_base_configs` VALUES ('23', 'rewardBoxUrl', 'http://m.91ns.com/activities/box');
INSERT INTO `pre_base_configs` VALUES ('22', 'maxNum', '20');
INSERT INTO `pre_base_configs` VALUES ('24', 'getCoinTime', '300');
INSERT INTO `pre_base_configs` VALUES ('25', 'userListNum', '500');
