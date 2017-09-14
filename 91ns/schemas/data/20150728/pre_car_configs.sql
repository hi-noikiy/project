/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.70
Source Server Version : 50539
Source Host           : 192.168.1.70:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-07-28 16:59:01
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
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COMMENT='座驾配置表';

-- ----------------------------
-- Records of pre_car_configs
-- ----------------------------
INSERT INTO `pre_car_configs` VALUES ('4', '2', '布加迪威航', '', '10680', '1', '0', 'Bujiadiwei');
INSERT INTO `pre_car_configs` VALUES ('5', '3', '路虎揽胜', '', '4080', '15', '0', 'Range_Rover');
INSERT INTO `pre_car_configs` VALUES ('6', '2', '阿斯顿马丁', '', '8800', '20', '0', 'Aston_Martin');
INSERT INTO `pre_car_configs` VALUES ('7', '8', '奔驰E', '', '4000', '14', '0', 'Benz_E');
INSERT INTO `pre_car_configs` VALUES ('8', '4', '宾利', '富豪等级：王爵', '6900', '0', '0', 'Bentley');
INSERT INTO `pre_car_configs` VALUES ('9', '4', '柯尼塞格', '富豪等级：皇帝', '7700', '0', '0', 'Kenisaige');
INSERT INTO `pre_car_configs` VALUES ('10', '4', '劳斯莱斯', '富豪等级：教皇', '10000', '0', '0', 'Rolls_Royce');
INSERT INTO `pre_car_configs` VALUES ('11', '1', '超能大白', '', '32800', '0', '0', 'Baymax');
INSERT INTO `pre_car_configs` VALUES ('12', '2', '彼得比尔特', '', '5800', '0', '0', 'Peter_Birte');
INSERT INTO `pre_car_configs` VALUES ('13', '1', '龙猫', '', '23800', '0', '0', 'Totoro');
INSERT INTO `pre_car_configs` VALUES ('14', '2', '宝马i8', '', '6800', '0', '0', 'BMW_I8');
INSERT INTO `pre_car_configs` VALUES ('15', '1', '灵魂哈雷', '', '56800', '0', '0', 'Haley');
INSERT INTO `pre_car_configs` VALUES ('16', '5', '雪佛兰科迈罗', '', '2400', '0', '0', 'Chevrolet_Camaro');
INSERT INTO `pre_car_configs` VALUES ('17', '6', '特斯拉', '', '3800', '0', '0', 'Tesla');
INSERT INTO `pre_car_configs` VALUES ('18', '9', '兰博基尼', '', '8000', '0', '0', 'Lamborghini');
INSERT INTO `pre_car_configs` VALUES ('19', '3', '凯美瑞', '', '2480', '0', '0', 'Camry');
INSERT INTO `pre_car_configs` VALUES ('20', '3', '马自达', '', '2180', '0', '0', 'Mazda');
INSERT INTO `pre_car_configs` VALUES ('21', '3', '飞度', '', '1580', '0', '0', 'Fit');
INSERT INTO `pre_car_configs` VALUES ('22', '3', '标致307', '', '1380', '0', '0', 'Peugeot_307');
INSERT INTO `pre_car_configs` VALUES ('25', '7', '法拉利', '', '7800', '0', '0', 'Ferrari');
INSERT INTO `pre_car_configs` VALUES ('24', '3', '沃尔沃XC90', '', '4880', '0', '0', 'Volvo_XC90');
INSERT INTO `pre_car_configs` VALUES ('26', '3', '雷克萨斯ES', '', '3180', '0', '0', 'Lexus_ES');
INSERT INTO `pre_car_configs` VALUES ('27', '1', '宇宙飞船', '', '15800', '0', '0', 'Spacecraft');
INSERT INTO `pre_car_configs` VALUES ('28', '10', '魔法扫帚', '坐腻了车座？来试试魔法的扫帚吧。', '10000', '0', '0', 'Magic_broom');
INSERT INTO `pre_car_configs` VALUES ('29', '10', '拖拉机', '开上价值95万人民币的拖拉机，坐拥另类的拉风！', '8000', '0', '0', 'Tractor');
INSERT INTO `pre_car_configs` VALUES ('30', '10', '自行车', '一辆自行车，邀请女神坐上青葱岁月的回忆。', '5000', '0', '0', 'bike');
INSERT INTO `pre_car_configs` VALUES ('32', '11', '玛莎拉蒂', '', '10000', '0', '0', 'Maserati');
INSERT INTO `pre_car_configs` VALUES ('33', '11', '捷豹', '', '10000', '0', '0', 'Jaguar');
INSERT INTO `pre_car_configs` VALUES ('34', '8', '奔驰S', '', '4000', '0', '0', 'Benz_S');
INSERT INTO `pre_car_configs` VALUES ('35', '10', '呆萌木马', '可爱值奔涌的平台最萌座驾。', '10000', '0', '0', 'Trojan');
INSERT INTO `pre_car_configs` VALUES ('36', '10', '魔法飞毯', '阿拉丁会见公主的魔法道具。', '10000', '0', '0', 'Magic_carpet');
INSERT INTO `pre_car_configs` VALUES ('37', '10', '南瓜车', '让灰姑娘变公主的魔法坐骑。', '10000', '0', '0', 'Pumpkin_car');
INSERT INTO `pre_car_configs` VALUES ('38', '10', '保时捷', '经典顶级跑车，闻名世界车坛。', '10500', '0', '0', 'Porsche');
INSERT INTO `pre_car_configs` VALUES ('39', '10', '劳斯莱斯银魅', '银魅，劳斯莱斯的杀手锏产品。', '15000', '0', '0', 'Silver_Ghost');
INSERT INTO `pre_car_configs` VALUES ('40', '10', '黄金战车', '真土豪的不二选择，高富帅的身份标识。', '20000', '0', '0', 'Golden_chariot');
INSERT INTO `pre_car_configs` VALUES ('41', '10', '独角兽', '独角兽', '0', '0', '0', 'Unicorn');
