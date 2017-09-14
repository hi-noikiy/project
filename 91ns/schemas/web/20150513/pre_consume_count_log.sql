/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-05-13 17:49:30
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_consume_count_log`
-- ----------------------------
DROP TABLE IF EXISTS `pre_consume_count_log`;
CREATE TABLE `pre_consume_count_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `sendCoin` int(11) DEFAULT '0' COMMENT '送出聊豆数量',
  `sendStar` int(11) DEFAULT '0' COMMENT '送出魅力星数量',
  `receiveCoin` int(11) DEFAULT '0' COMMENT '收到的聊豆数量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消费聊豆、送魅力星数量记录表，用于判断增长用户富豪经验或主播经验等';

 