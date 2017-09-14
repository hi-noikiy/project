/*
Navicat MySQL Data Transfer

Source Server         : mySQL
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-20 19:26:40
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
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='主播配置表';

-- ----------------------------
-- Records of pre_anchor_configs
-- ----------------------------
INSERT INTO `pre_anchor_configs` VALUES ('1', '棒棒糖1', '4999', '1000', '1', '0');
INSERT INTO `pre_anchor_configs` VALUES ('2', '棒棒糖5', '99999', '50000', '5', '0');
INSERT INTO `pre_anchor_configs` VALUES ('3', '平民', '999', '0', '0', '0');
INSERT INTO `pre_anchor_configs` VALUES ('4', '棒棒糖2', '14999', '5000', '2', '0');
INSERT INTO `pre_anchor_configs` VALUES ('5', '棒棒糖3', '29999', '15000', '3', '0');
INSERT INTO `pre_anchor_configs` VALUES ('6', '棒棒糖4', '49999', '30000', '4', '0');
INSERT INTO `pre_anchor_configs` VALUES ('7', '蝴蝶结1', '199999', '100000', '6', '0');
INSERT INTO `pre_anchor_configs` VALUES ('8', '蝴蝶结2', '349999', '200000', '7', '0');
INSERT INTO `pre_anchor_configs` VALUES ('9', '蝴蝶结3', '549999', '350000', '8', '0');
INSERT INTO `pre_anchor_configs` VALUES ('10', '蝴蝶结4', '799999', '550000', '9', '0');
INSERT INTO `pre_anchor_configs` VALUES ('11', '蝴蝶结5', '1199999', '800000', '10', '0');
INSERT INTO `pre_anchor_configs` VALUES ('12', '口红1', '1499999', '1200000', '11', '0');
INSERT INTO `pre_anchor_configs` VALUES ('13', '口红2', '1999999', '1500000', '12', '0');
INSERT INTO `pre_anchor_configs` VALUES ('14', '口红3', '2999999', '2000000', '13', '0');
INSERT INTO `pre_anchor_configs` VALUES ('15', '口红4', '4999999', '3000000', '14', '0');
INSERT INTO `pre_anchor_configs` VALUES ('16', '口红5', '7999999', '5000000', '15', '0');
INSERT INTO `pre_anchor_configs` VALUES ('17', '口红6', '11999999', '8000000', '16', '0');
INSERT INTO `pre_anchor_configs` VALUES ('18', '口红7', '17999999', '12000000', '17', '0');
INSERT INTO `pre_anchor_configs` VALUES ('19', '口红8', '24999999', '18000000', '18', '0');
INSERT INTO `pre_anchor_configs` VALUES ('20', '口红9', '32999999', '25000000', '19', '0');
INSERT INTO `pre_anchor_configs` VALUES ('21', '口红10', '40999999', '33000000', '20', '0');
INSERT INTO `pre_anchor_configs` VALUES ('22', '口红11', '49999999', '41000000', '21', '0');
INSERT INTO `pre_anchor_configs` VALUES ('23', '口红12', '59999999', '50000000', '22', '0');
INSERT INTO `pre_anchor_configs` VALUES ('24', '口红13', '69999999', '60000000', '23', '0');
INSERT INTO `pre_anchor_configs` VALUES ('25', '口红14', '79999999', '70000000', '24', '0');
INSERT INTO `pre_anchor_configs` VALUES ('26', '高跟鞋1', '89999999', '80000000', '25', '0');
INSERT INTO `pre_anchor_configs` VALUES ('27', '高跟鞋2', '99999999', '90000000', '26', '0');
INSERT INTO `pre_anchor_configs` VALUES ('28', '高跟鞋3', '109999999', '100000000', '27', '0');
INSERT INTO `pre_anchor_configs` VALUES ('29', '高跟鞋4', '124999999', '110000000', '28', '0');
INSERT INTO `pre_anchor_configs` VALUES ('30', '高跟鞋5', '149999999', '125000000', '29', '0');
INSERT INTO `pre_anchor_configs` VALUES ('31', '钻戒1', '174999999', '150000000', '30', '0');
INSERT INTO `pre_anchor_configs` VALUES ('32', '钻戒2', '199999999', '175000000', '31', '0');
INSERT INTO `pre_anchor_configs` VALUES ('33', '钻戒3', '229999999', '200000000', '32', '0');
INSERT INTO `pre_anchor_configs` VALUES ('34', '钻戒4', '259999999', '230000000', '33', '0');
INSERT INTO `pre_anchor_configs` VALUES ('35', '钻戒5', '289999999', '260000000', '34', '0');
INSERT INTO `pre_anchor_configs` VALUES ('36', '皇冠1', '329999999', '290000000', '35', '0');
INSERT INTO `pre_anchor_configs` VALUES ('37', '皇冠2', '369999999', '330000000', '36', '0');
INSERT INTO `pre_anchor_configs` VALUES ('38', '皇冠3', '99999999999', '370000000', '37', '0');
