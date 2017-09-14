/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-05-20 10:53:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_gift_package_configs`
-- ----------------------------
DROP TABLE IF EXISTS `pre_gift_package_configs`;
CREATE TABLE `pre_gift_package_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT '' COMMENT '礼包名称',
  `desc` varchar(255) DEFAULT '' COMMENT '礼包描述',
  `items` varchar(255) DEFAULT '' COMMENT '【物品类型，物品id，物品数量,有效期】',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='礼包配置表';

-- ----------------------------
-- Records of pre_gift_package_configs
-- ----------------------------
INSERT INTO `pre_gift_package_configs` VALUES ('1', '礼包1', '', '[{\"type\":4,\"id\":1,\"num\":1,\"validity\":0}]');
INSERT INTO `pre_gift_package_configs` VALUES ('2', '礼包2', '', '[{\"type\":4,\"id\":2,\"num\":1,\"validity\":0}]');
INSERT INTO `pre_gift_package_configs` VALUES ('3', '礼包3', '', '[{\"type\":2,\"id\":20,\"num\":1,\"validity\":2332800}]');
INSERT INTO `pre_gift_package_configs` VALUES ('4', '礼包4', '', '[{\"type\":3,\"id\":9,\"num\":5,\"validity\":0}]');
INSERT INTO `pre_gift_package_configs` VALUES ('5', '礼包5', '', '[{\"type\":3,\"id\":10,\"num\":10,\"validity\":0}]');
INSERT INTO `pre_gift_package_configs` VALUES ('6', '礼包6', '', '[{\"type\":3,\"id\":13,\"num\":20,\"validity\":0}]');
INSERT INTO `pre_gift_package_configs` VALUES ('7', '礼包7', '', '[{\"type\":3,\"id\":15,\"num\":10,\"validity\":0}]');
INSERT INTO `pre_gift_package_configs` VALUES ('8', '礼包8', '', '[{\"type\":3,\"id\":17,\"num\":1,\"validity\":0}]');
INSERT INTO `pre_gift_package_configs` VALUES ('9', '礼包9', '', '[{\"type\":4,\"id\":3,\"num\":1,\"validity\":0}]');
