/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.71
Source Server Version : 50539
Source Host           : 192.168.1.71:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-10-29 16:35:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_guard_rights`
-- ----------------------------
DROP TABLE IF EXISTS `pre_guard_rights`;
CREATE TABLE `pre_guard_rights` (
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
-- Records of pre_guard_rights
-- ----------------------------
INSERT INTO `pre_guard_rights` VALUES ('1', '专属标识', '专属尊贵标识', '/guard/wodedaoju_shouhubiaozhi@3x.png', '0', '1', 0);
INSERT INTO `pre_guard_rights` VALUES ('2', '专属坐席', '进入房间自动落座专属坐席', '/guard/wodedaoju_zhuanshuzuoxi@3x.png', '0', '1', 0);
INSERT INTO `pre_guard_rights` VALUES ('3', '专属座驾', '铂金：兰博基尼\n黄金：帕加尼\n白银：奔驰S', '/guard/shangcheng_zuojia@3x.png', '0', '1', 0);
INSERT INTO `pre_guard_rights` VALUES ('4', '房间特权', '铂金：除房主外，防御被踢和被禁言\n黄金：除房主外，防御被踢', '/guard/wodedaoju_fangjiantequan@3x.png', '0', '1', 0);
INSERT INTO `pre_guard_rights` VALUES ('5', '进场提示', '进入房间醒目提示', '/guard/wodedaoju_jinchangtishi@3x.png', '0', '1', 0);
INSERT INTO `pre_guard_rights` VALUES ('6', '聊天提示', '专属动态表情，专属文字颜色', '/guard/wodedaoju_liaotiantishi@3x.png', '0', '1', 0);
INSERT INTO `pre_guard_rights` VALUES ('7', '专属礼物', '拥有专属守护礼物赠送权', '/guard/wodedaoju_zhuanshuliwu@3x.png', '0', '1', 0);