/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-05-19 16:41:02
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
  `package` varchar(255) DEFAULT '' COMMENT '礼包配置：【用户类型，礼包id】',
  `validity` int(11) DEFAULT '0' COMMENT '有效期：单位秒。0为永久',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='签到配置表 ';

-- ----------------------------
-- Records of pre_sign_configs
-- ----------------------------
INSERT INTO `pre_sign_configs` VALUES ('1', '1', '使用可发送当前直播间广播', '7', '[{\"type\":0,\"ids\":\"1\"}]', '0');
INSERT INTO `pre_sign_configs` VALUES ('2', '1', '使用可发送所有直播间广播', '17', '[{\"type\":0,\"ids\":\"2\"}]', '0');
INSERT INTO `pre_sign_configs` VALUES ('3', '1', '签到专属座驾', '27', '[{\"type\":0,\"ids\":\"3\"}]', '2332800');
INSERT INTO `pre_sign_configs` VALUES ('4', '2', '活动/任务专属礼物', '2', '[{\"type\":0,\"ids\":\"4\"}]', '0');
INSERT INTO `pre_sign_configs` VALUES ('5', '2', '活动/任务专属礼物', '3', '[{\"type\":0,\"ids\":\"5\"}]', '0');
INSERT INTO `pre_sign_configs` VALUES ('6', '2', '活动/任务专属礼物', '4', '[{\"type\":0,\"ids\":\"6\"}]', '0');
INSERT INTO `pre_sign_configs` VALUES ('7', '2', '活动/任务专属礼物', '5', '[{\"type\":0,\"ids\":\"7\"}]', '0');
INSERT INTO `pre_sign_configs` VALUES ('8', '2', '活动/任务专属礼物', '6', '[{\"type\":0,\"ids\":\"8\"}]', '0');
INSERT INTO `pre_sign_configs` VALUES ('9', '2', '签到专属徽章，连续获得可升级', '28', '[{\"type\":0,\"ids\":\"9\"}]', '0');
