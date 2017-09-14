/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-06-16 14:33:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_source_gift_configs`
-- ----------------------------
DROP TABLE IF EXISTS `pre_source_gift_configs`;
CREATE TABLE `pre_source_gift_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` smallint(4) DEFAULT '0' COMMENT '动作',
  `utm_source` varchar(50) DEFAULT '',
  `utm_medium` varchar(50) DEFAULT '',
  `giftPackageId` int(11) DEFAULT '0' COMMENT '礼包id',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='渠道礼包配置表';

-- ----------------------------
-- Records of pre_source_gift_configs
-- ----------------------------
INSERT INTO `pre_source_gift_configs` VALUES ('1', '1', 'qipaimi', '', '15', '1');
INSERT INTO `pre_source_gift_configs` VALUES ('2', '1', 'unicom', '', '14', '1');
