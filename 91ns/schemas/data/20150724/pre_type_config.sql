/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.70
Source Server Version : 50539
Source Host           : 192.168.1.70:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-07-24 15:39:13
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
  `roomAnimate` tinyint(3) DEFAULT '0',
  `showStatus` tinyint(3) DEFAULT '0' COMMENT '是否显示',
  `sellStatus` tinyint(3) DEFAULT '0' COMMENT '是否非卖品',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COMMENT='pre_base_config';

-- ----------------------------
-- Records of pre_type_config
-- ----------------------------
INSERT INTO `pre_type_config` VALUES ('5', 'giftType', '1', '0', '1425287745', null, '0', '0', '0');
INSERT INTO `pre_type_config` VALUES ('6', 'carType', '2', '0', '1425287745', null, '0', '0', '0');
INSERT INTO `pre_type_config` VALUES ('7', 'foodType', '3', '0', '1425287745', null, '0', '0', '0');
INSERT INTO `pre_type_config` VALUES ('17', '科幻', '1', '2', '1426822334', '拥有炫丽进场动画', '1', '1', '1');
INSERT INTO `pre_type_config` VALUES ('18', '豪华', '2', '2', '1426823131', '拥有炫丽进场动画', '1', '1', '1');
INSERT INTO `pre_type_config` VALUES ('19', '经济', '3', '2', '1426823428', '拥有炫丽进场动画', '1', '1', '1');
INSERT INTO `pre_type_config` VALUES ('20', '专属', '4', '2', '1426823440', '拥有炫丽进场动画', '1', '1', '0');
INSERT INTO `pre_type_config` VALUES ('21', '热门', '1', '1', '1426823606', '', '0', '1', '1');
INSERT INTO `pre_type_config` VALUES ('22', '豪华', '2', '1', '1426823611', '', '0', '1', '1');
INSERT INTO `pre_type_config` VALUES ('23', '暂时取消（热门排行）', '5', '1', '1430361700', '', '0', '1', '1');
INSERT INTO `pre_type_config` VALUES ('24', '非卖品', '6', '1', '1430361700', null, '0', '0', '0');
INSERT INTO `pre_type_config` VALUES ('27', '签到', '5', '2', '1432775977', '', '0', '0', '0');
INSERT INTO `pre_type_config` VALUES ('28', '普通VIP', '6', '2', '1432776888', '拥有炫丽进场动画', '1', '0', '0');
INSERT INTO `pre_type_config` VALUES ('29', '至尊VIP', '7', '2', '1432776906', '拥有炫丽进场动画', '1', '0', '0');
INSERT INTO `pre_type_config` VALUES ('30', '白银守护', '8', '2', '1432776923', '拥有炫丽进场动画', '1', '0', '0');
INSERT INTO `pre_type_config` VALUES ('31', '黄金守护', '9', '2', '1432776933', '拥有炫丽进场动画', '1', '0', '0');
INSERT INTO `pre_type_config` VALUES ('32', '活动', '10', '2', '1432868413', '拥有炫丽进场动画', '1', '1', '0');
INSERT INTO `pre_type_config` VALUES ('33', '渠道专属', '11', '2', '1433752646', '', '1', '0', '0');
INSERT INTO `pre_type_config` VALUES ('35', '专属', '8', '1', '1436177265', '', '0', null, null);
INSERT INTO `pre_type_config` VALUES ('34', '幸运', '7', '1', '1434596556', '', '0', null, null);
INSERT INTO `pre_type_config` VALUES ('36', '抢星礼物', '9', '1', '1437709574', '', '0', null, null);
