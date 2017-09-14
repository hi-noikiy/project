/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-10-29 11:35:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_red_packet_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_red_packet_log`;
CREATE TABLE `pre_red_packet_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `redPacketId` int(11) NOT NULL DEFAULT '0' COMMENT '红包id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `money` int(11) NOT NULL DEFAULT '0' COMMENT '金额',
  `getTime` int(11) DEFAULT '0' COMMENT '领取时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:可领取 0：不可领取',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户领取红包记录表';

-- ----------------------------
-- Records of pre_red_packet_log
-- ----------------------------
