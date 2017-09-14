/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.71
Source Server Version : 50539
Source Host           : 192.168.1.71:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-10-29 16:28:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_vip_rights`
-- ----------------------------
DROP TABLE IF EXISTS `pre_vip_rights`;
CREATE TABLE `pre_vip_rights` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL COMMENT '名称',
  `desc` varchar(256) DEFAULT NULL COMMENT '描述',
  `img` varchar(128) DEFAULT NULL COMMENT '图片地址',
  `orderType` int(11) DEFAULT '0' COMMENT '排序',
  `type` tinyint(3) DEFAULT '0' COMMENT '类型',
  `lastTime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_vip_rights
-- ----------------------------
INSERT INTO `pre_vip_rights` VALUES ('1', '专属标识', '昵称前有普通VIP标识', '/vip/shangcheng_biaozhi@3x.png', '0', '1', 0);
INSERT INTO `pre_vip_rights` VALUES ('2', '专属座驾', '专属座驾：特斯拉', '/vip/shangcheng_zuojia@3x.png', '0', '1', 0);
INSERT INTO `pre_vip_rights` VALUES ('3', '领取专属礼物', '每日可免费领取“金玫瑰”10个', '/vip/shangcheng_liwu@3x.png', '0', '1', 0);
INSERT INTO `pre_vip_rights` VALUES ('4', '魅力升级', '只需在线5分钟可自动获取1点魅力星\n每日领取上限30点', '/vip/shangcheng_shengji@3x.png', '0', '1', 0);
INSERT INTO `pre_vip_rights` VALUES ('5', '专属表情', '可以使用VIP专属表情', '/vip/shangcheng_biaoqing@3x.png', '0', '1', 0);
INSERT INTO `pre_vip_rights` VALUES ('6', '专属标识', '昵称前有至尊VIP标识', '/vip/shangcheng_biaozhi@3x.png', '0', '2', 0);
INSERT INTO `pre_vip_rights` VALUES ('7', '专属座驾', '专属座驾：法拉利', '/vip/shangcheng_zuojia@3x.png', '0', '2', 0);
INSERT INTO `pre_vip_rights` VALUES ('8', '领取专属礼物', '每日可免费领取“金玫瑰”20个', '/vip/shangcheng_liwu@3x.png', '0', '2', 0);
INSERT INTO `pre_vip_rights` VALUES ('9', '防御特权', '除房主外，防御被踢和被禁言', '/vip/shangcheng_gaojifangyu@3x.png', '0', '2', 0);
INSERT INTO `pre_vip_rights` VALUES ('10', '魅力升级', '只需在线5分钟可自动获取1点魅力星\n每日领取上限50点', '/vip/shangcheng_shengji@3x.png', '0', '2', 0);
INSERT INTO `pre_vip_rights` VALUES ('11', '专属表情', '可以使用VIP专属表情', '/vip/shangcheng_biaoqing@3x.png', '0', '2', 0);
INSERT INTO `pre_vip_rights` VALUES ('12', '专属标识', '昵称前有专属VIP标识', '/vip/shangcheng_biaozhi@3x.png', '0', '0', '0');
INSERT INTO `pre_vip_rights` VALUES ('13', '专属座驾', '至尊VIP：法拉利\n普通VIP：特斯拉', '/vip/shangcheng_zuojia@3x.png', '0', '0', '0');
INSERT INTO `pre_vip_rights` VALUES ('14', '领取专属礼物', '每日可免费领取“金玫瑰”\n至尊VIP：20个\n普通VIP：10个', '/vip/shangcheng_liwu@3x.png', '0', '0', '0');
INSERT INTO `pre_vip_rights` VALUES ('15', '防御特权', '至尊VIP：除房主外，防御被踢和被禁言', '/vip/shangcheng_gaojifangyu@3x.png', '0', '0', '0');
INSERT INTO `pre_vip_rights` VALUES ('16', '魅力升级', '只需在线5分钟可自动获取1点魅力星\n至尊VIP每日领取上限50点\n普通VIP每日领取上限30点', '/vip/shangcheng_shengji@3x.png', '0', '0', '0');
INSERT INTO `pre_vip_rights` VALUES ('17', '专属表情', '可以使用VIP专属表情', '/vip/shangcheng_biaoqing@3x.png', '0', '0', '0');
