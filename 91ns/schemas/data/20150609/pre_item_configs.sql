/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-06-09 18:18:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_item_configs`
-- ----------------------------
DROP TABLE IF EXISTS `pre_item_configs`;
CREATE TABLE `pre_item_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(4) DEFAULT '1' COMMENT '类型：喇叭、徽章',
  `name` varchar(30) DEFAULT '' COMMENT '物品名称',
  `description` varchar(100) DEFAULT '' COMMENT '描述',
  `cash` float(32,3) DEFAULT '0.000' COMMENT '聊币',
  `configName` varchar(20) DEFAULT '' COMMENT '配置名称，索引图片别名用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='物品配置表';

-- ----------------------------
-- Records of pre_item_configs
-- ----------------------------
INSERT INTO `pre_item_configs` VALUES ('1', '1', '银喇叭卡', '可以向正在观看的直播房间发送一条飞屏信息', '1000.000', 'ylbk');
INSERT INTO `pre_item_configs` VALUES ('2', '1', '金喇叭卡', '可以向其他直播房间发送一条飞屏信息', '2000.000', 'jlbk');
INSERT INTO `pre_item_configs` VALUES ('3', '2', '签到专属徽章', '在聊天时会在昵称前面显示，每次重复获得该徽章时徽章会升级。', '0.000', 'qdhz');
INSERT INTO `pre_item_configs` VALUES ('4', '2', '联通徽章', '联通徽章', '0.000', 'lthz');
INSERT INTO `pre_item_configs` VALUES ('5', '2', '棋牌徽章', '棋牌徽章', '0.000', 'qphz');
