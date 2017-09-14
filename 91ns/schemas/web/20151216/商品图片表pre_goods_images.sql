/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.71
Source Server Version : 50539
Source Host           : 192.168.1.71:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-12-15 15:06:17
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_goods_images`
-- ----------------------------
DROP TABLE IF EXISTS `pre_goods_images`;
CREATE TABLE `pre_goods_images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goodsId` int(11) DEFAULT '0',
  `imgUrl` varchar(64) DEFAULT NULL COMMENT '图片url',
  `createTime` int(11) DEFAULT '0' COMMENT '添加时间',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态',
  `orderType` int(11) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE,
  KEY `goodsId` (`goodsId`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COMMENT='商品图片';

-- ----------------------------
-- Records of pre_goods_images
-- ----------------------------
INSERT INTO `pre_goods_images` VALUES ('1', '4', 'MI_tdsej2', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('2', '4', 'MI_tdsej3', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('3', '4', 'MI_tdsej4', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('4', '6', 'MI_lyej2', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('5', '6', 'MI_lyej3', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('6', '6', 'MI_lyej4', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('7', '7', 'MI_fhzlyyx2', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('8', '7', 'MI_fhzlyyx3', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('9', '7', 'MI_fhzlyyx4', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('10', '12', 'HUAWEI_sh2', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('11', '12', 'HUAWEI_sh3', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('12', '12', 'HUAWEI_sh4', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('13', '11', 'HUAWEI_P8_bp2', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('14', '11', 'HUAWEI_P8_bp3', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('15', '11', 'HUAWEI_P8_bp4', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('16', '10', 'HUAWEI_Mate7_bp2', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('17', '10', 'HUAWEI_Mate7_bp3', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('18', '10', 'HUAWEI_Mate7_bp4', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('19', '27', 'Bdba_6plus2', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('20', '22', 'SAMSUNG_Galaxy_S6_Edge+2', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('21', '21', 'OPPO_R7_Plus2', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('22', '21', 'OPPO_R7_Plus3', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('23', '20', 'Apple_iPad_Air_2_64G2', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('24', '20', 'Apple_iPad_Air_2_64G3', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('25', '20', 'Apple_iPad_Air_2_64G4', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('26', '20', 'Apple_iPad_Air_2_64G5', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('27', '19', 'Apple_iPad_Air_2_16G2', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('28', '19', 'Apple_iPad_Air_2_16G3', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('29', '19', 'Apple_iPad_Air_2_16G4', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('30', '19', 'Apple_iPad_Air_2_16G5', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('31', '17', 'Apple_MacBook_Air2', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('32', '17', 'Apple_MacBook_Air3', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('33', '17', 'Apple_MacBook_Air4', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('34', '14', 'iPhone_6s_64G2', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('35', '14', 'iPhone_6s_64G3', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('36', '14', 'iPhone_6s_64G4', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('37', '14', 'iPhone_6s_64G5', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('38', '13', 'iPhone_6s_Plus_64G2', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('39', '13', 'iPhone_6s_Plus_64G3', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('40', '13', 'iPhone_6s_Plus_64G4', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('41', '13', 'iPhone_6s_Plus_64G5', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('42', '16', 'iPhone_6_64G2', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('43', '16', 'iPhone_6_64G3', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('44', '16', 'iPhone_6_64G4', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('45', '16', 'iPhone_6_64G5', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('46', '15', 'iPhone_6_Plus_64G2', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('47', '15', 'iPhone_6_Plus_64G3', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('48', '15', 'iPhone_6_Plus_64G4', '1450195200', '0', '0');
INSERT INTO `pre_goods_images` VALUES ('49', '15', 'iPhone_6_Plus_64G5', '1450195200', '0', '0');
