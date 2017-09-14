/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-06-02 03:02:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_sign_configs`
-- ----------------------------
DROP TABLE IF EXISTS `pre_sign_configs`;
CREATE TABLE `pre_sign_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '1' COMMENT '签到类型：1：累计签到 2：连续签到',
  `desc` varchar(100) DEFAULT '' COMMENT '描述',
  `daysNum` int(11) DEFAULT '0' COMMENT '签到天数',
  `itemType` smallint(4) DEFAULT '0' COMMENT '物品类型  1：物品 2座驾 3礼物',
  `itemId` int(11) DEFAULT '0' COMMENT '物品id',
  `itemNum` int(11) DEFAULT '1' COMMENT '奖品数量',
  `validity` int(11) DEFAULT '0' COMMENT '有效期：单位秒。0为永久',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='签到配置表 ';

-- ----------------------------
-- Records of pre_sign_configs
-- ----------------------------
INSERT INTO `pre_sign_configs` VALUES ('1', '1', '使用可发送当前直播间广播', '7', '1', '1', '1', '0');
INSERT INTO `pre_sign_configs` VALUES ('2', '1', '使用可发送所有直播间广播', '17', '1', '2', '1', '0');
INSERT INTO `pre_sign_configs` VALUES ('3', '1', '签到专属座驾', '27', '2', '20', '1', '2332800');
INSERT INTO `pre_sign_configs` VALUES ('4', '2', '活动/任务专属礼物', '2', '3', '9', '5', '0');
INSERT INTO `pre_sign_configs` VALUES ('5', '2', '活动/任务专属礼物', '3', '3', '10', '10', '0');
INSERT INTO `pre_sign_configs` VALUES ('6', '2', '活动/任务专属礼物', '4', '3', '13', '20', '0');
INSERT INTO `pre_sign_configs` VALUES ('7', '2', '活动/任务专属礼物', '5', '3', '15', '10', '0');
INSERT INTO `pre_sign_configs` VALUES ('8', '2', '活动/任务专属礼物', '6', '3', '17', '1', '0');
INSERT INTO `pre_sign_configs` VALUES ('9', '2', '签到专属徽章，连续获得可升级', '28', '1', '3', '1', '0');
