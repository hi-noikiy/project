/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.70
Source Server Version : 50539
Source Host           : 192.168.1.70:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-11-05 03:53:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_car_configs`
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
  `hasBigCar` tinyint(3) DEFAULT '0' COMMENT '是否有大座驾',
  `positionX1` int(11) DEFAULT '0' COMMENT 'X轴位置1',
  `positionY1` int(11) DEFAULT '0' COMMENT 'Y轴位置1',
  `sort` int(11) DEFAULT '1' COMMENT '排序',
  `positionX2` int(11) DEFAULT '0' COMMENT 'X轴位置2',
  `positionY2` int(11) DEFAULT '0' COMMENT 'Y轴位置2',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COMMENT='座驾配置表';

-- ----------------------------
-- Records of pre_car_configs
-- ----------------------------
INSERT INTO `pre_car_configs` VALUES ('4', '2', '布加迪威航', '', '10680', '1', '0', 'Bujiadiwei', '0', '0', '0', '260', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('5', '3', '路虎揽胜', '', '4080', '15', '0', 'Range_Rover', '0', '0', '0', '160', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('6', '2', '阿斯顿马丁', '', '8800', '20', '0', 'Aston_Martin', '0', '0', '0', '210', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('7', '8', '奔驰E', '', '4000', '14', '0', 'Benz_E', '0', '0', '0', '120', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('8', '4', '宾利', '富豪等级：王爵', '6900', '0', '0', 'Bentley', '0', '0', '0', '280', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('9', '4', '柯尼塞格', '富豪等级：皇帝', '7700', '0', '0', 'Kenisaige', '0', '0', '0', '310', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('10', '4', '劳斯莱斯', '富豪等级：教皇', '10000', '0', '0', 'Rolls_Royce', '0', '0', '0', '330', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('11', '1', '超能大白', '', '32800', '0', '0', 'Baymax', '0', '-137', '268', '320', '-137', '268');
INSERT INTO `pre_car_configs` VALUES ('12', '2', '彼得比尔特', '', '5800', '0', '0', 'Peter_Birte', '0', '0', '0', '190', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('13', '1', '龙猫', '', '23800', '0', '0', 'Totoro', '0', '0', '0', '300', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('14', '2', '宝马i8', '', '6800', '0', '0', 'BMW_I8', '0', '0', '0', '200', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('15', '1', '灵魂哈雷', '', '56800', '0', '0', 'Haley', '0', '-149', '293', '340', '-149', '293');
INSERT INTO `pre_car_configs` VALUES ('16', '5', '雪佛兰科迈罗', '', '2400', '0', '0', 'Chevrolet_Camaro', '0', '0', '0', '130', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('17', '6', '特斯拉', '', '3800', '0', '0', 'Tesla', '0', '0', '0', '110', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('18', '9', '兰博基尼', '', '8000', '0', '0', 'Lamborghini', '0', '0', '0', '240', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('19', '3', '凯美瑞', '', '2480', '0', '0', 'Camry', '0', '0', '0', '140', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('20', '3', '马自达', '', '2180', '0', '0', 'Mazda', '0', '0', '0', '100', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('21', '3', '飞度', '', '1580', '0', '0', 'Fit', '0', '0', '0', '50', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('22', '3', '标致307', '', '1380', '0', '0', 'Peugeot_307', '0', '0', '0', '40', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('25', '7', '法拉利', '', '7800', '0', '0', 'Ferrari', '0', '0', '0', '220', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('24', '3', '沃尔沃XC90', '', '4880', '0', '0', 'Volvo_XC90', '0', '0', '0', '170', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('26', '3', '雷克萨斯ES', '', '3180', '0', '0', 'Lexus_ES', '0', '0', '0', '150', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('27', '1', '宇宙飞船', '', '15800', '0', '0', 'Spacecraft', '0', '-143', '259', '270', '-143', '259');
INSERT INTO `pre_car_configs` VALUES ('28', '10', '魔法扫帚', '坐腻了车座？来试试魔法的扫帚吧。', '10000', '0', '0', 'Magic_broom', '0', '0', '0', '30', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('29', '10', '拖拉机', '开上价值95万人民币的拖拉机，坐拥另类的拉风！', '8000', '0', '0', 'Tractor', '0', '-170', '213', '20', '-170', '213');
INSERT INTO `pre_car_configs` VALUES ('30', '10', '自行车', '一辆自行车，邀请女神坐上青葱岁月的回忆。', '5000', '0', '0', 'bike', '0', '-218', '191', '10', '-218', '191');
INSERT INTO `pre_car_configs` VALUES ('32', '11', '玛莎拉蒂', '', '10000', '0', '0', 'Maserati', '0', '0', '0', '90', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('33', '11', '捷豹', '', '10000', '0', '0', 'Jaguar', '0', '0', '0', '80', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('34', '8', '奔驰S', '', '4000', '0', '0', 'Benz_S', '0', '0', '0', '120', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('35', '10', '呆萌木马', '可爱值奔涌的平台最萌座驾。', '10000', '0', '0', 'Trojan', '0', '-170', '191', '180', '-170', '191');
INSERT INTO `pre_car_configs` VALUES ('36', '10', '魔法飞毯', '阿拉丁会见公主的魔法道具。', '10000', '0', '0', 'Magic_carpet', '0', '-176', '203', '60', '-176', '203');
INSERT INTO `pre_car_configs` VALUES ('37', '10', '南瓜车', '让灰姑娘变公主的魔法坐骑。', '10000', '0', '0', 'Pumpkin_car', '0', '-230', '200', '70', '-230', '200');
INSERT INTO `pre_car_configs` VALUES ('38', '10', '保时捷', '经典顶级跑车，闻名世界车坛。', '10500', '0', '0', 'Porsche', '0', '0', '0', '250', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('39', '10', '劳斯莱斯银魅', '银魅，劳斯莱斯的杀手锏产品。', '15000', '0', '0', 'Silver_Ghost', '0', '0', '0', '350', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('40', '10', '黄金战车', '真土豪的不二选择，高富帅的身份标识。', '20000', '0', '0', 'Golden_chariot', '0', '0', '0', '360', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('41', '10', '独角兽', '独角兽', '0', '0', '0', 'Unicorn', '0', '-126', '310', '370', '-126', '310');
INSERT INTO `pre_car_configs` VALUES ('42', '10', '比翼鸟', '传说中的鸟类，此鸟仅一翼一目，雌雄须并翼飞行，象征坚贞的爱情。', '77777', '0', '0', 'Lovebirds', '0', '0', '0', '290', '0', '0');
INSERT INTO `pre_car_configs` VALUES ('43', '10', '月亮马车', '形如皎月的马车，车上坐的是否是那位月宫仙子呢？', '0', '0', '0', 'ylmc', '1', '-280', '110', '380', '-470', '296');
INSERT INTO `pre_car_configs` VALUES ('44', '10', '黑熊', '会骑单车的黑熊，还真是少见呢', '0', '0', '0', 'black_bear', '0', '-140', '120', '230', '-140', '120');
INSERT INTO `pre_car_configs` VALUES ('45', '10', '星辰骑士', '诞生于星辰深处的座驾，神秘、高贵~', '0', '0', '0', 'xcqs', '0', '-297', '196', '390', '-297', '196');
INSERT INTO `pre_car_configs` VALUES ('46', '12', '帕加尼', '', '0', '0', '0', 'pagani', '0', '0', '0', '1', '0', '0');
