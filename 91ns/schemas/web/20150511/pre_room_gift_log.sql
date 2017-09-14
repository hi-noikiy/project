/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-05-11 20:30:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_room_gift_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_room_gift_log`;
CREATE TABLE `pre_room_gift_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roomId` int(11) DEFAULT '0' COMMENT '房间id',
  `type` smallint(4) DEFAULT '0' COMMENT '类型：1礼物、2抢座、3魅力星',
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `giftId` int(11) DEFAULT '0' COMMENT '礼物id',
  `configName` varchar(30) DEFAULT '',
  `giftNum` int(11) DEFAULT '0' COMMENT '礼物数量',
  `giftName` varchar(30) DEFAULT NULL COMMENT '礼物名称',
  `price` int(11) DEFAULT '0' COMMENT '价钱',
  `priceType` tinyint(1) DEFAULT '1' COMMENT '价钱类型 1:聊币 2聊豆',
  `createTime` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='直播间送礼日志-用于进入直播间时显示送礼记录';

-- ----------------------------
-- Records of pre_room_gift_log
-- ----------------------------
 