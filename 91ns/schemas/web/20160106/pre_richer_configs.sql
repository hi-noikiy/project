/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.71
Source Server Version : 50539
Source Host           : 192.168.1.71:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2016-01-06 15:42:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_richer_configs`
-- ----------------------------
DROP TABLE IF EXISTS `pre_richer_configs`;
CREATE TABLE `pre_richer_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `higher` bigint(32) DEFAULT NULL,
  `lower` bigint(32) DEFAULT NULL,
  `level` tinyint(3) DEFAULT NULL,
  `carId` int(11) DEFAULT NULL,
  `hornNum` int(11) NOT NULL DEFAULT '0' COMMENT '赠送的金喇叭数量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='富豪配置表';

-- ----------------------------
-- Records of pre_richer_configs
-- ----------------------------
INSERT INTO `pre_richer_configs` VALUES ('1', '屌丝', '999', '0', '0', '0', '0');
INSERT INTO `pre_richer_configs` VALUES ('2', '1富', '1999', '1000', '1', '0', '0');
INSERT INTO `pre_richer_configs` VALUES ('3', '2富', '3999', '2000', '2', '0', '0');
INSERT INTO `pre_richer_configs` VALUES ('4', '3富', '7999', '4000', '3', '0', '0');
INSERT INTO `pre_richer_configs` VALUES ('5', '4富', '15999', '8000', '4', '0', '0');
INSERT INTO `pre_richer_configs` VALUES ('6', '5富', '31999', '16000', '5', '0', '0');
INSERT INTO `pre_richer_configs` VALUES ('7', '6富', '63999', '32000', '6', '0', '0');
INSERT INTO `pre_richer_configs` VALUES ('8', '7富', '127999', '64000', '7', '0', '0');
INSERT INTO `pre_richer_configs` VALUES ('9', '8富', '255999', '128000', '8', '49', '0');
INSERT INTO `pre_richer_configs` VALUES ('10', '9富', '511999', '256000', '9', '44', '0');
INSERT INTO `pre_richer_configs` VALUES ('11', '男爵', '1023999', '512000', '10', '50', '3');
INSERT INTO `pre_richer_configs` VALUES ('12', '子爵', '2047999', '1024000', '11', '51', '5');
INSERT INTO `pre_richer_configs` VALUES ('13', '伯爵', '4095999', '2048000', '12', '52', '6');
INSERT INTO `pre_richer_configs` VALUES ('14', '侯爵', '8191999', '4096000', '13', '53', '8');
INSERT INTO `pre_richer_configs` VALUES ('15', '公爵', '16383999', '8192000', '14', '54', '10');
INSERT INTO `pre_richer_configs` VALUES ('16', '王爵1', '21844999', '16384000', '15', '55', '10');
INSERT INTO `pre_richer_configs` VALUES ('17', '王爵2', '27305999', '21845000', '16', '55', '10');
INSERT INTO `pre_richer_configs` VALUES ('18', '王爵3', '32767999', '27306000', '17', '55', '10');
INSERT INTO `pre_richer_configs` VALUES ('19', '皇帝1', '43689999', '32768000', '18', '56', '20');
INSERT INTO `pre_richer_configs` VALUES ('20', '皇帝2', '54612999', '43690000', '19', '56', '20');
INSERT INTO `pre_richer_configs` VALUES ('21', '皇帝3', '65535999', '54613000', '20', '56', '20');
INSERT INTO `pre_richer_configs` VALUES ('22', '太皇1', '87380999', '65536000', '21', '57', '20');
INSERT INTO `pre_richer_configs` VALUES ('23', '太皇2', '109225999', '87381000', '22', '57', '20');
INSERT INTO `pre_richer_configs` VALUES ('24', '太皇3', '131071999', '109226000', '23', '57', '20');
INSERT INTO `pre_richer_configs` VALUES ('25', '天皇1', '174761999', '131072000', '24', '58', '30');
INSERT INTO `pre_richer_configs` VALUES ('26', '天皇2', '218452999', '174762000', '25', '58', '30');
INSERT INTO `pre_richer_configs` VALUES ('27', '天皇3', '262143999', '218453000', '26', '58', '30');
INSERT INTO `pre_richer_configs` VALUES ('28', '帝皇1', '349524999', '262144000', '27', '59', '30');
INSERT INTO `pre_richer_configs` VALUES ('29', '帝皇2', '436905999', '349525000', '28', '59', '30');
INSERT INTO `pre_richer_configs` VALUES ('30', '帝皇3', '524287999', '436906000', '29', '59', '30');
INSERT INTO `pre_richer_configs` VALUES ('31', '教皇', '99999999999999999', '524288000', '30', '60', '50');
