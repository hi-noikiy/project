/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-03-24 16:36:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_question_configs`
-- ----------------------------
DROP TABLE IF EXISTS `pre_question_configs`;
CREATE TABLE `pre_question_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '安全问题内容',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_question_configs
-- ----------------------------
INSERT INTO `pre_question_configs` VALUES ('1', '父亲的名字', '1');
INSERT INTO `pre_question_configs` VALUES ('2', '宠物的名字', '1');
INSERT INTO `pre_question_configs` VALUES ('3', '初恋的名字', '1');
INSERT INTO `pre_question_configs` VALUES ('4', '小学的名字', '1');
INSERT INTO `pre_question_configs` VALUES ('5', '最喜欢的食物', '1');
INSERT INTO `pre_question_configs` VALUES ('6', '最喜欢的城市', '1');
INSERT INTO `pre_question_configs` VALUES ('7', '最擅长的运动', '1');
