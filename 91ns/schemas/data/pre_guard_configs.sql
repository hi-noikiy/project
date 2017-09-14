/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.70
Source Server Version : 50539
Source Host           : 192.168.1.70:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-03-30 18:11:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_guard_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_guard_configs`;
CREATE TABLE `pre_guard_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `level` tinyint(3) DEFAULT NULL,
  `carId` int(11) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='守护配置表';

-- ----------------------------
-- Records of pre_guard_configs
-- ----------------------------
INSERT INTO `pre_guard_configs` VALUES ('7', '黄金守护', '1', '6', '');
INSERT INTO `pre_guard_configs` VALUES ('15', '白银守护', '2', '7', '');
