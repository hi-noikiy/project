/*
Navicat MySQL Data Transfer

Source Server         : 越南统计
Source Server Version : 50636
Source Host           : localhost:3306
Source Database       : mydb

Target Server Type    : MYSQL
Target Server Version : 50636
File Encoding         : 65001

Date: 2017-07-03 16:02:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for auth_config
-- ----------------------------
DROP TABLE IF EXISTS `auth_config`;
CREATE TABLE `auth_config` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `game_id` int(10) NOT NULL,
  `appid` varchar(20) NOT NULL,
  `secret` varchar(32) NOT NULL,
  `access_token` varchar(50) NOT NULL,
  `expire` int(10) NOT NULL,
  `max_request` int(10) NOT NULL DEFAULT '20000',
  `total_request_today` int(10) NOT NULL DEFAULT '0',
  `created_at` int(10) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_access_token` (`access_token`),
  KEY `idx_appid_secret` (`appid`,`secret`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of auth_config
-- ----------------------------
INSERT INTO `auth_config` VALUES ('4', '3', '10002', 'ce23a805d28aaf5e576d4cebe1fbf8e1', 'dad81be0e5e9116b7f274b7ea053e051', '1475129160', '200000', '191', '0', '口袋怪兽');

-- ----------------------------
-- Table structure for game_reserveusers_daily
-- ----------------------------
DROP TABLE IF EXISTS `game_reserveusers_daily`;
CREATE TABLE `game_reserveusers_daily` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sday` int(11) unsigned NOT NULL,
  `serverid` int(10) unsigned NOT NULL DEFAULT '0',
  `appid` char(20) NOT NULL,
  `channel` int(10) NOT NULL,
  `usercount` int(11) NOT NULL DEFAULT '0',
  `newlogin` int(11) NOT NULL DEFAULT '0',
  `dau` int(11) unsigned NOT NULL DEFAULT '0',
  `wau` int(11) unsigned NOT NULL DEFAULT '0',
  `mau` int(11) unsigned NOT NULL DEFAULT '0',
  `day1` int(11) unsigned NOT NULL DEFAULT '0',
  `day2` int(11) unsigned NOT NULL DEFAULT '0',
  `day3` int(11) unsigned NOT NULL DEFAULT '0',
  `day4` int(11) unsigned NOT NULL DEFAULT '0',
  `day5` int(11) unsigned NOT NULL DEFAULT '0',
  `day6` int(11) unsigned NOT NULL DEFAULT '0',
  `day7` int(11) unsigned NOT NULL DEFAULT '0',
  `day8` int(11) unsigned NOT NULL DEFAULT '0',
  `day14` int(11) NOT NULL DEFAULT '0',
  `day15` int(11) unsigned NOT NULL DEFAULT '0',
  `day30` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sasc` (`sday`,`serverid`,`channel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of game_reserveusers_daily
-- ----------------------------

-- ----------------------------
-- Table structure for groups
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `permissions` varchar(255) NOT NULL DEFAULT '',
  `appid` int(10) NOT NULL DEFAULT '0',
  `channel` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of groups
-- ----------------------------
INSERT INTO `groups` VALUES ('1', 'admin', 'Administrator', '1,2,3,4,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49', '0', '0');
INSERT INTO `groups` VALUES ('2', 'members', 'General User', '1,2,3,4,6,7,8,10,11,14,16,17,18,19,20,21,22,23,24,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49', '10002', '602001');
INSERT INTO `groups` VALUES ('3', 'channel001', 'channel users`1222', '', '0', '0');
INSERT INTO `groups` VALUES ('4', 'xiaomi', '小米渠道的用户', '', '0', '0');
INSERT INTO `groups` VALUES ('5', 'guopan', '渠道用户组', '6,7,8,9,10,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45', '10002', '614001');
INSERT INTO `groups` VALUES ('6', 'yingyongbao', '渠道用户组', '6,13,31,34', '10002', '601001');

-- ----------------------------
-- Table structure for login_attempts
-- ----------------------------
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of login_attempts
-- ----------------------------

-- ----------------------------
-- Table structure for sum_act_by_type
-- ----------------------------
DROP TABLE IF EXISTS `sum_act_by_type`;
CREATE TABLE `sum_act_by_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL COMMENT '按类型统计0统计类型1区服2渠道',
  `typeid` bigint(20) NOT NULL COMMENT '类型编号',
  `account_num` bigint(20) NOT NULL COMMENT '参与玩家数量',
  `consume_money` bigint(20) NOT NULL COMMENT '消耗金钱数量',
  `consume_diamond` bigint(20) NOT NULL COMMENT '消耗钻石数量',
  `consume_tired` bigint(20) NOT NULL COMMENT '消耗体力数量',
  `get_money` bigint(20) NOT NULL COMMENT '获得金钱数量',
  `get_diamond` bigint(20) NOT NULL COMMENT '获得钻石数量',
  `get_tired` bigint(20) NOT NULL COMMENT '获得体力数量',
  `logdate` int(11) NOT NULL COMMENT '记录日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`logdate`,`type`,`typeid`) USING BTREE,
  KEY `idx_logdate` (`logdate`,`type`,`typeid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=81184 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of sum_act_by_type
-- ----------------------------

-- ----------------------------
-- Table structure for sum_active_hour
-- ----------------------------
DROP TABLE IF EXISTS `sum_active_hour`;
CREATE TABLE `sum_active_hour` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL DEFAULT '0',
  `appid` char(20) NOT NULL,
  `cnt` int(10) NOT NULL COMMENT '在线数',
  `hour` int(10) NOT NULL COMMENT '小时',
  `date` int(10) NOT NULL COMMENT '日期',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3406368 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每小时活跃数据';

-- ----------------------------
-- Records of sum_active_hour
-- ----------------------------

-- ----------------------------
-- Table structure for sum_au
-- ----------------------------
DROP TABLE IF EXISTS `sum_au`;
CREATE TABLE `sum_au` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `vip_role` int(10) NOT NULL COMMENT '付费玩家',
  `new_role` int(10) NOT NULL COMMENT '新增玩家',
  `dau` int(10) NOT NULL,
  `wau` int(10) NOT NULL,
  `mau` int(10) NOT NULL,
  `sday` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `dau_ac` int(10) NOT NULL,
  `wau_ac` int(10) NOT NULL,
  `mau_ac` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_server_channel` (`appid`,`sday`,`serverid`,`channel`)
) ENGINE=MyISAM AUTO_INCREMENT=593561 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records of sum_au
-- ----------------------------

-- ----------------------------
-- Table structure for sum_copy_analysis
-- ----------------------------
DROP TABLE IF EXISTS `sum_copy_analysis`;
CREATE TABLE `sum_copy_analysis` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL DEFAULT '0',
  `appid` char(20) NOT NULL,
  `copy_type` int(10) NOT NULL,
  `copy_id` int(10) unsigned NOT NULL,
  `copy_title` int(10) NOT NULL,
  `success_times` smallint(4) unsigned NOT NULL DEFAULT '0',
  `fail_times` smallint(4) unsigned NOT NULL DEFAULT '0',
  `total_times` smallint(4) unsigned NOT NULL DEFAULT '0',
  `sday` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_appid_sday` (`appid`,`sday`)
) ENGINE=InnoDB AUTO_INCREMENT=7551 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of sum_copy_analysis
-- ----------------------------

-- ----------------------------
-- Table structure for sum_device_active_day
-- ----------------------------
DROP TABLE IF EXISTS `sum_device_active_day`;
CREATE TABLE `sum_device_active_day` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `cnt` int(10) NOT NULL COMMENT '激活数',
  `date` int(10) NOT NULL COMMENT '日期',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3165 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每日激活数据';

-- ----------------------------
-- Records of sum_device_active_day
-- ----------------------------

-- ----------------------------
-- Table structure for sum_device_active_hour
-- ----------------------------
DROP TABLE IF EXISTS `sum_device_active_hour`;
CREATE TABLE `sum_device_active_hour` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `cnt` int(10) NOT NULL COMMENT '在线数',
  `hour` int(10) NOT NULL COMMENT '小时',
  `date` int(10) NOT NULL COMMENT '日期',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=169114 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每小时激活数据';

-- ----------------------------
-- Records of sum_device_active_hour
-- ----------------------------

-- ----------------------------
-- Table structure for sum_emoney_analysis
-- ----------------------------
DROP TABLE IF EXISTS `sum_emoney_analysis`;
CREATE TABLE `sum_emoney_analysis` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `item_type` varchar(20) NOT NULL,
  `emoney_get` int(10) NOT NULL,
  `emoney_use` int(10) NOT NULL,
  `emoney_left` int(10) NOT NULL DEFAULT '-1',
  `sday` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_server_channel` (`appid`,`sday`,`item_type`,`serverid`,`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=258 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of sum_emoney_analysis
-- ----------------------------

-- ----------------------------
-- Table structure for sum_income_hour
-- ----------------------------
DROP TABLE IF EXISTS `sum_income_hour`;
CREATE TABLE `sum_income_hour` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `money` int(10) NOT NULL COMMENT '充值金额',
  `hour` int(10) NOT NULL COMMENT '小时',
  `date` int(10) NOT NULL COMMENT '日期',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=516859 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每小时充值数据';

-- ----------------------------
-- Records of sum_income_hour
-- ----------------------------

-- ----------------------------
-- Table structure for sum_item
-- ----------------------------
DROP TABLE IF EXISTS `sum_item`;
CREATE TABLE `sum_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemid` bigint(20) NOT NULL COMMENT '道具编号',
  `type` bigint(20) NOT NULL COMMENT '统计类型',
  `consume_num` bigint(20) NOT NULL COMMENT '消耗数量',
  `get_num` bigint(20) NOT NULL COMMENT '获得数量',
  `logdate` int(11) NOT NULL COMMENT '记录日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`logdate`,`itemid`,`type`) USING BTREE,
  KEY `idx_logdate` (`logdate`,`itemid`,`type`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=381958 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sum_item
-- ----------------------------

-- ----------------------------
-- Table structure for sum_item_by_type
-- ----------------------------
DROP TABLE IF EXISTS `sum_item_by_type`;
CREATE TABLE `sum_item_by_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemid` bigint(20) NOT NULL COMMENT '道具编号',
  `type` bigint(20) NOT NULL COMMENT '统计类型',
  `typeid` bigint(20) NOT NULL COMMENT '类型编号',
  `consume_num` bigint(20) NOT NULL COMMENT '消耗数量',
  `get_num` bigint(20) NOT NULL COMMENT '获得数量',
  `logdate` int(11) NOT NULL COMMENT '记录日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`logdate`,`itemid`,`type`,`typeid`) USING BTREE,
  KEY `idx_logdate` (`logdate`,`itemid`,`type`,`typeid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=38106 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sum_item_by_type
-- ----------------------------

-- ----------------------------
-- Table structure for sum_join
-- ----------------------------
DROP TABLE IF EXISTS `sum_join`;
CREATE TABLE `sum_join` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `act_id` int(11) NOT NULL,
  `param` int(11) NOT NULL DEFAULT '0' COMMENT '0',
  `act_count` int(11) NOT NULL COMMENT '行为次数',
  `act_account` int(11) NOT NULL COMMENT '参与人数',
  `logdate` int(11) NOT NULL COMMENT '记录日期',
  `serverid` int(11) NOT NULL,
  `mysort` int(11) NOT NULL COMMENT '排序',
  `vip_level` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`act_id`,`param`,`logdate`,`serverid`,`vip_level`) USING BTREE,
  KEY `idx_time` (`act_id`,`param`,`logdate`,`serverid`,`vip_level`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=986142 DEFAULT CHARSET=utf8 COMMENT='参与度统计表';

-- ----------------------------
-- Records of sum_join
-- ----------------------------

-- ----------------------------
-- Table structure for sum_login_day
-- ----------------------------
DROP TABLE IF EXISTS `sum_login_day`;
CREATE TABLE `sum_login_day` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `cnt` int(10) NOT NULL COMMENT '用户数',
  `date` int(10) NOT NULL COMMENT '日期',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8241 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每日登陆数据';

-- ----------------------------
-- Records of sum_login_day
-- ----------------------------

-- ----------------------------
-- Table structure for sum_login_hour
-- ----------------------------
DROP TABLE IF EXISTS `sum_login_hour`;
CREATE TABLE `sum_login_hour` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `cnt` int(10) NOT NULL COMMENT '用户数',
  `hour` int(10) NOT NULL COMMENT '小时',
  `date` int(10) NOT NULL COMMENT '日期',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6406243 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每小时登陆数据';

-- ----------------------------
-- Records of sum_login_hour
-- ----------------------------

-- ----------------------------
-- Table structure for sum_newplayer_day
-- ----------------------------
DROP TABLE IF EXISTS `sum_newplayer_day`;
CREATE TABLE `sum_newplayer_day` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `cnt` int(10) NOT NULL COMMENT '用户数',
  `date` int(10) NOT NULL COMMENT '日期',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=647756 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每日角色数据';

-- ----------------------------
-- Records of sum_newplayer_day
-- ----------------------------

-- ----------------------------
-- Table structure for sum_newplayer_hour
-- ----------------------------
DROP TABLE IF EXISTS `sum_newplayer_hour`;
CREATE TABLE `sum_newplayer_hour` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `cnt` int(10) NOT NULL COMMENT '用户数',
  `hour` int(10) NOT NULL COMMENT '小时',
  `date` int(10) NOT NULL COMMENT '日期',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=380635 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每小时新用户数据';

-- ----------------------------
-- Records of sum_newplayer_hour
-- ----------------------------

-- ----------------------------
-- Table structure for sum_newrole_day
-- ----------------------------
DROP TABLE IF EXISTS `sum_newrole_day`;
CREATE TABLE `sum_newrole_day` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `cnt` int(10) NOT NULL COMMENT '用户数',
  `date` int(10) NOT NULL COMMENT '日期',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`serverid`,`channel`,`date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1451247 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每日注册数据';

-- ----------------------------
-- Records of sum_newrole_day
-- ----------------------------

-- ----------------------------
-- Table structure for sum_newrole_day_copy
-- ----------------------------
DROP TABLE IF EXISTS `sum_newrole_day_copy`;
CREATE TABLE `sum_newrole_day_copy` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `cnt` int(10) NOT NULL COMMENT '用户数',
  `date` int(10) NOT NULL COMMENT '日期',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70515 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每日注册数据';

-- ----------------------------
-- Records of sum_newrole_day_copy
-- ----------------------------

-- ----------------------------
-- Table structure for sum_newroles_hour
-- ----------------------------
DROP TABLE IF EXISTS `sum_newroles_hour`;
CREATE TABLE `sum_newroles_hour` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `cnt` int(10) NOT NULL COMMENT '用户数',
  `hour` int(10) NOT NULL COMMENT '小时',
  `date` int(10) NOT NULL COMMENT '日期',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=788675 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每小时新用户数据';

-- ----------------------------
-- Records of sum_newroles_hour
-- ----------------------------

-- ----------------------------
-- Table structure for sum_online_avg_day
-- ----------------------------
DROP TABLE IF EXISTS `sum_online_avg_day`;
CREATE TABLE `sum_online_avg_day` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL DEFAULT '0',
  `appid` char(20) NOT NULL,
  `date` int(10) NOT NULL COMMENT '日期',
  `total_online_time` int(10) NOT NULL DEFAULT '0' COMMENT '当天所有玩家总上线时间',
  `total_online_num` int(10) NOT NULL DEFAULT '0' COMMENT '当天上线玩家总数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=523276 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每日在线数据--计算平均在线用';

-- ----------------------------
-- Records of sum_online_avg_day
-- ----------------------------

-- ----------------------------
-- Table structure for sum_online_day
-- ----------------------------
DROP TABLE IF EXISTS `sum_online_day`;
CREATE TABLE `sum_online_day` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL DEFAULT '0',
  `appid` char(20) NOT NULL,
  `cnt` int(10) NOT NULL COMMENT '在线数',
  `date` int(10) NOT NULL COMMENT '日期',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `total_online_time` int(10) NOT NULL DEFAULT '0' COMMENT '当天所有玩家总上线时间',
  `total_online_num` int(10) NOT NULL DEFAULT '0' COMMENT '当天上线玩家总数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28135 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每日在线数据';

-- ----------------------------
-- Records of sum_online_day
-- ----------------------------

-- ----------------------------
-- Table structure for sum_online_hour
-- ----------------------------
DROP TABLE IF EXISTS `sum_online_hour`;
CREATE TABLE `sum_online_hour` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL DEFAULT '0',
  `appid` char(20) NOT NULL,
  `cnt` int(10) NOT NULL COMMENT '在线数',
  `hour` int(10) NOT NULL COMMENT '小时',
  `date` int(10) NOT NULL COMMENT '日期',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=799058 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每小时在线数据';

-- ----------------------------
-- Records of sum_online_hour
-- ----------------------------

-- ----------------------------
-- Table structure for sum_online_time
-- ----------------------------
DROP TABLE IF EXISTS `sum_online_time`;
CREATE TABLE `sum_online_time` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `vip_online` int(10) NOT NULL,
  `vip_cnt` int(10) NOT NULL,
  `active_online` int(10) NOT NULL,
  `active_cnt` int(10) NOT NULL,
  `new_online` int(10) NOT NULL,
  `new_cnt` int(10) NOT NULL,
  `sday` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_server_channel` (`appid`,`sday`,`serverid`,`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=1147 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of sum_online_time
-- ----------------------------

-- ----------------------------
-- Table structure for sum_player_lost
-- ----------------------------
DROP TABLE IF EXISTS `sum_player_lost`;
CREATE TABLE `sum_player_lost` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `sday` int(10) NOT NULL COMMENT '统计日期',
  `usercount` int(10) NOT NULL COMMENT '注册人数',
  `lev` smallint(4) unsigned NOT NULL DEFAULT '0',
  `lost_1` int(10) NOT NULL COMMENT '1日流失',
  `lost_3` int(10) NOT NULL COMMENT '3日流失',
  `lost_7` int(10) NOT NULL COMMENT '7日流失',
  `lost_14` int(10) NOT NULL COMMENT '14日流失',
  `lost_30` int(10) NOT NULL COMMENT '30日流失',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_server_channel` (`appid`,`sday`,`lev`,`serverid`,`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=17704944 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='流失统计';

-- ----------------------------
-- Records of sum_player_lost
-- ----------------------------

-- ----------------------------
-- Table structure for sum_player_lost_back
-- ----------------------------
DROP TABLE IF EXISTS `sum_player_lost_back`;
CREATE TABLE `sum_player_lost_back` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `sday` int(10) NOT NULL COMMENT '统计日期',
  `lost_8` int(10) NOT NULL,
  `lost_15` int(10) NOT NULL,
  `lost_31` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `lost_2` int(10) NOT NULL,
  `lost_4` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_server_channel` (`appid`,`sday`,`serverid`,`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=19030 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='回流统计';

-- ----------------------------
-- Records of sum_player_lost_back
-- ----------------------------

-- ----------------------------
-- Table structure for sum_player_lost_normal
-- ----------------------------
DROP TABLE IF EXISTS `sum_player_lost_normal`;
CREATE TABLE `sum_player_lost_normal` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `sday` int(10) NOT NULL COMMENT '统计日期',
  `lev` smallint(4) NOT NULL COMMENT '等级',
  `lost_1` int(10) NOT NULL COMMENT '1日流失',
  `lost_3` int(10) NOT NULL COMMENT '3日流失',
  `lost_7` int(10) NOT NULL COMMENT '7日流失',
  `lost_14` int(10) NOT NULL COMMENT '14日流失',
  `lost_30` int(10) NOT NULL COMMENT '30日流失',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_server_channel` (`appid`,`sday`,`serverid`,`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=39104 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='VIP流失统计';

-- ----------------------------
-- Records of sum_player_lost_normal
-- ----------------------------

-- ----------------------------
-- Table structure for sum_player_lost_vip
-- ----------------------------
DROP TABLE IF EXISTS `sum_player_lost_vip`;
CREATE TABLE `sum_player_lost_vip` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `sday` int(10) NOT NULL COMMENT '统计日期',
  `viplev` smallint(4) NOT NULL COMMENT 'VIP等级',
  `lost_1` int(10) NOT NULL COMMENT '1日流失',
  `lost_3` int(10) NOT NULL COMMENT '3日流失',
  `lost_7` int(10) NOT NULL COMMENT '7日流失',
  `lost_14` int(10) NOT NULL COMMENT '14日流失',
  `lost_30` int(10) NOT NULL COMMENT '30日流失',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_server_channel` (`appid`,`sday`,`serverid`,`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=35417 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='VIP流失统计';

-- ----------------------------
-- Records of sum_player_lost_vip
-- ----------------------------

-- ----------------------------
-- Table structure for sum_playeronline
-- ----------------------------
DROP TABLE IF EXISTS `sum_playeronline`;
CREATE TABLE `sum_playeronline` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `appid` char(20) NOT NULL,
  `serverid` int(4) unsigned NOT NULL,
  `channel` int(4) unsigned NOT NULL,
  `rmb` int(10) unsigned NOT NULL DEFAULT '0',
  `not_rmb` int(10) unsigned NOT NULL DEFAULT '0',
  `player` int(10) unsigned NOT NULL DEFAULT '0',
  `online_lvl` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `online_lvl_txt` varchar(32) NOT NULL DEFAULT '',
  `date` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=2554224 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of sum_playeronline
-- ----------------------------

-- ----------------------------
-- Table structure for sum_props_analysis
-- ----------------------------
DROP TABLE IF EXISTS `sum_props_analysis`;
CREATE TABLE `sum_props_analysis` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `prop_id` bigint(20) NOT NULL,
  `props_get` int(10) NOT NULL,
  `props_use` int(10) NOT NULL,
  `props_left` int(10) NOT NULL DEFAULT '-1',
  `sday` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `prop_name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_server_channel` (`appid`,`sday`,`prop_id`,`serverid`,`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=24128 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of sum_props_analysis
-- ----------------------------

-- ----------------------------
-- Table structure for sum_real_au
-- ----------------------------
DROP TABLE IF EXISTS `sum_real_au`;
CREATE TABLE `sum_real_au` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL DEFAULT '0',
  `channel` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `new_role` int(10) NOT NULL COMMENT '新增玩家',
  `dau` int(10) NOT NULL,
  `clean_dau` int(10) NOT NULL,
  `wau` int(10) NOT NULL,
  `mau` int(10) NOT NULL,
  `sday` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_account_channel` (`appid`,`sday`,`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=980725 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of sum_real_au
-- ----------------------------

-- ----------------------------
-- Table structure for sum_reg
-- ----------------------------
DROP TABLE IF EXISTS `sum_reg`;
CREATE TABLE `sum_reg` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `cnt` int(10) NOT NULL,
  `sday` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_server_channel` (`serverid`,`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of sum_reg
-- ----------------------------

-- ----------------------------
-- Table structure for sum_register_day
-- ----------------------------
DROP TABLE IF EXISTS `sum_register_day`;
CREATE TABLE `sum_register_day` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `cnt` int(10) NOT NULL COMMENT '用户数',
  `date` int(10) NOT NULL COMMENT '日期',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`serverid`,`channel`,`appid`,`date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=162378 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每日注册数据';

-- ----------------------------
-- Records of sum_register_day
-- ----------------------------

-- ----------------------------
-- Table structure for sum_register_day_copy1
-- ----------------------------
DROP TABLE IF EXISTS `sum_register_day_copy1`;
CREATE TABLE `sum_register_day_copy1` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `cnt` int(10) NOT NULL COMMENT '用户数',
  `date` int(10) NOT NULL COMMENT '日期',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6812 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每日注册数据';

-- ----------------------------
-- Records of sum_register_day_copy1
-- ----------------------------

-- ----------------------------
-- Table structure for sum_register_hour
-- ----------------------------
DROP TABLE IF EXISTS `sum_register_hour`;
CREATE TABLE `sum_register_hour` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` char(20) NOT NULL,
  `cnt` int(10) NOT NULL COMMENT '用户数',
  `hour` int(10) NOT NULL COMMENT '小时',
  `date` int(10) NOT NULL COMMENT '日期',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=166708 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='每小时新用户数据';

-- ----------------------------
-- Records of sum_register_hour
-- ----------------------------

-- ----------------------------
-- Table structure for sum_reserveusers_daily
-- ----------------------------
DROP TABLE IF EXISTS `sum_reserveusers_daily`;
CREATE TABLE `sum_reserveusers_daily` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sday` int(11) unsigned NOT NULL,
  `serverid` int(10) unsigned NOT NULL DEFAULT '0',
  `appid` char(20) NOT NULL,
  `channel` int(10) NOT NULL,
  `usercount` int(11) NOT NULL DEFAULT '0',
  `newlogin` int(11) NOT NULL DEFAULT '0',
  `dau` int(11) unsigned NOT NULL DEFAULT '0',
  `wau` int(11) unsigned NOT NULL DEFAULT '0',
  `mau` int(11) unsigned NOT NULL DEFAULT '0',
  `day1` int(11) unsigned NOT NULL DEFAULT '0',
  `day2` int(11) unsigned NOT NULL DEFAULT '0',
  `day3` int(11) unsigned NOT NULL DEFAULT '0',
  `day4` int(11) unsigned NOT NULL DEFAULT '0',
  `day5` int(11) unsigned NOT NULL DEFAULT '0',
  `day6` int(11) unsigned NOT NULL DEFAULT '0',
  `day7` int(11) unsigned NOT NULL DEFAULT '0',
  `day8` int(11) unsigned NOT NULL DEFAULT '0',
  `day14` int(11) NOT NULL DEFAULT '0',
  `day15` int(11) unsigned NOT NULL DEFAULT '0',
  `day30` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sasc` (`sday`,`appid`,`serverid`,`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=539558 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of sum_reserveusers_daily
-- ----------------------------

-- ----------------------------
-- Table structure for sum_reserveusers_daily_copy
-- ----------------------------
DROP TABLE IF EXISTS `sum_reserveusers_daily_copy`;
CREATE TABLE `sum_reserveusers_daily_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sday` int(11) unsigned NOT NULL,
  `serverid` int(10) unsigned NOT NULL DEFAULT '0',
  `appid` char(20) NOT NULL,
  `channel` int(10) NOT NULL,
  `usercount` int(11) NOT NULL DEFAULT '0',
  `newlogin` int(11) NOT NULL DEFAULT '0',
  `dau` int(11) unsigned NOT NULL DEFAULT '0',
  `wau` int(11) unsigned NOT NULL DEFAULT '0',
  `mau` int(11) unsigned NOT NULL DEFAULT '0',
  `day1` int(11) unsigned NOT NULL DEFAULT '0',
  `day2` int(11) unsigned NOT NULL DEFAULT '0',
  `day3` int(11) unsigned NOT NULL DEFAULT '0',
  `day4` int(11) unsigned NOT NULL DEFAULT '0',
  `day5` int(11) unsigned NOT NULL DEFAULT '0',
  `day6` int(11) unsigned NOT NULL DEFAULT '0',
  `day7` int(11) unsigned NOT NULL DEFAULT '0',
  `day8` int(11) unsigned NOT NULL DEFAULT '0',
  `day14` int(11) NOT NULL DEFAULT '0',
  `day15` int(11) unsigned NOT NULL DEFAULT '0',
  `day30` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sasc` (`sday`,`appid`,`serverid`,`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=15370 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of sum_reserveusers_daily_copy
-- ----------------------------

-- ----------------------------
-- Table structure for sum_reserveusers_daily_new
-- ----------------------------
DROP TABLE IF EXISTS `sum_reserveusers_daily_new`;
CREATE TABLE `sum_reserveusers_daily_new` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sday` int(11) unsigned NOT NULL,
  `appid` char(20) CHARACTER SET latin1 NOT NULL,
  `channel` int(10) NOT NULL,
  `usercount` int(11) NOT NULL DEFAULT '0',
  `day1` int(11) unsigned NOT NULL DEFAULT '0',
  `day2` int(11) unsigned NOT NULL DEFAULT '0',
  `day3` int(11) unsigned NOT NULL DEFAULT '0',
  `day4` int(11) unsigned NOT NULL DEFAULT '0',
  `day5` int(11) unsigned NOT NULL DEFAULT '0',
  `day6` int(11) unsigned NOT NULL DEFAULT '0',
  `day7` int(11) unsigned NOT NULL DEFAULT '0',
  `day8` int(11) unsigned NOT NULL DEFAULT '0',
  `day14` int(11) NOT NULL DEFAULT '0',
  `day15` int(11) unsigned NOT NULL DEFAULT '0',
  `day30` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sasc` (`sday`,`appid`,`channel`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1196427 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sum_reserveusers_daily_new
-- ----------------------------

-- ----------------------------
-- Table structure for sum_summary
-- ----------------------------
DROP TABLE IF EXISTS `sum_summary`;
CREATE TABLE `sum_summary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `device` int(11) NOT NULL COMMENT '新增设备',
  `macregister` int(11) NOT NULL COMMENT '新增设备注册数',
  `rare` varchar(50) NOT NULL COMMENT '新增设备转化率',
  `reg` int(11) NOT NULL COMMENT '注册数',
  `role` int(11) NOT NULL COMMENT '创建数',
  `trans_rate` int(11) NOT NULL COMMENT '创建数转化率',
  `dau` int(11) NOT NULL COMMENT '每日在线',
  `wau` int(11) NOT NULL COMMENT '周在线人数',
  `mau` int(11) NOT NULL COMMENT '月在线人数',
  `max_online` int(11) NOT NULL COMMENT '当日最大在线人数',
  `avg_online_cnt` int(11) NOT NULL COMMENT '平均在线人数',
  `avg_online` double(50,0) NOT NULL COMMENT '平均在线时长',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`date`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=utf8 COMMENT='汇总统计表';

-- ----------------------------
-- Records of sum_summary
-- ----------------------------

-- ----------------------------
-- Table structure for sum_summary_by_channel
-- ----------------------------
DROP TABLE IF EXISTS `sum_summary_by_channel`;
CREATE TABLE `sum_summary_by_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `device` int(11) NOT NULL COMMENT '新增设备',
  `macregister` int(11) NOT NULL COMMENT '新增设备注册数',
  `rare` varchar(50) NOT NULL COMMENT '新增设备转化率',
  `reg` int(11) NOT NULL COMMENT '注册数',
  `role` int(11) NOT NULL COMMENT '创建数',
  `trans_rate` int(11) NOT NULL COMMENT '创建数转化率',
  `dau` int(11) NOT NULL COMMENT '每日在线',
  `wau` int(11) NOT NULL COMMENT '周在线人数',
  `mau` int(11) NOT NULL COMMENT '月在线人数',
  `channel` int(11) NOT NULL COMMENT '渠道',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`date`,`channel`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10274 DEFAULT CHARSET=utf8 COMMENT='汇总统计表';

-- ----------------------------
-- Records of sum_summary_by_channel
-- ----------------------------

-- ----------------------------
-- Table structure for sys_menus
-- ----------------------------
DROP TABLE IF EXISTS `sys_menus`;
CREATE TABLE `sys_menus` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '菜单名称',
  `controller` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '控制器',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_contrlller` (`parent_id`,`controller`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='菜单配置';

-- ----------------------------
-- Records of sys_menus
-- ----------------------------
INSERT INTO `sys_menus` VALUES ('1', '1', '汇总', 'Home/Summary');
INSERT INTO `sys_menus` VALUES ('2', '1', '渠道注册统计', 'Home/ChannelRegisterProcess');
INSERT INTO `sys_menus` VALUES ('3', '1', '注册流程统计', 'Home/RegisterProcess');
INSERT INTO `sys_menus` VALUES ('4', '1', '新手流程统计', 'Home/FoolBird');
INSERT INTO `sys_menus` VALUES ('5', '1', 'Bug反馈', 'Home/bugReport');
INSERT INTO `sys_menus` VALUES ('6', '2', '实时在线', 'RealTime/OnlineRt');
INSERT INTO `sys_menus` VALUES ('7', '2', '每小时时在线统计', 'RealTime/Online');
INSERT INTO `sys_menus` VALUES ('8', '2', '安装解压', 'RealTime/Device');
INSERT INTO `sys_menus` VALUES ('9', '2', '设备激活', 'RealTime/DeviceActive');
INSERT INTO `sys_menus` VALUES ('10', '2', '新增玩家', 'RealTime/NewPlayer');
INSERT INTO `sys_menus` VALUES ('11', '3', '新增玩家', 'PlayerAnalysis/NewPlayer');
INSERT INTO `sys_menus` VALUES ('12', '3', '活跃角色', 'PlayerAnalysis/ActivePlayer');
INSERT INTO `sys_menus` VALUES ('13', '3', '活跃账号', 'PlayerAnalysis/ActiveAccounts');
INSERT INTO `sys_menus` VALUES ('14', '3', '留存统计', 'PlayerAnalysis/Remain');
INSERT INTO `sys_menus` VALUES ('15', '3', '设备详情', 'PlayerAnalysis/DeviceDetail');
INSERT INTO `sys_menus` VALUES ('16', '3', '用户信息统计数据', 'PlayerAnalysis/Life');
INSERT INTO `sys_menus` VALUES ('17', '4', '付费数据', 'PayAnalysis/PayData');
INSERT INTO `sys_menus` VALUES ('18', '4', '付费行为', 'PayAnalysis/PayBehavior');
INSERT INTO `sys_menus` VALUES ('19', '4', '付费排行', 'PayAnalysis/PayRank');
INSERT INTO `sys_menus` VALUES ('20', '5', '每日流失', 'LostAnalysis/Index');
INSERT INTO `sys_menus` VALUES ('21', '6', '在线时长', 'OnlineAnalysis/Index');
INSERT INTO `sys_menus` VALUES ('22', '6', '在线习惯', 'OnlineAnalysis/Habit');
INSERT INTO `sys_menus` VALUES ('23', '7', '虚拟币统计', 'SystemAnalysis/Emoney');
INSERT INTO `sys_menus` VALUES ('24', '7', '道具分析', 'SystemAnalysis/Props');
INSERT INTO `sys_menus` VALUES ('25', '7', '副本记录', 'SystemAnalysis/Copy');
INSERT INTO `sys_menus` VALUES ('26', '7', '销售产品', 'Home/Wait');
INSERT INTO `sys_menus` VALUES ('27', '7', '关卡进度', 'SystemAnalysis/Level');
INSERT INTO `sys_menus` VALUES ('28', '7', '成就进度', 'SystemAnalysis/Success');
INSERT INTO `sys_menus` VALUES ('29', '7', '升级历程', 'SystemAnalysis/Upgrade');
INSERT INTO `sys_menus` VALUES ('30', '8', '玩家养成情况', 'SystemFunction/PlayerDevelop');
INSERT INTO `sys_menus` VALUES ('31', '8', '捕捉统计数据', 'SystemFunction/Capture');
INSERT INTO `sys_menus` VALUES ('32', '8', '货币消耗', 'SystemFunction/money_use');
INSERT INTO `sys_menus` VALUES ('33', '8', '道具商店统计数据', 'SystemFunction/props_shop');
INSERT INTO `sys_menus` VALUES ('34', '8', '行为产销记录', 'SystemFunction/BehaviorProduceSale');
INSERT INTO `sys_menus` VALUES ('35', '8', '钻石获取渠道统计', 'SystemFunction/Diamond');
INSERT INTO `sys_menus` VALUES ('36', '8', '钻石消耗渠道统计', 'SystemFunction/Diamond_use');
INSERT INTO `sys_menus` VALUES ('37', '8', '商店销售统计', 'SystemFunction/ShopSaleCount');
INSERT INTO `sys_menus` VALUES ('38', '8', '活跃度统计', 'SystemFunction/PlayerActive');
INSERT INTO `sys_menus` VALUES ('39', '8', '玩法次数统计', 'SystemFunction/PlayingMethod');
INSERT INTO `sys_menus` VALUES ('40', '8', '通用货币获取', 'SystemFunction/CommonCurrency');
INSERT INTO `sys_menus` VALUES ('41', '8', '通用货币消耗', 'SystemFunction/CommonCurrency_use');
INSERT INTO `sys_menus` VALUES ('42', '8', '精灵星级', 'SystemFunction/ElfStarLev');
INSERT INTO `sys_menus` VALUES ('43', '8', '图鉴等级', 'SystemFunction/PhotoLevel');
INSERT INTO `sys_menus` VALUES ('44', '8', '关卡进度统计', 'SystemFunction/LevelProgress');
INSERT INTO `sys_menus` VALUES ('45', '8', '关卡难易程度统计', 'SystemFunction/LevelDifficulty');
INSERT INTO `sys_menus` VALUES ('46', '9', '留存', 'GameAnalysis/Remain');
INSERT INTO `sys_menus` VALUES ('47', '9', '流失', 'GameAnalysis/Lost');
INSERT INTO `sys_menus` VALUES ('48', '9', '流失时长', 'GameAnalysis/LostTimeLong');
INSERT INTO `sys_menus` VALUES ('49', '9', '冒险关卡流失', 'GameAnalysis/RiskLost');

-- ----------------------------
-- Table structure for sys_menus_grp
-- ----------------------------
DROP TABLE IF EXISTS `sys_menus_grp`;
CREATE TABLE `sys_menus_grp` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL DEFAULT '0' COMMENT 'appid',
  `title` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '菜单名称',
  `controller` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '控制器',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_contrlller` (`appid`,`controller`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='菜单配置';

-- ----------------------------
-- Records of sys_menus_grp
-- ----------------------------
INSERT INTO `sys_menus_grp` VALUES ('1', '10002', '数据汇总', 'Home');
INSERT INTO `sys_menus_grp` VALUES ('2', '10002', '实时概况', 'RealTime');
INSERT INTO `sys_menus_grp` VALUES ('3', '10002', '玩家分析', 'PlayerAnalysis');
INSERT INTO `sys_menus_grp` VALUES ('4', '10002', '付费分析', 'PayAnalysis');
INSERT INTO `sys_menus_grp` VALUES ('5', '10002', '流失分析', 'LostAnalysis');
INSERT INTO `sys_menus_grp` VALUES ('6', '10002', '在线分析', 'OnlineAnalysis');
INSERT INTO `sys_menus_grp` VALUES ('7', '10002', '系统分析', 'SystemAnalysis');
INSERT INTO `sys_menus_grp` VALUES ('8', '10002', '系统功能统计', 'SystemFunction');
INSERT INTO `sys_menus_grp` VALUES ('9', '10002', '游服数据统计', 'GameAnalysis');

-- ----------------------------
-- Table structure for u_games
-- ----------------------------
DROP TABLE IF EXISTS `u_games`;
CREATE TABLE `u_games` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of u_games
-- ----------------------------
INSERT INTO `u_games` VALUES ('1', 'test', '2015-12-21 22:53:17');
INSERT INTO `u_games` VALUES ('2', '大凶器', '2015-12-21 23:33:46');
INSERT INTO `u_games` VALUES ('3', '口袋怪兽', '2015-12-21 23:36:12');
INSERT INTO `u_games` VALUES ('4', '衣范儿', '2015-12-21 23:36:25');
INSERT INTO `u_games` VALUES ('5', '真三国无双', '2016-01-03 15:27:01');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', '127.0.0.1', 'administrator', '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36', '', 'admin@admin.com', '', 'DmEElokujDTwj0o4mb.Mje10f798007f93fbe0b4', '1450592694', 'FcLxMlVqR4sFuiPlu43dlO', '1268889823', '1499068352', '1', 'Admin', 'istrator', 'ADMIN', '0');
INSERT INTO `users` VALUES ('2', '127.0.0.1', 'administrator', '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36', '', '1031289030@qq.com', '', 'KrWGB.XIo6BlTDhgsn1zke3d1dca8d5e9c22ae97', '1450592845', null, '1268889823', '1450591880', '1', 'cgp', 'istrator', 'ADMIN', '0');
INSERT INTO `users` VALUES ('3', '27.156.4.83', null, '$2y$08$wM38V2ZEW/L.Fpm8UQyEzuuxEauwHMbQjbR8U8h3nGbT/xLpkmp2O', null, 'testphp@126.com', null, null, null, null, '1450619568', '1480230293', '1', '云飞', 'bai', null, '18059004030');
INSERT INTO `users` VALUES ('4', '27.156.4.83', null, '$2y$08$.pKr36sudQis40W73uACOO6DeRd5ITNY/JoIeY8EfZgNw71i27OVW', null, 'testphp123@126.com', null, null, null, null, '1450619587', null, '1', '云飞', 'bai', null, '18059004030');
INSERT INTO `users` VALUES ('5', '27.156.90.150', null, '$2y$08$tI1Q3mtrj8UnrgSE0nDsrOQMuh5cqBCPspiJmkqBdjEPBtstCxW1.', null, '270901687@qq.com', null, null, null, null, '1475134727', '1475134752', '1', '礼强', '翁', null, '18700000000');
INSERT INTO `users` VALUES ('6', '110.90.12.55', null, '$2y$08$aeYx/53h502fMuGx8VuC0e0ST6KpnEVqiWuxJZTiAIW.bHE6abScm', null, '3464399629@qq.com', null, null, null, null, '1480398552', null, '1', '丹舟', '刘', '', '');

-- ----------------------------
-- Table structure for users_groups
-- ----------------------------
DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE `users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`),
  CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of users_groups
-- ----------------------------
INSERT INTO `users_groups` VALUES ('9', '1', '1');
INSERT INTO `users_groups` VALUES ('11', '2', '2');
INSERT INTO `users_groups` VALUES ('12', '3', '2');
INSERT INTO `users_groups` VALUES ('4', '4', '2');
INSERT INTO `users_groups` VALUES ('5', '5', '2');
INSERT INTO `users_groups` VALUES ('13', '6', '2');
