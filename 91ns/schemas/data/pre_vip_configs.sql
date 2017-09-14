/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.70
Source Server Version : 50539
Source Host           : 192.168.1.70:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2015-04-01 21:12:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_vip_configs
-- ----------------------------
DROP TABLE IF EXISTS `pre_vip_configs`;
CREATE TABLE `pre_vip_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` tinyint(3) DEFAULT NULL,
  `lower` bigint(32) DEFAULT NULL COMMENT '当前vip等级最低经验值',
  `higher` bigint(32) DEFAULT NULL COMMENT '当前vip等级最高经验值',
  `description` text,
  `carId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='pre_vip_configs';

-- ----------------------------
-- Records of pre_vip_configs
-- ----------------------------
INSERT INTO `pre_vip_configs` VALUES ('11', '1', '300', '2999', '• 昵称前有标志。<br/>• 专属会员表情。<br/>\n• 富豪等级直升至1富（若已经达到1富，则无效果）。<br/>• 主播等级直升至棒棒糖1（若已经达到棒棒糖1，则无效果）。<br/>• 除房主以外，防止被踢！除房主以外，防止被禁言。', '0');
INSERT INTO `pre_vip_configs` VALUES ('12', '2', '3000', '9999', '• 昵称前有标志。<br/> \n            • 专属会员表情。<br/>\n            • 您只需在线5分钟可自动获取1点魅力值，每日领取上限为30点。<br/>\n            • 您每天可免费送出10朵金玫瑰，价值10聊币，非卖品，VIP专属礼物。<br/>\n            • 除房主以外，防止被踢！除房主以外，防止被禁言。', '0');
INSERT INTO `pre_vip_configs` VALUES ('13', '3', '10000', '19999', '• 昵称前有标志。<br/>\n            • 专属会员表情。<br/>\n            • 您只需在线5分钟可自动获取1点魅力值，每日领取上限为30点。<br/>\n            • 您每天可免费送出10朵金玫瑰，价值10聊币，非卖品，VIP专属礼物。<br/>\n            • 除房主以外，防止被踢！除房主以外，防止被禁言。<br/>\n            • 赠送经济座驾\"标志307\"一台/1个月。', '22');
INSERT INTO `pre_vip_configs` VALUES ('14', '4', '20000', '39999', '• 昵称前有标志。<br/>\n            • 专属会员表情。<br/>\n            • 您只需在线5分钟可自动获取1点魅力值，每日领取上限为30点。<br/>\n            • 您每天可免费送出10朵金玫瑰，价值10聊币，非卖品，VIP专属礼物。<br/>\n            • 除房主以外，防止被踢！除房主以外，防止被禁言。<br/>\n            • 赠送经济座驾\"马自达\"一台/1个月。<br/>\n           ', '20');
INSERT INTO `pre_vip_configs` VALUES ('15', '5', '40000', '79999', '• 昵称前有标志。<br/>\n            • 专属会员表情。<br/>\n            • 您只需在线5分钟可自动获取1点魅力值，每日领取上限为30点。<br/>\n            • 您每天可免费送出10朵金玫瑰，价值10聊币，非卖品，VIP专属礼物。<br/>\n            • 除房主以外，防止被踢！除房主以外，防止被禁言。<br/>\n            • 赠送经济座驾\"凯美瑞\"一台/1个月。<br/>\n            ', '19');
INSERT INTO `pre_vip_configs` VALUES ('16', '6', '80000', '159999', '• 昵称前有标志。<br/>\n           •  专属会员表情。<br/>\n            • 您只需在线5分钟可自动获取1点魅力值，每日领取上限为30点。<br/>\n            • 您每天可免费送出10朵金玫瑰，价值10聊币，非卖品，VIP专属礼物。<br/>\n          •  除房主以外，防止被踢！除房主以外，防止被禁言。<br/>\n            • 赠送豪华座驾\"雷克萨斯ES\"一台/1个月。<br/>\n          ', '18');
INSERT INTO `pre_vip_configs` VALUES ('17', '7', '160000', '319999', '• 昵称前有标志。<br/>\n            •  专属会员表情。<br/>\n             •  您只需在线5分钟可自动获取1点魅力值，每日领取上限为30点。<br/>\n             •  您每天可免费送出10朵金玫瑰，价值10聊币，非卖品，VIP专属礼物。<br/>\n            •  除主播等级6口红（包括）以上房主以外，防止被禁言。免疫所有踢人。<br/>\n             •  赠送豪华座驾\"路虎揽胜\"一台/1个月。<br/>\n           ', '5');
INSERT INTO `pre_vip_configs` VALUES ('18', '8', '320000', '9999999', '• 昵称前有标志。<br/>\n             •  专属会员表情。<br/>\n             •  您只需在线5分钟可自动获取1点魅力值，每日领取上限为30点。<br/>\n             •  您每天可免费送出10朵金玫瑰，价值10聊币，非卖品，VIP专属礼物。<br/>\n             •  除主播等级6口红（包括）以上房主以外，防止被禁言。免疫所有踢人。<br/>\n             •  赠送奢华座驾\"法拉利\"一台/1个月。<br/>\n           ', '13');
