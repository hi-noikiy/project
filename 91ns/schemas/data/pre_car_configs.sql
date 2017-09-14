/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.70
Source Server Version : 50539
Source Host           : 192.168.1.70:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-04-10 21:28:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_car_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_car_configs`;
CREATE TABLE `pre_car_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeId` tinyint(3) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `price` int(11) DEFAULT NULL,
  `orderType` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `configName` varchar(20) NOT NULL COMMENT '配置名称，索引图片别名用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='座驾配置表';

-- ----------------------------
-- Records of pre_car_configs
-- ----------------------------
INSERT INTO `pre_car_configs` VALUES ('4', '1', '布加迪威航', '', '106800', '1', '0', 'bujiadiweihang');
INSERT INTO `pre_car_configs` VALUES ('5', '2', '路虎揽胜', '', '10680', '15', '0', 'luhulansheng');
INSERT INTO `pre_car_configs` VALUES ('6', '1', '阿斯顿马丁', '', '23800', '20', '0', 'asidunmading');
INSERT INTO `pre_car_configs` VALUES ('7', '2', '奔驰E', '', '4080', '14', '0', 'benciE');
INSERT INTO `pre_car_configs` VALUES ('8', '4', '宾利', '富豪等级：王爵', '0', '0', '0', 'binli');
INSERT INTO `pre_car_configs` VALUES ('9', '4', '柯尼塞格', '富豪等级：皇帝', '0', '0', '0', 'kenisaige');
INSERT INTO `pre_car_configs` VALUES ('10', '4', '劳斯莱斯', '富豪等级：教皇', '0', '0', '0', 'laosilaisi');
INSERT INTO `pre_car_configs` VALUES ('11', '1', '兰博基尼', '', '56800', '0', '0', 'lanbojini');
INSERT INTO `pre_car_configs` VALUES ('12', '1', '彼得比尔特', '', '24800', '0', '0', 'pidebierte');
INSERT INTO `pre_car_configs` VALUES ('13', '1', '法拉利', '', '32800', '0', '0', 'falali');
INSERT INTO `pre_car_configs` VALUES ('14', '1', '宝马i8', '', '24800', '0', '0', 'baomai8');
INSERT INTO `pre_car_configs` VALUES ('15', '2', '沃尔沃XC90', '', '5800', '0', '0', 'woerwoXC90');
INSERT INTO `pre_car_configs` VALUES ('16', '2', '雪佛兰科迈罗', '', '4180', '0', '0', 'xuefulankemailuo');
INSERT INTO `pre_car_configs` VALUES ('17', '2', '特斯拉', '', '4180', '0', '0', 'tesila');
INSERT INTO `pre_car_configs` VALUES ('18', '2', '雷克萨斯ES', '', '3180', '0', '0', 'leikesasiES');
INSERT INTO `pre_car_configs` VALUES ('19', '3', '凯美瑞', '', '2480', '0', '0', 'kaimeirui');
INSERT INTO `pre_car_configs` VALUES ('20', '3', '马自达', '', '2180', '0', '0', 'mazida');
INSERT INTO `pre_car_configs` VALUES ('21', '3', '飞度', '', '1580', '0', '0', 'feidu');
INSERT INTO `pre_car_configs` VALUES ('22', '3', '标志307', '', '1380', '0', '0', 'biaozhi307');
