/*
Navicat MySQL Data Transfer

Source Server         : mySQL
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-19 21:44:50
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
  `price` int(11) DEFAULT NULL COMMENT '购买价格',
  `duration` int(11) DEFAULT NULL COMMENT '购买的时间',
  `giveDuration` int(11) DEFAULT NULL COMMENT '赠送时间',
  `carId` int(11) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='守护配置表';
