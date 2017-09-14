/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.71_3306
Source Server Version : 50539
Source Host           : 192.168.1.71:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-08-26 20:15:17
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_family_skin
-- ----------------------------
DROP TABLE IF EXISTS `pre_family_skin`;
CREATE TABLE `pre_family_skin` (
  `fid` int(11) NOT NULL,
  `backgroundColor` varchar(100) DEFAULT NULL,
  `backgroundImg` varchar(255) DEFAULT NULL,
  `styleType` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
