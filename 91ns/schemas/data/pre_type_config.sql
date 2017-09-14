/*
Navicat MySQL Data Transfer

Source Server         : mySQL
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-20 19:26:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_type_config
-- ----------------------------
DROP TABLE IF EXISTS `pre_type_config`;
CREATE TABLE `pre_type_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `typeId` int(11) DEFAULT NULL,
  `parentTypeId` int(11) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `roomAnimate` tinyint(3) DEFAULT '0' COMMENT '��Ҫ���������ϣ�ӵ�����ݽ�����֮���Ƿ��д�����ݹ㲥',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='pre_base_config';

-- ----------------------------
-- Records of pre_type_config
-- ----------------------------
INSERT INTO `pre_type_config` VALUES ('5', 'giftType', '1', '0', '1425287745', null, '0');
INSERT INTO `pre_type_config` VALUES ('6', 'carType', '2', '0', '1425287745', null, '0');
INSERT INTO `pre_type_config` VALUES ('7', 'foodType', '3', '0', '1425287745', null, '0');
INSERT INTO `pre_type_config` VALUES ('17', '奢华', '1', '2', '1426822334', '拥有炫丽进场动画', '1');
INSERT INTO `pre_type_config` VALUES ('18', '豪华', '2', '2', '1426823131', '', '0');
INSERT INTO `pre_type_config` VALUES ('19', '经济', '3', '2', '1426823428', '', '0');
INSERT INTO `pre_type_config` VALUES ('20', '专属', '4', '2', '1426823440', '拥有炫丽进场动画', '1');
INSERT INTO `pre_type_config` VALUES ('21', '普通', '1', '1', '1426823606', '', '0');
INSERT INTO `pre_type_config` VALUES ('22', '豪华', '2', '1', '1426823611', '', '0');
INSERT INTO `pre_type_config` VALUES ('23', '守护', '3', '1', '1426823616', '', '0');
INSERT INTO `pre_type_config` VALUES ('24', '特殊', '4', '1', '1426823621', '', '0');
