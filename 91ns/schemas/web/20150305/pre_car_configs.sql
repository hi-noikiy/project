/*
Navicat MySQL Data Transfer

Source Server         : mySQL
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-05 10:57:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_car_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_car_configs`;
CREATE TABLE `pre_car_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vipLevel` tinyint(3) DEFAULT NULL,
  `typeId` tinyint(3) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `price` int(11) DEFAULT NULL,
  `flashLimit` tinyint(3) DEFAULT NULL,
  `orderType` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `configName` varchar(20) NOT NULL COMMENT '配置名称，索引图片别名用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='座驾配置表';
