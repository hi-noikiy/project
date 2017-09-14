/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.70
Source Server Version : 50539
Source Host           : 192.168.1.70:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-05-31 14:29:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_vip_configs`
-- ----------------------------
DROP TABLE IF EXISTS `pre_vip_configs`;
CREATE TABLE `pre_vip_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` tinyint(3) DEFAULT NULL,
  `lower` bigint(32) DEFAULT NULL COMMENT '当前vip等级最低经验值',
  `higher` bigint(32) DEFAULT NULL COMMENT '当前vip等级最高经验值',
  `description` text,
  `carId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='pre_vip_configs';

-- ----------------------------
-- Records of pre_vip_configs
-- ----------------------------
INSERT INTO `pre_vip_configs` VALUES ('11', '1', '0', '0', '1. 购买后即时生效并享受普通VIP的对应增值功能。\n2. 在使用期内续费则叠加使用时间。\n3. 若已经拥有至尊VIP的情况下无法购买普通VIP。', '17');
INSERT INTO `pre_vip_configs` VALUES ('12', '2', '0', '0', '1. 购买后即时生效并享受至尊VIP的对应增值功能。\n2. 在使用期内续费则叠加使用时间。\n3.	若已经拥有普通VIP的情况下又购买至尊VIP，则至尊VIP功能立即生效，之前的普通VIP功能依然存在，两种VIP使用时间不叠加。', '7');
