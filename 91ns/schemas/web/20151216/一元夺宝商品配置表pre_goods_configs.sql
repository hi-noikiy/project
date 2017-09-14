/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.71
Source Server Version : 50539
Source Host           : 192.168.1.71:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-12-15 15:06:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_goods_configs`
-- ----------------------------
DROP TABLE IF EXISTS `pre_goods_configs`;
CREATE TABLE `pre_goods_configs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL COMMENT '商品名称',
  `price` decimal(10,0) DEFAULT '0' COMMENT '商品价格',
  `description` varchar(128) DEFAULT NULL COMMENT '商品描述',
  `totalNums` int(11) DEFAULT '0' COMMENT '所需总人次',
  `perPoint` int(11) DEFAULT '0' COMMENT '每人次所需积分',
  `perCash` int(11) DEFAULT '0' COMMENT '每人次所需聊币',
  `type` tinyint(3) DEFAULT '1' COMMENT '商品类型',
  `isShow` tinyint(3) DEFAULT '0' COMMENT '商品状态',
  `createTime` int(11) DEFAULT '0' COMMENT '时间',
  `img` varchar(64) DEFAULT NULL COMMENT '图片',
  `orderType` int(11) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='一元夺宝商品配置表';

-- ----------------------------
-- Records of pre_goods_configs
-- ----------------------------
INSERT INTO `pre_goods_configs` VALUES ('1', '杜蕾斯', '100', '随性而动，大展雄风', '1000', '50', '10', '1', '0', '1450195200', 'durex1', '0');
INSERT INTO `pre_goods_configs` VALUES ('2', '纯金车票', '500', '纯金打造，永久典藏', '1000', '250', '50', '1', '0', '1450195200', 'cjcp1', '0');
INSERT INTO `pre_goods_configs` VALUES ('3', '八骏金条', '1000', '八骏金典，尽显奢华', '1000', '500', '100', '1', '0', '1450195200', 'bjjt1', '0');
INSERT INTO `pre_goods_configs` VALUES ('4', '小米头戴式耳机', '599', null, '599', '500', '100', '1', '0', '1450195200', 'MI_tdsej1', '0');
INSERT INTO `pre_goods_configs` VALUES ('5', '小钢炮蓝牙音箱', '128', null, '128', '500', '100', '1', '0', '1450195200', 'MI_xgplyyx1', '0');
INSERT INTO `pre_goods_configs` VALUES ('6', '小米蓝牙耳机', '99', null, '99', '500', '100', '1', '0', '1450195200', 'MI_lyej1', '0');
INSERT INTO `pre_goods_configs` VALUES ('7', '小米方盒子蓝牙音箱', '128', null, '128', '500', '100', '1', '0', '1450195200', 'MI_fhzlyyx1', '0');
INSERT INTO `pre_goods_configs` VALUES ('8', '小米运动手环', '88', null, '88', '500', '100', '1', '0', '1450195200', 'MI_ydsh1', '0');
INSERT INTO `pre_goods_configs` VALUES ('9', '小米移动电源（16000mA）', '148', null, '148', '500', '100', '1', '0', '1450195200', 'MI_yddy1', '0');
INSERT INTO `pre_goods_configs` VALUES ('10', '华为 Mate7 标配版', '2899', '16G', '2899', '500', '100', '1', '0', '1450195200', 'HUAWEI_Mate7_bp1', '0');
INSERT INTO `pre_goods_configs` VALUES ('11', 'Huawei P8标配版', '2899', '双4G版移动电信 16G', '2899', '500', '100', '1', '0', '1450195200', 'HUAWEI_P8_bp1', '0');
INSERT INTO `pre_goods_configs` VALUES ('12', '华为手环 B2 TPU腕带', '1299', '运动版', '1299', '500', '100', '1', '0', '1450195200', 'HUAWEI_sh1', '0');
INSERT INTO `pre_goods_configs` VALUES ('13', 'iPhone 6s Plus 64GB 4G手机', '7180', '颜色随机', '7180', '500', '100', '2', '0', '1450195200', 'iPhone_6s_Plus_64G1', '10');
INSERT INTO `pre_goods_configs` VALUES ('14', 'iPhone 6s 64GB 4G手机', '6380', '颜色随机', '6380', '500', '100', '1', '0', '1450195200', 'iPhone_6s_64G1', '0');
INSERT INTO `pre_goods_configs` VALUES ('15', 'iPhone 6 Plus 64GB 4G手机', '6688', '颜色随机', '6688', '500', '100', '1', '0', '1450195200', 'iPhone_6_Plus_64G1', '0');
INSERT INTO `pre_goods_configs` VALUES ('16', 'iPhone 6 64GB 4G手机', '5688', '颜色随机', '5688', '500', '100', '1', '0', '1450195200', 'iPhone_6_64G1', '0');
INSERT INTO `pre_goods_configs` VALUES ('17', 'Apple MacBook Air', '6888', '11英寸 128G', '6888', '500', '100', '1', '0', '1450195200', 'Apple_MacBook_Air1', '0');
INSERT INTO `pre_goods_configs` VALUES ('18', 'Apple Watch Sport 智能手表', '3588', '42毫米银色铝金属表壳搭配白色运动型表带', '3588', '500', '100', '1', '0', '1450195200', 'Apple_Watch_Sport1', '0');
INSERT INTO `pre_goods_configs` VALUES ('19', 'Apple_iPad Air 2 16G', '3588', 'WLAN 颜色随机', '3588', '500', '100', '1', '0', '1450195200', 'Apple_iPad_Air_2_16G1', '0');
INSERT INTO `pre_goods_configs` VALUES ('20', 'Apple_iPad Air 2 64G', '4588', 'WLAN 颜色随机', '4588', '500', '100', '2', '0', '1450195200', 'Apple_iPad_Air_2_64G1', '9');
INSERT INTO `pre_goods_configs` VALUES ('21', 'OPPO R7 Plus', '3399', '全网通4G手机 双卡双待 32G 颜色随机', '3399', '500', '100', '1', '0', '1450195200', 'OPPO_R7_Plus1', '0');
INSERT INTO `pre_goods_configs` VALUES ('22', 'SAMSUNG Galaxy S6 Edge+', '5688', '32G 全网通4G手机', '5688', '500', '100', '1', '0', '1450195200', 'SAMSUNG_Galaxy_S6_Edge+1', '0');
INSERT INTO `pre_goods_configs` VALUES ('23', '50元话费', '55', null, '55', '500', '100', '1', '0', '1450195200', '50hf1', '0');
INSERT INTO `pre_goods_configs` VALUES ('24', '100元话费', '108', null, '108', '500', '100', '1', '0', '1450195200', '100hf1', '0');
INSERT INTO `pre_goods_configs` VALUES ('25', '三只松鼠 手剥巴旦木 手剥薄壳杏仁', '35', '235g', '35', '500', '100', '1', '0', '1450195200', 'Szss_sbbdm1', '0');
INSERT INTO `pre_goods_configs` VALUES ('26', '三只松鼠 夏威夷果', '35', '澳洲坚果特产 奶油味', '35', '500', '100', '1', '0', '1450195200', 'Szss_xwyg1', '0');
INSERT INTO `pre_goods_configs` VALUES ('27', '不得不爱6plus高清摄像头', '838', '自动变焦720P超显瘦视频主播美颜摄像头', '838', '500', '100', '1', '0', '1450195200', 'Bdba_6plus1', '0');
