/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-10-29 11:35:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_red_packet`
-- ----------------------------
DROP TABLE IF EXISTS `pre_red_packet`;
CREATE TABLE `pre_red_packet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roomId` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `createTime` int(11) NOT NULL DEFAULT '0',
  `initTime` int(11) NOT NULL DEFAULT '0' COMMENT '开启时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '红包类型 1：人气红包，2：平均红包',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '红包个数',
  `limit` tinyint(1) NOT NULL DEFAULT '1' COMMENT '限制条件',
  `sumMoney` int(11) NOT NULL DEFAULT '0' COMMENT '红包总金额',
  `returnMoney` int(11) DEFAULT '0' COMMENT '退还金额',
  `returnTime` int(11) DEFAULT '0' COMMENT '退还时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '红包状态：0:未开启 ，1：开启，2：已领取完',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='直播间用户开启红包记录';

-- ----------------------------
-- Records of pre_red_packet
-- ----------------------------
