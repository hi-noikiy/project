/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91nsdb

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-04-14 11:14:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_fans_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_fans_configs`;
CREATE TABLE `pre_fans_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `higher` bigint(32) DEFAULT NULL,
  `lower` bigint(32) DEFAULT NULL,
  `level` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='粉丝配置表';

-- ----------------------------
-- Records of pre_fans_configs
-- ----------------------------
INSERT INTO `pre_fans_configs` VALUES ('1', '无名小卒', '499', '0', '0');
INSERT INTO `pre_fans_configs` VALUES ('2', '鲜为人知', '999', '500', '1');
INSERT INTO `pre_fans_configs` VALUES ('3', '略有所成', '1999', '1000', '2');
INSERT INTO `pre_fans_configs` VALUES ('4', '人见人爱', '3999', '2000', '3');
INSERT INTO `pre_fans_configs` VALUES ('5', '如日中天', '7999', '4000', '4');
INSERT INTO `pre_fans_configs` VALUES ('6', '万人空巷', '15999', '8000', '5');
INSERT INTO `pre_fans_configs` VALUES ('7', '叱咤风云', '31999', '16000', '6');
INSERT INTO `pre_fans_configs` VALUES ('8', '举世闻名', '63999', '32000', '7');
INSERT INTO `pre_fans_configs` VALUES ('9', '载入史册', '127999', '64000', '8');
INSERT INTO `pre_fans_configs` VALUES ('10', '璀璨神话', '255999', '128000', '9');
INSERT INTO `pre_fans_configs` VALUES ('11', '传世天神', '9999999', '256000', '10');
