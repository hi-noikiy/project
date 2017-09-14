/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.70
Source Server Version : 50539
Source Host           : 192.168.1.70:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-06-12 11:15:26
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
  `items` varchar(1000) DEFAULT '' COMMENT '【物品类型，物品id，物品数量,有效期】',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='礼包配置表';

-- ----------------------------
-- Records of pre_gift_package_configs
-- ----------------------------
INSERT INTO `pre_gift_package_configs` VALUES ('1', '礼包1', '', '[{\"type\":4,\"id\":1,\"num\":1,\"validity\":0}]');
INSERT INTO `pre_gift_package_configs` VALUES ('2', '礼包2', '', '[{\"type\":4,\"id\":2,\"num\":1,\"validity\":0}]');
INSERT INTO `pre_gift_package_configs` VALUES ('3', '礼包3', '', '[{\"type\":2,\"id\":16,\"num\":1,\"validity\":2332800}]');
INSERT INTO `pre_gift_package_configs` VALUES ('4', '礼包4', '', '[{\"type\":3,\"id\":9,\"num\":5,\"validity\":0}]');
INSERT INTO `pre_gift_package_configs` VALUES ('5', '礼包5', '', '[{\"type\":3,\"id\":10,\"num\":10,\"validity\":0}]');
INSERT INTO `pre_gift_package_configs` VALUES ('6', '礼包6', '', '[{\"type\":3,\"id\":11,\"num\":20,\"validity\":0}]');
INSERT INTO `pre_gift_package_configs` VALUES ('7', '礼包7', '', '[{\"type\":3,\"id\":15,\"num\":10,\"validity\":0}]');
INSERT INTO `pre_gift_package_configs` VALUES ('8', '礼包8', '', '[{\"type\":3,\"id\":17,\"num\":1,\"validity\":0}]');
INSERT INTO `pre_gift_package_configs` VALUES ('9', '礼包9', '', '[{\"type\":4,\"id\":3,\"num\":1,\"validity\":0}]');
INSERT INTO `pre_gift_package_configs` VALUES ('10', '超值大礼包', '', '[{\"type\":0,\"coin\":1000},{\"type\":3,\"id\":10,\"num\":10,\"validity\":0},{\"type\":2,\"id\":29,\"num\":1,\"validity\":1209600}]');
INSERT INTO `pre_gift_package_configs` VALUES ('11', '土豪大礼包', '', '[{\"type\":0,\"coin\":10000},{\"type\":1,\"id\":1,\"validity\":604800},{\"type\":2,\"id\":27,\"num\":1,\"validity\":2419200},{\"type\":3,\"id\":9,\"num\":100,\"validity\":0}]');
INSERT INTO `pre_gift_package_configs` VALUES ('12', '首充礼包1', '', '[{\"type\":0,\"coin\":1000},{\"type\":1,\"validity\":604800},{\"type\":2,\"id\":20,\"validity\":864000}]');
INSERT INTO `pre_gift_package_configs` VALUES ('13', '首充礼包2', '', '[{\"type\":0,\"coin\":10000},{\"type\":1,\"validity\":2592000},{\"type\":2,\"id\":19,\"validity\":2592000}]');
INSERT INTO `pre_gift_package_configs` VALUES ('14', '联通礼包', '', '[{\"type\":0,\"coin\":10000,\"img\":\"/public/web/images/gift/ld48.png\"},{\"type\":2,\"id\":33,\"num\":1,\"validity\":2419200,\"img\":\"/public/web/images/gift/Maserati.png\"},{\"type\":3,\"id\":9,\"num\":100,\"validity\":0,\"img\":\"/public/web/images/gift/xxxy.png\"},{\"type\":3,\"id\":10,\"num\":100,\"validity\":0,\"img\":\"/public/web/images/gift/bbt.png\"},{\"type\":3,\"id\":11,\"num\":100,\"validity\":0,\"img\":\"/public/web/images/gift/dg.png\"},{\"type\":4,\"id\":1,\"num\":10,\"validity\":0,\"img\":\"/public/web/images/gift/ylb48.png\"},{\"type\":4,\"id\":2,\"num\":10,\"validity\":0,\"img\":\"/public/web/images/gift/jlb48.png\"},{\"type\":4,\"id\":4,\"num\":1,\"validity\":0,\"img\":\"/public/web/images/gift/lt48.png\"}]');
INSERT INTO `pre_gift_package_configs` VALUES ('15', '棋牌礼包', '', '[{\"type\":0,\"coin\":10000,\"img\":\"/public/web/images/gift/ld48.png\"},{\"type\":2,\"id\":32,\"num\":1,\"validity\":2419200,\"img\":\"/public/web/images/gift/Jaguar.png\"},{\"type\":3,\"id\":9,\"num\":100,\"validity\":0,\"img\":\"/public/web/images/gift/xxxy.png\"},{\"type\":3,\"id\":10,\"num\":100,\"validity\":0,\"img\":\"/public/web/images/gift/bbt.png\"},{\"type\":3,\"id\":11,\"num\":100,\"validity\":0,\"img\":\"/public/web/images/gift/dg.png\"},{\"type\":4,\"id\":1,\"num\":10,\"validity\":0,\"img\":\"/public/web/images/gift/ylb48.png\"},{\"type\":4,\"id\":2,\"num\":10,\"validity\":0,\"img\":\"/public/web/images/gift/jlb48.png\"},{\"type\":4,\"id\":5,\"num\":1,\"validity\":0,\"img\":\"/public/web/images/gift/7pm48.png\"}]');
