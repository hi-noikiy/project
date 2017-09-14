/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.70
Source Server Version : 50539
Source Host           : 192.168.1.70:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-06-02 14:06:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_anchor_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_anchor_configs`;
CREATE TABLE `pre_anchor_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `higher` bigint(32) DEFAULT NULL,
  `lower` bigint(32) DEFAULT NULL,
  `level` tinyint(3) DEFAULT NULL,
  `roomLimitNum` int(11) DEFAULT '0' COMMENT '������������',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COMMENT='主播配置表';

-- ----------------------------
-- Records of pre_anchor_configs
-- ----------------------------
INSERT INTO `pre_anchor_configs` VALUES ('1', '1星', '4999', '1000', '1', '0');
INSERT INTO `pre_anchor_configs` VALUES ('2', '5星', '99999', '50000', '5', '0');
INSERT INTO `pre_anchor_configs` VALUES ('3', '新秀', '999', '0', '0', '0');
INSERT INTO `pre_anchor_configs` VALUES ('4', '2星', '14999', '5000', '2', '0');
INSERT INTO `pre_anchor_configs` VALUES ('5', '3星', '29999', '15000', '3', '0');
INSERT INTO `pre_anchor_configs` VALUES ('6', '4星', '49999', '30000', '4', '0');
INSERT INTO `pre_anchor_configs` VALUES ('7', '1钻', '199999', '100000', '6', '0');
INSERT INTO `pre_anchor_configs` VALUES ('8', '2钻', '349999', '200000', '7', '0');
INSERT INTO `pre_anchor_configs` VALUES ('9', '3钻', '549999', '350000', '8', '0');
INSERT INTO `pre_anchor_configs` VALUES ('10', '4钻', '799999', '550000', '9', '0');
INSERT INTO `pre_anchor_configs` VALUES ('11', '5钻', '1199999', '800000', '10', '0');
INSERT INTO `pre_anchor_configs` VALUES ('12', '1皇冠', '1499999', '1200000', '11', '0');
INSERT INTO `pre_anchor_configs` VALUES ('13', '2皇冠', '1999999', '1500000', '12', '0');
INSERT INTO `pre_anchor_configs` VALUES ('14', '3皇冠', '2999999', '2000000', '13', '0');
INSERT INTO `pre_anchor_configs` VALUES ('15', '4皇冠', '4999999', '3000000', '14', '0');
INSERT INTO `pre_anchor_configs` VALUES ('16', '5皇冠', '7999999', '5000000', '15', '0');
INSERT INTO `pre_anchor_configs` VALUES ('17', '6皇冠', '11999999', '8000000', '16', '0');
INSERT INTO `pre_anchor_configs` VALUES ('18', '7皇冠', '17999999', '12000000', '17', '0');
INSERT INTO `pre_anchor_configs` VALUES ('19', '8皇冠', '24999999', '18000000', '18', '0');
INSERT INTO `pre_anchor_configs` VALUES ('20', '9皇冠', '32999999', '25000000', '19', '0');
INSERT INTO `pre_anchor_configs` VALUES ('21', '10皇冠', '40999999', '33000000', '20', '0');
INSERT INTO `pre_anchor_configs` VALUES ('22', '11皇冠', '49999999', '41000000', '21', '0');
INSERT INTO `pre_anchor_configs` VALUES ('23', '12皇冠', '59999999', '50000000', '22', '0');
INSERT INTO `pre_anchor_configs` VALUES ('24', '13皇冠', '69999999', '60000000', '23', '0');
INSERT INTO `pre_anchor_configs` VALUES ('25', '14皇冠', '79999999', '70000000', '24', '0');
INSERT INTO `pre_anchor_configs` VALUES ('26', '15皇冠', '89999999', '80000000', '25', '0');
INSERT INTO `pre_anchor_configs` VALUES ('27', '16皇冠', '99999999', '90000000', '26', '0');
INSERT INTO `pre_anchor_configs` VALUES ('28', '17皇冠', '109999999', '100000000', '27', '0');
INSERT INTO `pre_anchor_configs` VALUES ('29', '18皇冠', '124999999', '110000000', '28', '0');
INSERT INTO `pre_anchor_configs` VALUES ('30', '19皇冠', '149999999', '125000000', '29', '0');
INSERT INTO `pre_anchor_configs` VALUES ('31', '20皇冠', '174999999', '150000000', '30', '0');
INSERT INTO `pre_anchor_configs` VALUES ('32', '21皇冠', '199999999', '175000000', '31', '0');
INSERT INTO `pre_anchor_configs` VALUES ('33', '22皇冠', '229999999', '200000000', '32', '0');
INSERT INTO `pre_anchor_configs` VALUES ('35', '24皇冠', '289999999', '260000000', '34', '0');
INSERT INTO `pre_anchor_configs` VALUES ('36', '25皇冠', '329999999', '290000000', '35', '0');
INSERT INTO `pre_anchor_configs` VALUES ('37', '26皇冠', '369999999', '330000000', '36', '0');
INSERT INTO `pre_anchor_configs` VALUES ('38', '27皇冠', '999999999', '370000000', '37', '0');
INSERT INTO `pre_anchor_configs` VALUES ('39', '23皇冠', '259999999', '230000000', '33', '0');
