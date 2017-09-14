/*
Navicat MySQL Data Transfer

Source Server         : mySQL
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-20 19:26:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_richer_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_richer_configs`;
CREATE TABLE `pre_richer_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `higher` bigint(32) DEFAULT NULL,
  `lower` bigint(32) DEFAULT NULL,
  `level` tinyint(3) DEFAULT NULL,
  `carId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='富豪配置表';

-- ----------------------------
-- Records of pre_richer_configs
-- ----------------------------
INSERT INTO `pre_richer_configs` VALUES ('1', '平民', '999', '0', '0', '0');
INSERT INTO `pre_richer_configs` VALUES ('2', '1富', '1999', '1000', '1', '0');
INSERT INTO `pre_richer_configs` VALUES ('3', '2富', '3999', '2000', '2', '0');
INSERT INTO `pre_richer_configs` VALUES ('4', '3富', '7999', '4000', '3', '0');
INSERT INTO `pre_richer_configs` VALUES ('5', '4富', '15999', '8000', '4', '0');
INSERT INTO `pre_richer_configs` VALUES ('6', '5富', '31999', '16000', '5', '0');
INSERT INTO `pre_richer_configs` VALUES ('7', '6富', '63999', '32000', '6', '0');
INSERT INTO `pre_richer_configs` VALUES ('8', '7富', '127999', '64000', '7', '0');
INSERT INTO `pre_richer_configs` VALUES ('9', '8富', '255999', '128000', '8', '0');
INSERT INTO `pre_richer_configs` VALUES ('10', '9富', '511999', '256000', '9', '0');
INSERT INTO `pre_richer_configs` VALUES ('11', '男爵', '1023999', '512000', '10', '0');
INSERT INTO `pre_richer_configs` VALUES ('12', '子爵', '2047999', '1024000', '11', '0');
INSERT INTO `pre_richer_configs` VALUES ('13', '伯爵', '4095999', '2048000', '12', '0');
INSERT INTO `pre_richer_configs` VALUES ('14', '侯爵', '8191999', '4096000', '13', '0');
INSERT INTO `pre_richer_configs` VALUES ('15', '公爵', '16383999', '8192000', '14', '0');
INSERT INTO `pre_richer_configs` VALUES ('16', '王爵', '32767999', '16384000', '15', '8');
INSERT INTO `pre_richer_configs` VALUES ('17', '皇帝', '65535999', '32768000', '16', '9');
INSERT INTO `pre_richer_configs` VALUES ('18', '教皇', '9999999999', '65536000', '17', '10');
