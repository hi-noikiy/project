/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : 91ns

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-03-18 18:28:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_user_profiles`
-- ----------------------------
DROP TABLE IF EXISTS `pre_user_profiles`;
CREATE TABLE `pre_user_profiles` (
  `uid` int(11) NOT NULL,
  `coin` bigint(32) DEFAULT NULL COMMENT '聊豆',
  `cash` bigint(32) DEFAULT NULL COMMENT '聊币',
  `money` bigint(32) DEFAULT NULL COMMENT '收益',
  `exp1` bigint(32) DEFAULT NULL COMMENT 'VIP经验值',
  `exp2` bigint(32) DEFAULT NULL COMMENT '主播经验值',
  `exp3` bigint(32) DEFAULT NULL COMMENT '富豪经验值',
  `exp4` bigint(32) DEFAULT NULL COMMENT '粉丝经验值',
  `exp5` bigint(32) DEFAULT NULL COMMENT '魅力值',
  `level1` tinyint(3) DEFAULT NULL COMMENT 'VIP等级',
  `level2` tinyint(3) DEFAULT NULL COMMENT '主播等级',
  `level3` tinyint(3) DEFAULT NULL COMMENT '富豪等级',
  `level4` tinyint(3) DEFAULT NULL COMMENT '粉丝等级',
  `level5` tinyint(3) DEFAULT NULL COMMENT '魅力等级',
  `vipExpireTime` int(11) DEFAULT NULL,
  `questionId` smallint(4) DEFAULT '0' COMMENT '安全问题',
  `answer` varchar(30) DEFAULT '' COMMENT '问题答案',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='pre_user_profiles';

-- ----------------------------
-- Records of pre_user_profiles
-- ----------------------------
