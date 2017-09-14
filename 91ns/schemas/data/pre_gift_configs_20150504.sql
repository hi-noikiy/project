/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.70
Source Server Version : 50539
Source Host           : 192.168.1.70:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-05-04 16:36:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_gift_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_gift_configs`;
CREATE TABLE `pre_gift_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vipLevel` tinyint(3) DEFAULT '0',
  `richerLevel` tinyint(3) DEFAULT '0',
  `typeId` tinyint(3) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `coin` int(11) DEFAULT NULL,
  `cash` int(11) DEFAULT NULL,
  `recvCoin` int(8) DEFAULT NULL,
  `discount` tinyint(1) DEFAULT NULL,
  `freeCount` tinyint(1) DEFAULT NULL,
  `littleFlag` tinyint(1) DEFAULT NULL,
  `orderType` int(11) DEFAULT NULL,
  `createTime` int(11) DEFAULT NULL,
  `configName` varchar(20) NOT NULL COMMENT '配置名称，索引图片别名用',
  `guardFlag` tinyint(1) DEFAULT '0' COMMENT '是否需要守护',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COMMENT='礼物配置表';

-- ----------------------------
-- Records of pre_gift_configs
-- ----------------------------
INSERT INTO `pre_gift_configs` VALUES ('4', '0', '0', '1', '红玫瑰', '0', '5', '2', '0', '0', '1', '0', '1426823913', 'mg', '0');
INSERT INTO `pre_gift_configs` VALUES ('5', '0', '5', '2', '甜蜜约会', '0', '20000', '10000', '9', '0', '0', '0', '1426823997', 'tmyh', '0');
INSERT INTO `pre_gift_configs` VALUES ('6', '0', '0', '1', '掌声', '5', '0', '0', '0', '0', '1', '0', '1426841925', 'gz', '0');
INSERT INTO `pre_gift_configs` VALUES ('7', '0', '0', '1', '砖头', '0', '5', '2', '0', '0', '1', '0', '1426842002', 'bn', '0');
INSERT INTO `pre_gift_configs` VALUES ('8', '0', '0', '1', '香吻', '0', '10', '5', '0', '0', '1', '0', '1426842148', 'xw', '0');
INSERT INTO `pre_gift_configs` VALUES ('9', '0', '0', '6', '心心相印', '0', '10', '5', '0', '0', '1', '0', '1426842200', 'xxxy', '0');
INSERT INTO `pre_gift_configs` VALUES ('10', '0', '0', '6', '棒棒糖', '0', '10', '5', '0', '0', '1', '0', '1426842233', 'bbt', '0');
INSERT INTO `pre_gift_configs` VALUES ('11', '0', '0', '6', '蛋糕', '0', '10', '5', '0', '0', '1', '0', '1426842261', 'dg', '0');
INSERT INTO `pre_gift_configs` VALUES ('12', '0', '0', '1', '勿忘我', '20', '0', '0', '0', '0', '1', '0', '1426842290', 'www', '0');
INSERT INTO `pre_gift_configs` VALUES ('13', '0', '0', '1', '巧克力', '0', '20', '10', '0', '0', '1', '0', '1426842329', 'qkl', '0');
INSERT INTO `pre_gift_configs` VALUES ('14', '0', '0', '1', '红酒', '0', '20', '10', '0', '0', '1', '0', '1426842359', 'hj', '0');
INSERT INTO `pre_gift_configs` VALUES ('15', '0', '0', '6', '玫瑰花束', '0', '50', '25', '0', '0', '1', '0', '1426842437', 'hs', '0');
INSERT INTO `pre_gift_configs` VALUES ('16', '0', '0', '1', '香水', '50', '0', '0', '0', '0', '1', '0', '1426842548', 'xs', '0');
INSERT INTO `pre_gift_configs` VALUES ('17', '0', '0', '2', '情侣对戒', '0', '1000', '500', '9', '0', '1', '0', '1426842604', 'qldj', '0');
INSERT INTO `pre_gift_configs` VALUES ('18', '0', '0', '6', '水晶鞋', '0', '1000', '500', '0', '0', '1', '0', '1426842678', 'sjx', '0');
INSERT INTO `pre_gift_configs` VALUES ('19', '0', '0', '2', 'LV包', '0', '1000', '500', '9', '0', '1', '0', '1426842734', 'lvbb', '0');
INSERT INTO `pre_gift_configs` VALUES ('20', '0', '0', '6', '劳力士', '0', '2000', '1000', '0', '0', '1', '0', '1426842770', 'sb', '0');
INSERT INTO `pre_gift_configs` VALUES ('21', '0', '0', '2', '钻石', '0', '2000', '1000', '9', '0', '1', '0', '1426842842', 'zs', '0');
INSERT INTO `pre_gift_configs` VALUES ('22', '0', '0', '2', '皇冠', '0', '2000', '1000', '9', '0', '1', '0', '1426842875', 'hg', '0');
INSERT INTO `pre_gift_configs` VALUES ('23', '0', '0', '2', '兰博基尼', '0', '20000', '10000', '9', '0', '0', '0', '1426843012', 'lbjn', '0');
INSERT INTO `pre_gift_configs` VALUES ('24', '0', '0', '2', '私人游艇', '0', '60000', '30000', '9', '0', '0', '0', '1426843042', 'yt', '0');
INSERT INTO `pre_gift_configs` VALUES ('25', '0', '0', '1', '蓝色妖姬', '0', '5', '3', '0', '0', '1', '0', '1426843095', 'lsyj', '1');
INSERT INTO `pre_gift_configs` VALUES ('26', '0', '0', '2', '挖掘机', '0', '1000', '500', '0', '0', '0', '0', '1426843128', 'dskwjj', '1');
INSERT INTO `pre_gift_configs` VALUES ('27', '0', '0', '2', '切糕车', '0', '1000', '500', '0', '0', '0', '0', '1426843156', 'dsmqg', '1');
INSERT INTO `pre_gift_configs` VALUES ('28', '0', '0', '2', '为你心动', '0', '3000', '1500', '0', '0', '0', '0', '1426843186', 'wnxd', '1');
INSERT INTO `pre_gift_configs` VALUES ('29', '0', '0', '2', '为你伴舞', '0', '3000', '1500', '0', '0', '0', '0', '1426843211', 'wnbw', '1');
INSERT INTO `pre_gift_configs` VALUES ('30', '0', '0', '2', '甜蜜骑行', '0', '3000', '1500', '0', '0', '0', '0', '1426843232', 'tmqx', '1');
INSERT INTO `pre_gift_configs` VALUES ('31', '0', '0', '2', '爱的火山', '0', '10000', '5000', '0', '0', '0', '0', '1426843271', 'hsbf', '1');
INSERT INTO `pre_gift_configs` VALUES ('32', '0', '0', '2', '幸福摩天轮', '0', '20000', '10000', '0', '0', '0', '0', '1426843296', 'mtl', '1');
INSERT INTO `pre_gift_configs` VALUES ('33', '0', '0', '2', '烛光晚餐', '0', '30000', '15000', '0', '0', '0', '0', '1426843324', 'zgwc', '1');
INSERT INTO `pre_gift_configs` VALUES ('34', '0', '0', '2', '私人岛屿', '0', '50000', '25000', '0', '0', '0', '0', '1426843345', 'srdy', '1');
INSERT INTO `pre_gift_configs` VALUES ('35', '1', '0', '6', '金玫瑰', '0', '0', '0', '0', '10', '1', '0', '1426843447', 'jmg', '0');
INSERT INTO `pre_gift_configs` VALUES ('36', '0', '1', '1', '月饼', '0', '50', '25', '0', '0', '1', '0', '1426843561', 'yb', '0');
INSERT INTO `pre_gift_configs` VALUES ('37', '0', '5', '2', '烟花', '0', '10000', '5000', '9', '0', '0', '0', '1426843617', 'yh', '0');
INSERT INTO `pre_gift_configs` VALUES ('38', '0', '8', '2', 'BeMyGirl', '0', '60000', '30000', '9', '0', '0', '0', '1426843781', 'zwnpy', '0');
