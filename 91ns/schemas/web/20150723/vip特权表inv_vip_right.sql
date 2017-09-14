/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.71_3306
Source Server Version : 50539
Source Host           : 192.168.1.71:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-07-21 14:58:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for inv_vip_right
-- ----------------------------
DROP TABLE IF EXISTS `inv_vip_right`;
CREATE TABLE `inv_vip_right` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `des` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `lasttime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lasttime` (`lasttime`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
