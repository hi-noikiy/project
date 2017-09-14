/*
Navicat MySQL Data Transfer

Source Server         : mySQL
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-13 14:33:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_vip_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_vip_configs`;
CREATE TABLE `pre_vip_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` tinyint(3) DEFAULT NULL,
  `lower` bigint(32) DEFAULT NULL COMMENT '当前vip等级最低经验值',
  `higher` bigint(32) DEFAULT NULL COMMENT '当前vip等级最高经验值',
  `duration` int(11) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `description` text,
  `carId` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='pre_vip_configs';
