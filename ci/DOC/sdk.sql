/*
Navicat MySQL Data Transfer

Source Server         : 越南统计
Source Server Version : 50636
Source Host           : localhost:3306
Source Database       : sdk

Target Server Type    : MYSQL
Target Server Version : 50636
File Encoding         : 65001

Date: 2017-07-03 16:04:30
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for access_log
-- ----------------------------
DROP TABLE IF EXISTS `access_log`;
CREATE TABLE `access_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `method` varchar(20) COLLATE utf8_bin NOT NULL,
  `reqtime` timestamp NOT NULL,
  `reqtoken` varchar(32) COLLATE utf8_bin NOT NULL,
  `reqdata` text COLLATE utf8_bin NOT NULL,
  `reqappid` char(18) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_method` (`method`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of access_log
-- ----------------------------

-- ----------------------------
-- Table structure for app_type_list
-- ----------------------------
DROP TABLE IF EXISTS `app_type_list`;
CREATE TABLE `app_type_list` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `typeid` int(10) NOT NULL,
  `typename` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of app_type_list
-- ----------------------------

-- ----------------------------
-- Table structure for client_bug
-- ----------------------------
DROP TABLE IF EXISTS `client_bug`;
CREATE TABLE `client_bug` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) DEFAULT NULL COMMENT '设备号',
  `client_version` varchar(50) DEFAULT NULL COMMENT '客户端版本号',
  `source_client` varchar(50) DEFAULT NULL COMMENT '资源版本号',
  `info` text COMMENT '出错内容',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `logdate` int(11) NOT NULL COMMENT '创建日期',
  `appid` int(11) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL COMMENT '游戏编号',
  PRIMARY KEY (`id`),
  KEY `idx_version` (`client_version`) USING BTREE,
  KEY `idx_time` (`logdate`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4395829 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of client_bug
-- ----------------------------

-- ----------------------------
-- Table structure for game_adventure_leve_201707
-- ----------------------------
DROP TABLE IF EXISTS `game_adventure_leve_201707`;
CREATE TABLE `game_adventure_leve_201707` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `adventurelev` int(11) NOT NULL,
  `active_tasknum` int(11) NOT NULL,
  `rank1_num` int(11) NOT NULL,
  `rank2_num` int(11) NOT NULL,
  `rank3_num` int(11) NOT NULL,
  `rank4_num` int(11) NOT NULL,
  `rank5_num` int(11) NOT NULL,
  `rank6_num` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `serverid` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `logdate` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106091 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_adventure_leve_201707
-- ----------------------------

-- ----------------------------
-- Table structure for game_community
-- ----------------------------
DROP TABLE IF EXISTS `game_community`;
CREATE TABLE `game_community` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL COMMENT '1社团副本2社团入侵3椰蛋树活动',
  `status` int(11) NOT NULL COMMENT '1开启2重置3通关4失败',
  `communityid` int(11) NOT NULL COMMENT '社团编号',
  `communityname` varchar(255) NOT NULL,
  `serverid` int(11) NOT NULL COMMENT '区服',
  `process` int(11) NOT NULL COMMENT '第几个副本',
  `operate_time` int(11) NOT NULL COMMENT '操作时间',
  `onh` int(11) NOT NULL COMMENT '所在时段',
  `logdate` int(11) NOT NULL COMMENT '记录日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=365256 DEFAULT CHARSET=utf8 COMMENT='社团副本';

-- ----------------------------
-- Records of game_community
-- ----------------------------

-- ----------------------------
-- Table structure for game_currency_201707
-- ----------------------------
DROP TABLE IF EXISTS `game_currency_201707`;
CREATE TABLE `game_currency_201707` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `currency1` int(11) unsigned NOT NULL DEFAULT '0',
  `currency2` int(11) unsigned NOT NULL DEFAULT '0',
  `currency3` int(11) unsigned NOT NULL DEFAULT '0',
  `currency4` int(11) unsigned NOT NULL DEFAULT '0',
  `currency5` int(11) unsigned NOT NULL DEFAULT '0',
  `currency6` int(11) unsigned NOT NULL DEFAULT '0',
  `currency7` int(11) unsigned NOT NULL DEFAULT '0',
  `currency8` int(11) unsigned NOT NULL DEFAULT '0',
  `currency9` int(11) unsigned NOT NULL DEFAULT '0',
  `currency10` int(11) unsigned NOT NULL DEFAULT '0',
  `currency11` int(11) unsigned NOT NULL DEFAULT '0',
  `currency12` int(11) unsigned NOT NULL DEFAULT '0',
  `currency13` int(11) unsigned NOT NULL DEFAULT '0',
  `currency14` int(11) unsigned NOT NULL DEFAULT '0',
  `currency15` int(11) unsigned NOT NULL DEFAULT '0',
  `currency16` int(11) unsigned NOT NULL DEFAULT '0',
  `currency17` int(11) unsigned NOT NULL DEFAULT '0',
  `currency18` int(11) unsigned NOT NULL DEFAULT '0',
  `currency19` int(11) unsigned NOT NULL DEFAULT '0',
  `currency20` int(11) unsigned NOT NULL DEFAULT '0',
  `currency21` int(11) unsigned NOT NULL DEFAULT '0',
  `currency22` int(11) unsigned NOT NULL DEFAULT '0',
  `currency23` int(11) unsigned NOT NULL DEFAULT '0',
  `currency24` int(11) unsigned NOT NULL DEFAULT '0',
  `currency25` int(11) unsigned NOT NULL DEFAULT '0',
  `currency26` int(11) unsigned NOT NULL DEFAULT '0',
  `currency27` int(11) unsigned NOT NULL DEFAULT '0',
  `currency28` int(11) unsigned NOT NULL DEFAULT '0',
  `currency29` int(11) unsigned NOT NULL DEFAULT '0',
  `currency30` int(11) unsigned NOT NULL DEFAULT '0',
  `currency31` int(11) unsigned NOT NULL DEFAULT '0',
  `currency32` int(11) unsigned NOT NULL DEFAULT '0',
  `currency33` int(11) unsigned NOT NULL DEFAULT '0',
  `currency34` int(11) unsigned NOT NULL DEFAULT '0',
  `currency35` int(11) unsigned NOT NULL DEFAULT '0',
  `currency36` int(11) unsigned NOT NULL DEFAULT '0',
  `currency37` int(11) unsigned NOT NULL DEFAULT '0',
  `currency38` int(11) unsigned NOT NULL DEFAULT '0',
  `currency39` int(11) unsigned NOT NULL DEFAULT '0',
  `currency40` int(11) unsigned NOT NULL DEFAULT '0',
  `account_id` int(11) NOT NULL,
  `serverid` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `logdate` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk` (`account_id`,`serverid`,`logdate`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=279024 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_currency_201707
-- ----------------------------

-- ----------------------------
-- Table structure for game_data
-- ----------------------------
DROP TABLE IF EXISTS `game_data`;
CREATE TABLE `game_data` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `endTime` int(11) NOT NULL COMMENT '结束时间',
  `type` int(11) NOT NULL COMMENT '类型0普通1练习2天梯普通3天梯神兽场4排位',
  `createTime` int(11) NOT NULL COMMENT '入库时间',
  `btype` int(11) NOT NULL DEFAULT '1' COMMENT '1全球对战2冠军之夜3社团争霸',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2413678 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_data
-- ----------------------------

-- ----------------------------
-- Table structure for game_data_201707
-- ----------------------------
DROP TABLE IF EXISTS `game_data_201707`;
CREATE TABLE `game_data_201707` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `endTime` int(11) NOT NULL COMMENT '结束时间',
  `type` int(11) NOT NULL COMMENT '类型0普通1练习2天梯普通3天梯神兽场4排位',
  `createTime` int(11) NOT NULL COMMENT '入库时间',
  `btype` int(11) NOT NULL DEFAULT '1' COMMENT '1全球对战4冠军之夜',
  `continuous` int(11) NOT NULL DEFAULT '0' COMMENT '持续回合',
  `gameround` int(11) NOT NULL DEFAULT '0' COMMENT '多少轮',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=114821 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_data_201707
-- ----------------------------

-- ----------------------------
-- Table structure for game_death
-- ----------------------------
DROP TABLE IF EXISTS `game_death`;
CREATE TABLE `game_death` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serverid` int(11) NOT NULL,
  `die_time` int(11) NOT NULL COMMENT '死亡时间',
  `logdate` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `lev` int(11) NOT NULL COMMENT '神兽等级',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`serverid`,`logdate`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_death
-- ----------------------------

-- ----------------------------
-- Table structure for game_drops
-- ----------------------------
DROP TABLE IF EXISTS `game_drops`;
CREATE TABLE `game_drops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `btype` int(11) NOT NULL COMMENT '1PVP练习2PVP天梯3异步竞技场4社团战战斗5全球6v66冠军之夜初赛7冠军之夜淘汰赛',
  `create_time` int(11) NOT NULL COMMENT '掉线时间',
  `serverid` int(11) NOT NULL,
  `accountid` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '玩家名称',
  `communityid` int(11) DEFAULT NULL COMMENT '社团编号',
  `composition` varchar(255) DEFAULT NULL COMMENT '社团职位',
  `channel` int(11) DEFAULT NULL,
  `client_version` int(11) NOT NULL COMMENT '客户端版本',
  `client_type` varchar(255) NOT NULL COMMENT '客户端类型',
  `sys` varchar(255) DEFAULT NULL COMMENT '系统',
  `userid` bigint(20) NOT NULL COMMENT '用户id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=108762 DEFAULT CHARSET=utf8 COMMENT='掉线统计';

-- ----------------------------
-- Records of game_drops
-- ----------------------------

-- ----------------------------
-- Table structure for game_egg
-- ----------------------------
DROP TABLE IF EXISTS `game_egg`;
CREATE TABLE `game_egg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `communityid` int(11) NOT NULL COMMENT '社团编号',
  `serverid` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1幸福值40 2幸福值803远古宝藏积分4远古宝藏获得物资',
  `operate_time` int(11) NOT NULL COMMENT '记录时间',
  `logdate` int(11) NOT NULL,
  `param` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82917 DEFAULT CHARSET=utf8 COMMENT='椰蛋树活动';

-- ----------------------------
-- Records of game_egg
-- ----------------------------

-- ----------------------------
-- Table structure for game_eudemon
-- ----------------------------
DROP TABLE IF EXISTS `game_eudemon`;
CREATE TABLE `game_eudemon` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `serverid` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `template_id` bigint(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10199 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_eudemon
-- ----------------------------

-- ----------------------------
-- Table structure for game_match_201707
-- ----------------------------
DROP TABLE IF EXISTS `game_match_201707`;
CREATE TABLE `game_match_201707` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `matchtime` int(11) NOT NULL COMMENT '匹配时间',
  `type` int(11) NOT NULL COMMENT '1是练习2是普通3是精英',
  `dan` int(11) NOT NULL COMMENT '段位',
  `serverid` int(11) NOT NULL,
  `accountid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `logdate` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=227367 DEFAULT CHARSET=utf8 COMMENT='战斗匹配时长';

-- ----------------------------
-- Records of game_match_201707
-- ----------------------------

-- ----------------------------
-- Table structure for game_player_fashion
-- ----------------------------
DROP TABLE IF EXISTS `game_player_fashion`;
CREATE TABLE `game_player_fashion` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` int(4) unsigned NOT NULL DEFAULT '0',
  `fashion_type` int(4) unsigned NOT NULL DEFAULT '0',
  `end_time` int(4) unsigned NOT NULL DEFAULT '0',
  `server_id` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28085 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_player_fashion
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170701
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170701`;
CREATE TABLE `game_process_20170701` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6876041 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170701
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170702
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170702`;
CREATE TABLE `game_process_20170702` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6905924 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170702
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170703
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170703`;
CREATE TABLE `game_process_20170703` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170703
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170704
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170704`;
CREATE TABLE `game_process_20170704` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170704
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170705
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170705`;
CREATE TABLE `game_process_20170705` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170705
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170706
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170706`;
CREATE TABLE `game_process_20170706` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170706
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170707
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170707`;
CREATE TABLE `game_process_20170707` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170707
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170708
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170708`;
CREATE TABLE `game_process_20170708` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170708
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170709
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170709`;
CREATE TABLE `game_process_20170709` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170709
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170710
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170710`;
CREATE TABLE `game_process_20170710` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170710
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170711
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170711`;
CREATE TABLE `game_process_20170711` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170711
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170712
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170712`;
CREATE TABLE `game_process_20170712` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170712
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170713
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170713`;
CREATE TABLE `game_process_20170713` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170713
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170714
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170714`;
CREATE TABLE `game_process_20170714` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170714
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170715
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170715`;
CREATE TABLE `game_process_20170715` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170715
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170716
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170716`;
CREATE TABLE `game_process_20170716` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170716
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170717
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170717`;
CREATE TABLE `game_process_20170717` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170717
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170718
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170718`;
CREATE TABLE `game_process_20170718` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170718
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170719
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170719`;
CREATE TABLE `game_process_20170719` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170719
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170720
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170720`;
CREATE TABLE `game_process_20170720` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170720
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170721
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170721`;
CREATE TABLE `game_process_20170721` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170721
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170722
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170722`;
CREATE TABLE `game_process_20170722` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170722
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170723
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170723`;
CREATE TABLE `game_process_20170723` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170723
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170724
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170724`;
CREATE TABLE `game_process_20170724` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170724
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170725
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170725`;
CREATE TABLE `game_process_20170725` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170725
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170726
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170726`;
CREATE TABLE `game_process_20170726` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170726
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170727
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170727`;
CREATE TABLE `game_process_20170727` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170727
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170728
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170728`;
CREATE TABLE `game_process_20170728` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170728
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170729
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170729`;
CREATE TABLE `game_process_20170729` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170729
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170730
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170730`;
CREATE TABLE `game_process_20170730` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170730
-- ----------------------------

-- ----------------------------
-- Table structure for game_process_20170731
-- ----------------------------
DROP TABLE IF EXISTS `game_process_20170731`;
CREATE TABLE `game_process_20170731` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` bigint(20) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  `logout_time` int(11) NOT NULL,
  `login_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `maxGroup` int(11) NOT NULL DEFAULT '0' COMMENT '普通副本章节0一张没打',
  `progress_num` int(11) NOT NULL COMMENT '关卡',
  `process_status` int(11) NOT NULL DEFAULT '0' COMMENT '0成功1失败',
  `maxGroup2` int(11) NOT NULL COMMENT '精英副本章节',
  `progress_num2` int(11) NOT NULL,
  `process_status2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`player_id`,`serverid`) USING BTREE,
  KEY `idx_n` (`serverid`,`maxGroup`,`maxGroup2`,`progress_num`,`progress_num2`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_process_20170731
-- ----------------------------

-- ----------------------------
-- Table structure for game_rank
-- ----------------------------
DROP TABLE IF EXISTS `game_rank`;
CREATE TABLE `game_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accountid` bigint(20) NOT NULL,
  `serverid` int(11) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `vip_level` int(11) NOT NULL COMMENT 'vip等级',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `power` int(11) NOT NULL COMMENT '战力',
  `rank` int(11) NOT NULL COMMENT '排名',
  `created_at` int(11) NOT NULL,
  `logdate` int(11) NOT NULL COMMENT '记录日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`logdate`)
) ENGINE=InnoDB AUTO_INCREMENT=6389 DEFAULT CHARSET=utf8 COMMENT='冠军之夜排名';

-- ----------------------------
-- Records of game_rank
-- ----------------------------

-- ----------------------------
-- Table structure for game_rank_emoney
-- ----------------------------
DROP TABLE IF EXISTS `game_rank_emoney`;
CREATE TABLE `game_rank_emoney` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logdate` int(11) NOT NULL COMMENT '日期',
  `serverid` int(11) NOT NULL,
  `accountid` bigint(20) NOT NULL,
  `emoney` bigint(20) NOT NULL COMMENT '数值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`logdate`,`serverid`,`accountid`) USING BTREE,
  KEY `idx_accountid` (`logdate`,`serverid`,`accountid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1554220 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_rank_emoney
-- ----------------------------

-- ----------------------------
-- Table structure for game_rank_money
-- ----------------------------
DROP TABLE IF EXISTS `game_rank_money`;
CREATE TABLE `game_rank_money` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logdate` int(11) NOT NULL COMMENT '日期',
  `serverid` int(11) NOT NULL,
  `accountid` bigint(20) NOT NULL,
  `money` bigint(20) NOT NULL COMMENT '数值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`logdate`,`serverid`,`accountid`) USING BTREE,
  KEY `idx_accountid` (`logdate`,`serverid`,`accountid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1306192 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_rank_money
-- ----------------------------

-- ----------------------------
-- Table structure for game_sign
-- ----------------------------
DROP TABLE IF EXISTS `game_sign`;
CREATE TABLE `game_sign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serverid` int(11) NOT NULL,
  `communityid` int(11) NOT NULL COMMENT '社团id',
  `comname` varchar(100) DEFAULT NULL COMMENT '社团名',
  `comlevel` int(11) NOT NULL COMMENT '社团等级',
  `contribution` int(11) NOT NULL COMMENT '周贡献',
  `logdate` int(11) NOT NULL COMMENT '报名日期',
  `created_at` int(11) NOT NULL COMMENT '记录时间',
  `isWinner` int(11) NOT NULL DEFAULT '0' COMMENT '1冠军',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`serverid`,`communityid`,`logdate`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5473 DEFAULT CHARSET=utf8 COMMENT='社团争霸报名表';

-- ----------------------------
-- Records of game_sign
-- ----------------------------

-- ----------------------------
-- Table structure for game_sign_config
-- ----------------------------
DROP TABLE IF EXISTS `game_sign_config`;
CREATE TABLE `game_sign_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serverids` varchar(255) NOT NULL COMMENT '区服分组',
  `gametime` int(11) NOT NULL COMMENT '赛事日期',
  `type` int(255) NOT NULL DEFAULT '1' COMMENT '1社团2冠军之夜',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`serverids`,`gametime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1053 DEFAULT CHARSET=utf8 COMMENT='区服分组编辑表';

-- ----------------------------
-- Records of game_sign_config
-- ----------------------------

-- ----------------------------
-- Table structure for game_squad_eudemon
-- ----------------------------
DROP TABLE IF EXISTS `game_squad_eudemon`;
CREATE TABLE `game_squad_eudemon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `totalpower` int(11) NOT NULL,
  `server_id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `eud_id1` int(11) DEFAULT NULL,
  `eud_id2` int(11) DEFAULT NULL,
  `eud_id3` int(11) DEFAULT NULL,
  `eud_id4` int(11) DEFAULT NULL,
  `eud_id5` int(11) DEFAULT NULL,
  `eud_id6` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=224218 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_squad_eudemon
-- ----------------------------

-- ----------------------------
-- Table structure for game_stone
-- ----------------------------
DROP TABLE IF EXISTS `game_stone`;
CREATE TABLE `game_stone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stonetype` int(11) NOT NULL COMMENT '1普通2格斗3飞行4毒系5地面6岩石7虫系8幽灵9钢系10火系11水系12草系13电气14超能15冰系16龙系17恶系18妖精',
  `stonestep` int(11) NOT NULL COMMENT '层',
  `hp` int(11) NOT NULL,
  `attack_p` int(11) NOT NULL COMMENT '物攻',
  `defense_p` int(11) NOT NULL COMMENT '物防',
  `attack_s` int(11) NOT NULL COMMENT '特攻',
  `defense_s` int(11) NOT NULL COMMENT '特防',
  `speed` int(11) NOT NULL COMMENT '速度',
  `player_id` bigint(11) NOT NULL,
  `serverid` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `account_id` bigint(11) NOT NULL,
  `logdate` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk` (`stonetype`,`account_id`,`logdate`,`serverid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=594530 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_stone
-- ----------------------------

-- ----------------------------
-- Table structure for game_synscience_201707
-- ----------------------------
DROP TABLE IF EXISTS `game_synscience_201707`;
CREATE TABLE `game_synscience_201707` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `level` smallint(6) NOT NULL,
  `account_id` int(11) NOT NULL,
  `serverid` int(11) NOT NULL,
  `vip_level` int(11) NOT NULL,
  `logdate` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk` (`player_id`,`group_id`,`serverid`,`logdate`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=313898 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_synscience_201707
-- ----------------------------

-- ----------------------------
-- Table structure for game_tower_201707
-- ----------------------------
DROP TABLE IF EXISTS `game_tower_201707`;
CREATE TABLE `game_tower_201707` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logdate` int(11) NOT NULL,
  `tower` int(11) NOT NULL COMMENT '层数',
  `integral` int(11) NOT NULL COMMENT '积分',
  `serverid` int(11) NOT NULL,
  `playerid` bigint(20) NOT NULL,
  `eudemon` bigint(20) NOT NULL,
  `hp` int(11) NOT NULL,
  `skills1` int(11) NOT NULL COMMENT '技能1',
  `skills2` int(11) DEFAULT NULL,
  `skills3` int(11) DEFAULT NULL,
  `skills4` int(11) DEFAULT NULL,
  `pp1` int(11) NOT NULL COMMENT '技能使用的pp值',
  `pp2` int(11) DEFAULT NULL,
  `pp3` int(11) DEFAULT NULL,
  `pp4` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47233 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of game_tower_201707
-- ----------------------------

-- ----------------------------
-- Table structure for game_user
-- ----------------------------
DROP TABLE IF EXISTS `game_user`;
CREATE TABLE `game_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serverid` int(11) NOT NULL,
  `accountid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `gameid` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '胜负1胜0负',
  `dan` int(11) NOT NULL COMMENT '段位',
  `viplevel` int(11) DEFAULT NULL COMMENT 'vip等级',
  `level` int(11) DEFAULT NULL COMMENT '用户等级',
  PRIMARY KEY (`id`),
  KEY `idx_account` (`serverid`,`accountid`,`gameid`,`dan`,`viplevel`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4827353 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_user
-- ----------------------------

-- ----------------------------
-- Table structure for game_user_201707
-- ----------------------------
DROP TABLE IF EXISTS `game_user_201707`;
CREATE TABLE `game_user_201707` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serverid` int(11) NOT NULL,
  `accountid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `gameid` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '胜负1胜0负',
  `dan` int(11) NOT NULL COMMENT '段位',
  `viplevel` int(11) DEFAULT NULL COMMENT 'vip等级',
  `level` int(11) DEFAULT NULL COMMENT '用户等级',
  `power` int(11) NOT NULL COMMENT '战力',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=229643 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_user_201707
-- ----------------------------

-- ----------------------------
-- Table structure for game_user_data
-- ----------------------------
DROP TABLE IF EXISTS `game_user_data`;
CREATE TABLE `game_user_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` int(11) NOT NULL,
  `endTime` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '比赛类型',
  `serverid1` int(11) NOT NULL,
  `accountid1` bigint(20) NOT NULL,
  `userid1` int(11) NOT NULL,
  `name1` varchar(50) NOT NULL,
  `status1` int(11) NOT NULL COMMENT '胜负1胜0负',
  `dan1` int(11) NOT NULL COMMENT '段位',
  `viplevel1` int(11) DEFAULT NULL COMMENT 'vip等级',
  `level1` int(11) DEFAULT NULL COMMENT '用户等级',
  `eudemon11` bigint(20) DEFAULT NULL,
  `estatus11` int(11) DEFAULT NULL,
  `eudemon12` bigint(20) DEFAULT NULL,
  `eudemon13` bigint(20) DEFAULT NULL,
  `eudemon14` bigint(20) DEFAULT NULL,
  `eudemon15` bigint(20) DEFAULT NULL,
  `eudemon16` bigint(20) DEFAULT NULL,
  `estatus12` int(11) DEFAULT NULL,
  `estatus13` int(11) DEFAULT NULL,
  `estatus14` int(11) DEFAULT NULL,
  `estatus15` int(11) DEFAULT NULL,
  `estatus16` int(11) DEFAULT NULL,
  `serverid2` int(11) DEFAULT NULL,
  `accountid2` bigint(20) DEFAULT NULL,
  `userid2` int(11) DEFAULT NULL,
  `name2` varchar(50) DEFAULT NULL,
  `status2` int(11) DEFAULT NULL,
  `dan2` int(11) DEFAULT NULL,
  `viplevel2` int(11) DEFAULT NULL,
  `level2` int(11) DEFAULT NULL,
  `eudemon21` bigint(20) DEFAULT NULL,
  `eudemon22` bigint(20) DEFAULT NULL,
  `eudemon23` bigint(20) DEFAULT NULL,
  `eudemon24` bigint(20) DEFAULT NULL,
  `eudemon25` bigint(20) DEFAULT NULL,
  `eudemon26` bigint(20) DEFAULT NULL,
  `estatus21` int(11) DEFAULT NULL,
  `estatus22` int(11) DEFAULT NULL,
  `estatus23` int(11) DEFAULT NULL,
  `estatus24` int(11) DEFAULT NULL,
  `estatus25` int(11) DEFAULT NULL,
  `estatus26` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`gameid`) USING BTREE,
  KEY `idx_gameid` (`gameid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2334588 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_user_data
-- ----------------------------

-- ----------------------------
-- Table structure for game_user_data_201707
-- ----------------------------
DROP TABLE IF EXISTS `game_user_data_201707`;
CREATE TABLE `game_user_data_201707` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` int(11) NOT NULL,
  `endTime` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '比赛类型',
  `serverid1` int(11) NOT NULL,
  `accountid1` bigint(20) NOT NULL,
  `userid1` int(11) NOT NULL,
  `name1` varchar(50) NOT NULL,
  `status1` int(11) NOT NULL COMMENT '胜负0胜1负',
  `dan1` int(11) NOT NULL COMMENT '段位',
  `viplevel1` int(11) DEFAULT NULL COMMENT 'vip等级',
  `level1` int(11) DEFAULT NULL COMMENT '用户等级',
  `eudemon11` bigint(20) DEFAULT NULL,
  `estatus11` int(11) DEFAULT NULL,
  `eudemon12` bigint(20) DEFAULT NULL,
  `eudemon13` bigint(20) DEFAULT NULL,
  `eudemon14` bigint(20) DEFAULT NULL,
  `eudemon15` bigint(20) DEFAULT NULL,
  `eudemon16` bigint(20) DEFAULT NULL,
  `estatus12` int(11) DEFAULT NULL,
  `estatus13` int(11) DEFAULT NULL,
  `estatus14` int(11) DEFAULT NULL,
  `estatus15` int(11) DEFAULT NULL,
  `estatus16` int(11) DEFAULT NULL,
  `serverid2` int(11) DEFAULT NULL,
  `accountid2` bigint(20) DEFAULT NULL,
  `userid2` int(11) DEFAULT NULL,
  `name2` varchar(50) DEFAULT NULL,
  `status2` int(11) DEFAULT NULL,
  `dan2` int(11) DEFAULT NULL,
  `viplevel2` int(11) DEFAULT NULL,
  `level2` int(11) DEFAULT NULL,
  `eudemon21` bigint(20) DEFAULT NULL,
  `eudemon22` bigint(20) DEFAULT NULL,
  `eudemon23` bigint(20) DEFAULT NULL,
  `eudemon24` bigint(20) DEFAULT NULL,
  `eudemon25` bigint(20) DEFAULT NULL,
  `eudemon26` bigint(20) DEFAULT NULL,
  `estatus21` int(11) DEFAULT NULL,
  `estatus22` int(11) DEFAULT NULL,
  `estatus23` int(11) DEFAULT NULL,
  `estatus24` int(11) DEFAULT NULL,
  `estatus25` int(11) DEFAULT NULL,
  `estatus26` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=114818 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_user_data_201707
-- ----------------------------

-- ----------------------------
-- Table structure for game_user_eudemon
-- ----------------------------
DROP TABLE IF EXISTS `game_user_eudemon`;
CREATE TABLE `game_user_eudemon` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eudemon` int(11) NOT NULL COMMENT '精灵id',
  `status` int(11) NOT NULL COMMENT '0死亡 1存活  2未上场',
  `gameuserid` int(11) NOT NULL COMMENT '该场比赛所属用户',
  PRIMARY KEY (`id`),
  KEY `idx_eudemon` (`eudemon`,`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=28810761 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_user_eudemon
-- ----------------------------

-- ----------------------------
-- Table structure for game_user_eudemon_201707
-- ----------------------------
DROP TABLE IF EXISTS `game_user_eudemon_201707`;
CREATE TABLE `game_user_eudemon_201707` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eudemon` int(11) NOT NULL COMMENT '精灵id',
  `status` int(11) NOT NULL COMMENT '0死亡 1存活  2未上场',
  `gameuserid` int(11) NOT NULL COMMENT '该场比赛所属用户',
  `hp` int(11) DEFAULT '0' COMMENT '剩余体力',
  `skills1` int(11) DEFAULT '0',
  `skills2` int(11) DEFAULT '0',
  `skills3` int(11) DEFAULT '0',
  `skills4` int(11) DEFAULT '0',
  `pp1` int(11) DEFAULT '0',
  `pp2` int(11) DEFAULT '0',
  `pp3` int(11) DEFAULT '0',
  `pp4` int(11) DEFAULT '0',
  `abilities` bigint(20) DEFAULT NULL,
  `fruit` bigint(20) DEFAULT NULL,
  `equip` bigint(20) DEFAULT NULL,
  `kidney` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1374862 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of game_user_eudemon_201707
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170701
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170701`;
CREATE TABLE `game_world_eudemon_20170701` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=567733 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170701
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170702
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170702`;
CREATE TABLE `game_world_eudemon_20170702` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=535069 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170702
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170703
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170703`;
CREATE TABLE `game_world_eudemon_20170703` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170703
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170704
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170704`;
CREATE TABLE `game_world_eudemon_20170704` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170704
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170705
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170705`;
CREATE TABLE `game_world_eudemon_20170705` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170705
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170706
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170706`;
CREATE TABLE `game_world_eudemon_20170706` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170706
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170707
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170707`;
CREATE TABLE `game_world_eudemon_20170707` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170707
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170708
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170708`;
CREATE TABLE `game_world_eudemon_20170708` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170708
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170709
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170709`;
CREATE TABLE `game_world_eudemon_20170709` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170709
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170710
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170710`;
CREATE TABLE `game_world_eudemon_20170710` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170710
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170711
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170711`;
CREATE TABLE `game_world_eudemon_20170711` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170711
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170712
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170712`;
CREATE TABLE `game_world_eudemon_20170712` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170712
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170713
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170713`;
CREATE TABLE `game_world_eudemon_20170713` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170713
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170714
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170714`;
CREATE TABLE `game_world_eudemon_20170714` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170714
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170715
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170715`;
CREATE TABLE `game_world_eudemon_20170715` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170715
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170716
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170716`;
CREATE TABLE `game_world_eudemon_20170716` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170716
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170717
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170717`;
CREATE TABLE `game_world_eudemon_20170717` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170717
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170718
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170718`;
CREATE TABLE `game_world_eudemon_20170718` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170718
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170719
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170719`;
CREATE TABLE `game_world_eudemon_20170719` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170719
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170720
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170720`;
CREATE TABLE `game_world_eudemon_20170720` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170720
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170721
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170721`;
CREATE TABLE `game_world_eudemon_20170721` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170721
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170722
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170722`;
CREATE TABLE `game_world_eudemon_20170722` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170722
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170723
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170723`;
CREATE TABLE `game_world_eudemon_20170723` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170723
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170724
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170724`;
CREATE TABLE `game_world_eudemon_20170724` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170724
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170725
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170725`;
CREATE TABLE `game_world_eudemon_20170725` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170725
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170726
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170726`;
CREATE TABLE `game_world_eudemon_20170726` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170726
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170727
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170727`;
CREATE TABLE `game_world_eudemon_20170727` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170727
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170728
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170728`;
CREATE TABLE `game_world_eudemon_20170728` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170728
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170729
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170729`;
CREATE TABLE `game_world_eudemon_20170729` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170729
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170730
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170730`;
CREATE TABLE `game_world_eudemon_20170730` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170730
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_eudemon_20170731
-- ----------------------------
DROP TABLE IF EXISTS `game_world_eudemon_20170731`;
CREATE TABLE `game_world_eudemon_20170731` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `eud` int(11) NOT NULL COMMENT '精灵模板编号',
  `ex1` int(11) NOT NULL COMMENT '精灵个体总值',
  `ex2` int(11) NOT NULL COMMENT '精灵努力总值',
  `intilv` int(11) NOT NULL COMMENT '亲密等级',
  `booklv` int(11) NOT NULL COMMENT '图鉴等级',
  `serverid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_no` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_eudemon_20170731
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170701
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170701`;
CREATE TABLE `game_world_user_20170701` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=101194 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170701
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170702
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170702`;
CREATE TABLE `game_world_user_20170702` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=95630 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170702
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170703
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170703`;
CREATE TABLE `game_world_user_20170703` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170703
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170704
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170704`;
CREATE TABLE `game_world_user_20170704` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170704
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170705
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170705`;
CREATE TABLE `game_world_user_20170705` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170705
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170706
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170706`;
CREATE TABLE `game_world_user_20170706` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170706
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170707
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170707`;
CREATE TABLE `game_world_user_20170707` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170707
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170708
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170708`;
CREATE TABLE `game_world_user_20170708` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170708
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170709
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170709`;
CREATE TABLE `game_world_user_20170709` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170709
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170710
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170710`;
CREATE TABLE `game_world_user_20170710` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170710
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170711
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170711`;
CREATE TABLE `game_world_user_20170711` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170711
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170712
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170712`;
CREATE TABLE `game_world_user_20170712` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170712
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170713
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170713`;
CREATE TABLE `game_world_user_20170713` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170713
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170714
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170714`;
CREATE TABLE `game_world_user_20170714` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170714
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170715
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170715`;
CREATE TABLE `game_world_user_20170715` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170715
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170716
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170716`;
CREATE TABLE `game_world_user_20170716` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170716
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170717
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170717`;
CREATE TABLE `game_world_user_20170717` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170717
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170718
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170718`;
CREATE TABLE `game_world_user_20170718` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170718
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170719
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170719`;
CREATE TABLE `game_world_user_20170719` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170719
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170720
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170720`;
CREATE TABLE `game_world_user_20170720` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170720
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170721
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170721`;
CREATE TABLE `game_world_user_20170721` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170721
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170722
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170722`;
CREATE TABLE `game_world_user_20170722` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170722
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170723
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170723`;
CREATE TABLE `game_world_user_20170723` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170723
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170724
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170724`;
CREATE TABLE `game_world_user_20170724` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170724
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170725
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170725`;
CREATE TABLE `game_world_user_20170725` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170725
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170726
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170726`;
CREATE TABLE `game_world_user_20170726` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170726
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170727
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170727`;
CREATE TABLE `game_world_user_20170727` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170727
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170728
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170728`;
CREATE TABLE `game_world_user_20170728` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170728
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170729
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170729`;
CREATE TABLE `game_world_user_20170729` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170729
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170730
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170730`;
CREATE TABLE `game_world_user_20170730` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170730
-- ----------------------------

-- ----------------------------
-- Table structure for game_world_user_20170731
-- ----------------------------
DROP TABLE IF EXISTS `game_world_user_20170731`;
CREATE TABLE `game_world_user_20170731` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` bigint(20) NOT NULL COMMENT '玩家id',
  `account_id` bigint(20) NOT NULL COMMENT '玩家账号id',
  `serverid` int(11) NOT NULL COMMENT '服务器id',
  `name` varchar(100) NOT NULL COMMENT '玩家名',
  `level` int(11) NOT NULL COMMENT '玩家等级',
  `vip_level` int(11) NOT NULL COMMENT '玩家vip等级',
  `season` int(11) NOT NULL COMMENT '赛季',
  `com_totaltimes` int(11) NOT NULL COMMENT '普通赛场次',
  `com_wintimes` int(11) NOT NULL COMMENT '普通赛胜场',
  `com_ranklev` int(11) NOT NULL COMMENT '普通赛当前段位',
  `elite_totaltimes` int(11) NOT NULL COMMENT '精英赛场次',
  `elite_wintimes` int(11) NOT NULL COMMENT '精英赛胜场',
  `elite_ranklev` int(11) NOT NULL COMMENT '精英赛当前段位',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`playerid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1950 DEFAULT CHARSET=utf8 COMMENT='全球段位分布和段位养成';

-- ----------------------------
-- Records of game_world_user_20170731
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170701
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170701`;
CREATE TABLE `item_trading_20170701` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12252026 DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170701
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170702
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170702`;
CREATE TABLE `item_trading_20170702` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11924705 DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170702
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170703
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170703`;
CREATE TABLE `item_trading_20170703` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6643762 DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170703
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170704
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170704`;
CREATE TABLE `item_trading_20170704` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170704
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170705
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170705`;
CREATE TABLE `item_trading_20170705` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170705
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170706
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170706`;
CREATE TABLE `item_trading_20170706` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170706
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170707
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170707`;
CREATE TABLE `item_trading_20170707` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170707
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170708
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170708`;
CREATE TABLE `item_trading_20170708` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170708
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170709
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170709`;
CREATE TABLE `item_trading_20170709` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170709
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170710
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170710`;
CREATE TABLE `item_trading_20170710` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170710
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170711
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170711`;
CREATE TABLE `item_trading_20170711` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170711
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170712
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170712`;
CREATE TABLE `item_trading_20170712` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170712
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170713
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170713`;
CREATE TABLE `item_trading_20170713` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170713
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170714
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170714`;
CREATE TABLE `item_trading_20170714` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170714
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170715
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170715`;
CREATE TABLE `item_trading_20170715` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170715
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170716
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170716`;
CREATE TABLE `item_trading_20170716` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170716
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170717
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170717`;
CREATE TABLE `item_trading_20170717` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170717
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170718
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170718`;
CREATE TABLE `item_trading_20170718` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170718
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170719
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170719`;
CREATE TABLE `item_trading_20170719` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170719
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170720
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170720`;
CREATE TABLE `item_trading_20170720` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170720
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170721
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170721`;
CREATE TABLE `item_trading_20170721` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170721
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170722
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170722`;
CREATE TABLE `item_trading_20170722` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170722
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170723
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170723`;
CREATE TABLE `item_trading_20170723` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170723
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170724
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170724`;
CREATE TABLE `item_trading_20170724` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170724
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170725
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170725`;
CREATE TABLE `item_trading_20170725` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170725
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170726
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170726`;
CREATE TABLE `item_trading_20170726` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170726
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170727
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170727`;
CREATE TABLE `item_trading_20170727` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170727
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170728
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170728`;
CREATE TABLE `item_trading_20170728` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170728
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170729
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170729`;
CREATE TABLE `item_trading_20170729` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170729
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170730
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170730`;
CREATE TABLE `item_trading_20170730` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170730
-- ----------------------------

-- ----------------------------
-- Table structure for item_trading_20170731
-- ----------------------------
DROP TABLE IF EXISTS `item_trading_20170731`;
CREATE TABLE `item_trading_20170731` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `table_type` int(10) NOT NULL DEFAULT '0' COMMENT '关联表类型',
  `behavior_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户行为id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '类型0获取1消耗',
  `item_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '物品id',
  `item_num` int(10) NOT NULL DEFAULT '0' COMMENT '物品数量',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物品产销表';

-- ----------------------------
-- Records of item_trading_20170731
-- ----------------------------

-- ----------------------------
-- Table structure for online
-- ----------------------------
DROP TABLE IF EXISTS `online`;
CREATE TABLE `online` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `servername` varchar(100) NOT NULL DEFAULT '',
  `online` int(10) NOT NULL DEFAULT '0',
  `MaxOnline` int(10) NOT NULL DEFAULT '0',
  `WorldOnline` int(10) NOT NULL DEFAULT '0',
  `WorldMaxOnline` int(10) NOT NULL DEFAULT '0',
  `daytime` int(4) NOT NULL DEFAULT '0',
  `gameid` smallint(4) NOT NULL DEFAULT '0',
  `serverid` int(10) unsigned NOT NULL DEFAULT '0',
  `appid` int(10) NOT NULL DEFAULT '10001',
  `remote_id` int(10) NOT NULL DEFAULT '0',
  `created_at` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46516950 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of online
-- ----------------------------

-- ----------------------------
-- Table structure for paylog
-- ----------------------------
DROP TABLE IF EXISTS `paylog`;
CREATE TABLE `paylog` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `money` int(10) NOT NULL,
  `orderid` varchar(100) NOT NULL,
  `is_new` tinyint(1) NOT NULL COMMENT '是否首次支付',
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `is_pay` tinyint(1) DEFAULT '0' COMMENT '默认成功',
  `vip_lev` int(11) DEFAULT NULL COMMENT 'vip等级',
  PRIMARY KEY (`id`),
  UNIQUE KEY `orderid` (`orderid`),
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_account` (`accountid`),
  KEY `time` (`created_at`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=992128 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of paylog
-- ----------------------------

-- ----------------------------
-- Table structure for sum_join_201707
-- ----------------------------
DROP TABLE IF EXISTS `sum_join_201707`;
CREATE TABLE `sum_join_201707` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `act_id` int(11) NOT NULL,
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '0',
  `act_count` int(11) NOT NULL COMMENT '行为次数',
  `act_account` int(11) NOT NULL COMMENT '参与人数',
  `logdate` int(11) NOT NULL COMMENT '记录日期',
  `serverid` int(11) NOT NULL,
  `mysort` int(11) NOT NULL COMMENT '排序',
  `vip_level` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`act_id`,`param`,`logdate`,`serverid`,`vip_level`) USING BTREE,
  KEY `idx_time` (`act_id`,`param`,`logdate`,`serverid`,`vip_level`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1359791 DEFAULT CHARSET=utf8 COMMENT='参与度统计表';

-- ----------------------------
-- Records of sum_join_201707
-- ----------------------------

-- ----------------------------
-- Table structure for type_001_100002
-- ----------------------------
DROP TABLE IF EXISTS `type_001_100002`;
CREATE TABLE `type_001_100002` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `typeid` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `参数字段1` int(10) NOT NULL,
  `参数字段2` char(10) COLLATE utf8_bin NOT NULL,
  `参数字段3` int(10) NOT NULL,
  `参数字段4` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of type_001_100002
-- ----------------------------

-- ----------------------------
-- Table structure for type_007_10002
-- ----------------------------
DROP TABLE IF EXISTS `type_007_10002`;
CREATE TABLE `type_007_10002` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `typeid` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `vip_level` int(10) NOT NULL,
  `client_time` int(10) NOT NULL,
  `shop_type` tinyint(2) NOT NULL COMMENT '商店类型，1：道具商店，2：联盟商店，3：冠军商店，4：环球商店，5：神秘商店，6：友好商店',
  `shop_name` varchar(32) NOT NULL COMMENT '商店名称',
  `buy_item_type` tinyint(2) NOT NULL COMMENT '商品类型，0：物品，6：精灵，18：努力点',
  `buy_item_id` int(10) unsigned NOT NULL COMMENT '购买的商品id',
  `buy_item_num` int(10) NOT NULL COMMENT '购买商品数量',
  `buy_item_name` varchar(32) NOT NULL COMMENT '购买的商品名称',
  `currency_type` tinyint(2) NOT NULL COMMENT '消耗的货币类型,1：金币，2：钻石，3：联盟币，4：冠军币，5：全球币，6：神秘积分，7：努力点，8：精力，9：体力',
  `currency_num` int(10) NOT NULL COMMENT '消耗货币数量',
  `currency_name` varchar(32) NOT NULL COMMENT '消耗的货币名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=510435 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of type_007_10002
-- ----------------------------

-- ----------------------------
-- Table structure for type_008_10002
-- ----------------------------
DROP TABLE IF EXISTS `type_008_10002`;
CREATE TABLE `type_008_10002` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `typeid` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `vip_level` int(10) NOT NULL,
  `client_time` int(10) NOT NULL,
  `exchange_type` tinyint(1) NOT NULL COMMENT '0：初级交换，1：中级交换，2：高级交换',
  `use_fairy_1` int(10) unsigned NOT NULL COMMENT '消耗的精灵1',
  `use_fairy_2` int(10) unsigned NOT NULL COMMENT '消耗的精灵2',
  `use_fairy_3` int(10) unsigned NOT NULL COMMENT '消耗的精灵3',
  `get_fairy` int(10) unsigned NOT NULL COMMENT '获得的精灵',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4082 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of type_008_10002
-- ----------------------------

-- ----------------------------
-- Table structure for type_009_10002
-- ----------------------------
DROP TABLE IF EXISTS `type_009_10002`;
CREATE TABLE `type_009_10002` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `typeid` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `vip_level` int(10) NOT NULL,
  `client_time` int(10) NOT NULL,
  `user_fighting_capacity` int(10) NOT NULL COMMENT '玩家战斗力',
  `instance_id` int(10) unsigned NOT NULL COMMENT '关卡id',
  `instance_type` tinyint(2) NOT NULL COMMENT '1：普通副本，2：精英副本，3：精英挑战',
  `result` tinyint(1) NOT NULL COMMENT '0：通关失败，1：通关成功',
  `fairys_id` varchar(64) NOT NULL COMMENT '上阵精灵id，中间用#隔开，未上阵的用‘-’',
  `fairys_level` varchar(64) NOT NULL COMMENT '上阵精灵等级，中间用#隔开，未上阵的用‘-’',
  `fairys_fighting_capacity` varchar(64) NOT NULL COMMENT '上阵精灵战斗力，中间用#隔开，未上阵的用‘-’',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1819164 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of type_009_10002
-- ----------------------------

-- ----------------------------
-- Table structure for type_010_10002
-- ----------------------------
DROP TABLE IF EXISTS `type_010_10002`;
CREATE TABLE `type_010_10002` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `typeid` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `vip_level` int(10) NOT NULL,
  `client_time` int(10) NOT NULL,
  `shop_type` tinyint(2) NOT NULL COMMENT '商店类型',
  `shop_name` varchar(32) NOT NULL COMMENT '商店名称',
  `currency_type` tinyint(2) NOT NULL COMMENT '消耗的货币类型',
  `currency_num` int(10) NOT NULL COMMENT '消耗货币数量',
  `currency_name` varchar(32) NOT NULL COMMENT '消耗的货币名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34839 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of type_010_10002
-- ----------------------------

-- ----------------------------
-- Table structure for type_011_10002
-- ----------------------------
DROP TABLE IF EXISTS `type_011_10002`;
CREATE TABLE `type_011_10002` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `typeid` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `vip_level` int(10) NOT NULL,
  `client_time` int(10) NOT NULL,
  `battle_type` tinyint(1) NOT NULL COMMENT '1：全球对战，2：联盟对战',
  `result` tinyint(1) NOT NULL COMMENT '0：失败，1胜利',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=307820 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of type_011_10002
-- ----------------------------

-- ----------------------------
-- Table structure for type_012_10002
-- ----------------------------
DROP TABLE IF EXISTS `type_012_10002`;
CREATE TABLE `type_012_10002` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `typeid` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `vip_level` int(10) NOT NULL,
  `client_time` int(10) NOT NULL,
  `buy_type` tinyint(2) NOT NULL COMMENT '1：联盟大赛',
  `buy_num` int(10) NOT NULL COMMENT '购买获得的次数',
  `currency_type` tinyint(2) NOT NULL COMMENT '消耗的货币类型',
  `currency_num` int(10) NOT NULL COMMENT '消耗的货币数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1430 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of type_012_10002
-- ----------------------------

-- ----------------------------
-- Table structure for type_013_10002
-- ----------------------------
DROP TABLE IF EXISTS `type_013_10002`;
CREATE TABLE `type_013_10002` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `typeid` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `vip_level` int(10) NOT NULL,
  `client_time` int(10) NOT NULL,
  `select_fairy_id` int(10) unsigned NOT NULL COMMENT '新手选择的精灵id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=301272 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of type_013_10002
-- ----------------------------

-- ----------------------------
-- Table structure for type_014_10002
-- ----------------------------
DROP TABLE IF EXISTS `type_014_10002`;
CREATE TABLE `type_014_10002` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `typeid` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `vip_level` int(10) NOT NULL,
  `client_time` int(10) NOT NULL,
  `awards_type` varchar(64) NOT NULL COMMENT '奖励类型，多个中间用#隔开',
  `awards_id` varchar(64) NOT NULL COMMENT '奖励id，多个中间用#隔开',
  `awards_num` varchar(64) NOT NULL COMMENT '奖励数量，多个中间用#隔开',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=938626 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of type_014_10002
-- ----------------------------

-- ----------------------------
-- Table structure for type_015_10002
-- ----------------------------
DROP TABLE IF EXISTS `type_015_10002`;
CREATE TABLE `type_015_10002` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `typeid` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `vip_level` int(10) NOT NULL,
  `client_time` int(10) NOT NULL,
  `task_id` int(10) unsigned NOT NULL COMMENT '任务id',
  `add_activity` int(10) NOT NULL COMMENT '增加的活跃度',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1301251 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of type_015_10002
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170701
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170701`;
CREATE TABLE `u_behavior_20170701` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7871364 DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170701
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170702
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170702`;
CREATE TABLE `u_behavior_20170702` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7699517 DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170702
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170703
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170703`;
CREATE TABLE `u_behavior_20170703` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4259725 DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170703
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170704
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170704`;
CREATE TABLE `u_behavior_20170704` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170704
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170705
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170705`;
CREATE TABLE `u_behavior_20170705` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170705
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170706
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170706`;
CREATE TABLE `u_behavior_20170706` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170706
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170707
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170707`;
CREATE TABLE `u_behavior_20170707` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170707
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170708
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170708`;
CREATE TABLE `u_behavior_20170708` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170708
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170709
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170709`;
CREATE TABLE `u_behavior_20170709` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170709
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170710
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170710`;
CREATE TABLE `u_behavior_20170710` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170710
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170711
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170711`;
CREATE TABLE `u_behavior_20170711` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170711
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170712
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170712`;
CREATE TABLE `u_behavior_20170712` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170712
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170713
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170713`;
CREATE TABLE `u_behavior_20170713` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170713
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170714
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170714`;
CREATE TABLE `u_behavior_20170714` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170714
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170715
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170715`;
CREATE TABLE `u_behavior_20170715` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170715
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170716
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170716`;
CREATE TABLE `u_behavior_20170716` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170716
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170717
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170717`;
CREATE TABLE `u_behavior_20170717` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170717
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170718
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170718`;
CREATE TABLE `u_behavior_20170718` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170718
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170719
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170719`;
CREATE TABLE `u_behavior_20170719` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170719
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170720
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170720`;
CREATE TABLE `u_behavior_20170720` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170720
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170721
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170721`;
CREATE TABLE `u_behavior_20170721` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170721
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170722
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170722`;
CREATE TABLE `u_behavior_20170722` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170722
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170723
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170723`;
CREATE TABLE `u_behavior_20170723` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170723
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170724
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170724`;
CREATE TABLE `u_behavior_20170724` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170724
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170725
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170725`;
CREATE TABLE `u_behavior_20170725` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170725
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170726
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170726`;
CREATE TABLE `u_behavior_20170726` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170726
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170727
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170727`;
CREATE TABLE `u_behavior_20170727` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170727
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170728
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170728`;
CREATE TABLE `u_behavior_20170728` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170728
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170729
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170729`;
CREATE TABLE `u_behavior_20170729` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170729
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170730
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170730`;
CREATE TABLE `u_behavior_20170730` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170730
-- ----------------------------

-- ----------------------------
-- Table structure for u_behavior_20170731
-- ----------------------------
DROP TABLE IF EXISTS `u_behavior_20170731`;
CREATE TABLE `u_behavior_20170731` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL DEFAULT '0' COMMENT '用户账号',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT '角色编号',
  `serverid` int(10) NOT NULL DEFAULT '0' COMMENT '所属服务器',
  `channel` int(10) NOT NULL DEFAULT '0' COMMENT '所属渠道',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录时间',
  `vip_level` int(10) NOT NULL DEFAULT '0' COMMENT 'vip等级',
  `act_id` int(10) NOT NULL DEFAULT '0' COMMENT '行为编号',
  `param` bigint(20) NOT NULL DEFAULT '0' COMMENT '具体行为',
  `client_time` int(10) NOT NULL DEFAULT '0' COMMENT '客户端记录时间',
  `user_level` int(10) DEFAULT '0' COMMENT '用户等级',
  `communityid` int(11) NOT NULL DEFAULT '0' COMMENT '社团编号',
  `communitylevel` int(11) NOT NULL DEFAULT '0' COMMENT '社团等级',
  `param1` int(11) NOT NULL DEFAULT '0' COMMENT '子运营活动',
  PRIMARY KEY (`id`),
  KEY `idx_time` (`userid`,`serverid`,`channel`,`created_at`,`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户行为表';

-- ----------------------------
-- Records of u_behavior_20170731
-- ----------------------------

-- ----------------------------
-- Table structure for u_bugreport
-- ----------------------------
DROP TABLE IF EXISTS `u_bugreport`;
CREATE TABLE `u_bugreport` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `client_type` varchar(255) COLLATE utf8_bin NOT NULL,
  `content` varchar(500) COLLATE utf8_bin NOT NULL,
  `appid` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `serverid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34918 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of u_bugreport
-- ----------------------------

-- ----------------------------
-- Table structure for u_common_currency
-- ----------------------------
DROP TABLE IF EXISTS `u_common_currency`;
CREATE TABLE `u_common_currency` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL DEFAULT '0',
  `channel` int(10) NOT NULL DEFAULT '0',
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `lev` smallint(4) NOT NULL COMMENT '玩家等级',
  `viplev` smallint(4) NOT NULL COMMENT 'vip等级',
  `created_at` int(10) NOT NULL COMMENT '服务器记录时间',
  `client_time` int(10) NOT NULL COMMENT '接口请求时间',
  `log_date` int(10) NOT NULL COMMENT '记录日期Ymd',
  `create_time` int(10) NOT NULL COMMENT '玩家注册时间',
  `item_type` int(10) unsigned NOT NULL COMMENT '物品类型',
  `daction` tinyint(1) NOT NULL COMMENT '消耗类型 1获得，2消耗',
  `amount` int(10) NOT NULL COMMENT '货币/道具数量',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_account_date` (`accountid`,`userid`,`log_date`,`daction`,`item_type`),
  KEY `idx_date` (`log_date`)
) ENGINE=InnoDB AUTO_INCREMENT=273249797 DEFAULT CHARSET=utf8mb4 COMMENT='通用货币获取消耗';

-- ----------------------------
-- Records of u_common_currency
-- ----------------------------

-- ----------------------------
-- Table structure for u_copy_progress
-- ----------------------------
DROP TABLE IF EXISTS `u_copy_progress`;
CREATE TABLE `u_copy_progress` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `accountid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `type` int(10) NOT NULL,
  `copy_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(20) COLLATE utf8_bin NOT NULL,
  `is_success` tinyint(1) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_copy_id` (`copy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15373 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of u_copy_progress
-- ----------------------------

-- ----------------------------
-- Table structure for u_daily_actions
-- ----------------------------
DROP TABLE IF EXISTS `u_daily_actions`;
CREATE TABLE `u_daily_actions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `accountid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `action_type` smallint(4) NOT NULL,
  `use_time` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_account` (`accountid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of u_daily_actions
-- ----------------------------

-- ----------------------------
-- Table structure for u_dayonline
-- ----------------------------
DROP TABLE IF EXISTS `u_dayonline`;
CREATE TABLE `u_dayonline` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `online` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `accountid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `total_rmb` int(10) NOT NULL,
  `online_date` int(10) NOT NULL,
  `create_time` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `client_time` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_accountid` (`accountid`,`serverid`,`online_date`),
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_created` (`online_date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22712499 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of u_dayonline
-- ----------------------------

-- ----------------------------
-- Table structure for u_develop
-- ----------------------------
DROP TABLE IF EXISTS `u_develop`;
CREATE TABLE `u_develop` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `accountid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `type` int(10) NOT NULL,
  `title` varchar(20) COLLATE utf8_bin NOT NULL,
  `progress` int(10) NOT NULL,
  `equip_id` varchar(20) COLLATE utf8_bin NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_account` (`accountid`)
) ENGINE=InnoDB AUTO_INCREMENT=2488 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of u_develop
-- ----------------------------

-- ----------------------------
-- Table structure for u_device_active
-- ----------------------------
DROP TABLE IF EXISTS `u_device_active`;
CREATE TABLE `u_device_active` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `client_type` varchar(255) COLLATE utf8_bin NOT NULL,
  `client_version` varchar(255) COLLATE utf8_bin NOT NULL,
  `mac` varchar(255) COLLATE utf8_bin NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sv_cnl` (`serverid`,`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=8413800 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of u_device_active
-- ----------------------------

-- ----------------------------
-- Table structure for u_device_unique
-- ----------------------------
DROP TABLE IF EXISTS `u_device_unique`;
CREATE TABLE `u_device_unique` (
  `id` bigint(20) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `client_type` varchar(255) COLLATE utf8_bin NOT NULL,
  `client_version` varchar(255) COLLATE utf8_bin NOT NULL,
  `mac` varchar(255) COLLATE utf8_bin NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  UNIQUE KEY `idx_mac` (`mac`),
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_time` (`created_at`) USING BTREE,
  KEY `idx_type` (`client_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of u_device_unique
-- ----------------------------

-- ----------------------------
-- Table structure for u_elf_starlev
-- ----------------------------
DROP TABLE IF EXISTS `u_elf_starlev`;
CREATE TABLE `u_elf_starlev` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL DEFAULT '0',
  `channel` int(10) NOT NULL DEFAULT '0',
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `lev` smallint(4) NOT NULL COMMENT '玩家等级',
  `viplev` smallint(4) NOT NULL COMMENT 'vip等级',
  `created_at` int(10) NOT NULL COMMENT '服务器记录时间',
  `client_time` int(10) NOT NULL COMMENT '接口请求时间',
  `log_date` int(10) NOT NULL COMMENT '记录日期Ymd',
  `create_time` int(10) NOT NULL COMMENT '玩家注册时间',
  `elf_1` smallint(4) NOT NULL COMMENT '第1高星精灵战斗力数值',
  `elf_2` smallint(4) NOT NULL COMMENT '第2高星精灵战斗力数值',
  `elf_3` smallint(4) NOT NULL COMMENT '第3高星精灵战斗力数值',
  `elf_4` smallint(4) NOT NULL COMMENT '第4高星精灵战斗力数值',
  `elf_5` smallint(4) NOT NULL COMMENT '第5高星精灵战斗力数值',
  `elf_6` smallint(4) NOT NULL COMMENT '第6高星精灵战斗力数值',
  `elf_7` smallint(4) NOT NULL COMMENT '第7高星精灵战斗力数值',
  `fighting` int(10) NOT NULL COMMENT '战斗力',
  `nomal_copy` int(10) NOT NULL COMMENT '普通副本进度',
  `nomal_elite` int(10) NOT NULL COMMENT '精英副本进度',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_account_date` (`accountid`,`userid`,`log_date`)
) ENGINE=InnoDB AUTO_INCREMENT=197478 DEFAULT CHARSET=utf8mb4 COMMENT='精灵星级&关卡统计';

-- ----------------------------
-- Records of u_elf_starlev
-- ----------------------------

-- ----------------------------
-- Table structure for u_eudemon
-- ----------------------------
DROP TABLE IF EXISTS `u_eudemon`;
CREATE TABLE `u_eudemon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eudemon` bigint(20) NOT NULL COMMENT '精灵编号',
  `serverid` int(11) NOT NULL,
  `logdate` int(11) NOT NULL,
  `num` bigint(20) NOT NULL COMMENT '玩家数量',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`eudemon`,`serverid`,`logdate`) USING BTREE,
  KEY `idx_date` (`eudemon`,`serverid`,`logdate`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=53564 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_eudemon
-- ----------------------------

-- ----------------------------
-- Table structure for u_game_process_201707
-- ----------------------------
DROP TABLE IF EXISTS `u_game_process_201707`;
CREATE TABLE `u_game_process_201707` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL,
  `server_name` varchar(20) NOT NULL DEFAULT '',
  `userid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `vip_level` int(10) NOT NULL,
  `client_time` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL DEFAULT '',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  `user_lev` int(10) NOT NULL,
  `process_index` int(10) NOT NULL COMMENT '事件id',
  `process_result` tinyint(1) NOT NULL COMMENT '事件结果',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_account_server` (`accountid`,`serverid`,`process_index`)
) ENGINE=InnoDB AUTO_INCREMENT=1898565 DEFAULT CHARSET=utf8 COMMENT='游戏流程统计';

-- ----------------------------
-- Records of u_game_process_201707
-- ----------------------------

-- ----------------------------
-- Table structure for u_give_emoney
-- ----------------------------
DROP TABLE IF EXISTS `u_give_emoney`;
CREATE TABLE `u_give_emoney` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `emoney` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `item_type` varchar(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_created` (`created_at`),
  KEY `idx_sv_cnl` (`serverid`,`channel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_give_emoney
-- ----------------------------

-- ----------------------------
-- Table structure for u_last_login
-- ----------------------------
DROP TABLE IF EXISTS `u_last_login`;
CREATE TABLE `u_last_login` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `userid` int(11) NOT NULL COMMENT '角色编号',
  `accountid` int(11) NOT NULL COMMENT '账号',
  `serverid` int(11) NOT NULL COMMENT '区服',
  `channel` int(11) NOT NULL COMMENT '渠道',
  `viplev` int(11) NOT NULL COMMENT 'vip等级',
  `lev` int(11) NOT NULL COMMENT '角色等级',
  `last_login_time` int(11) NOT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(255) DEFAULT NULL,
  `last_login_mac` varchar(255) DEFAULT NULL COMMENT '最后登录mac',
  `appid` int(11) DEFAULT NULL COMMENT '应用编号',
  `client_type` varchar(255) DEFAULT NULL COMMENT '手机类型',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`userid`,`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_id` (`userid`,`accountid`,`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4233186 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_last_login
-- ----------------------------

-- ----------------------------
-- Table structure for u_level_difficulty
-- ----------------------------
DROP TABLE IF EXISTS `u_level_difficulty`;
CREATE TABLE `u_level_difficulty` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL DEFAULT '0',
  `channel` int(10) NOT NULL DEFAULT '0',
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `lev` smallint(4) NOT NULL COMMENT '玩家等级',
  `viplev` smallint(4) NOT NULL COMMENT 'vip等级',
  `created_at` int(10) NOT NULL COMMENT '服务器记录时间',
  `client_time` int(10) NOT NULL COMMENT '接口请求时间',
  `log_date` int(10) NOT NULL COMMENT '记录日期Ymd',
  `create_time` int(10) NOT NULL COMMENT '玩家注册时间',
  `level_id` int(10) NOT NULL COMMENT '关卡ID',
  `copy_type` int(10) NOT NULL COMMENT '副本类型',
  `is_first_pass` tinyint(1) NOT NULL COMMENT '是否首次通过0否1是',
  `success_times` smallint(4) NOT NULL COMMENT '成功次数,累加',
  `failure_times` smallint(4) NOT NULL COMMENT '失败次数,累加',
  `total_times` smallint(4) NOT NULL COMMENT '总次数,累加',
  `star` tinyint(1) NOT NULL COMMENT '获得星级数',
  `fighting` int(10) NOT NULL COMMENT '战斗力',
  `total_lev` smallint(4) NOT NULL COMMENT '总的等级',
  `total_fighting` int(10) NOT NULL COMMENT '总的战斗力',
  `avg_fighting` int(10) NOT NULL COMMENT '平均战斗力',
  `avg_lev` int(10) NOT NULL COMMENT '平均等级',
  `max_star` tinyint(1) NOT NULL COMMENT '最高星级数',
  `max_star_times` smallint(4) NOT NULL COMMENT '挑战至三星的次数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_account_date` (`accountid`,`userid`,`log_date`,`level_id`),
  KEY `idx_log_date` (`log_date`),
  KEY `idx_level_id` (`level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1195027 DEFAULT CHARSET=utf8mb4 COMMENT='关卡难易程度统计';

-- ----------------------------
-- Records of u_level_difficulty
-- ----------------------------

-- ----------------------------
-- Table structure for u_level_process
-- ----------------------------
DROP TABLE IF EXISTS `u_level_process`;
CREATE TABLE `u_level_process` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `accountid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `level_type` int(10) NOT NULL,
  `level_id` int(10) NOT NULL,
  `highest_level` varchar(100) COLLATE utf8_bin NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_account` (`accountid`)
) ENGINE=InnoDB AUTO_INCREMENT=4726 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of u_level_process
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170701
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170701`;
CREATE TABLE `u_login_20170701` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=591725 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170701
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170702
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170702`;
CREATE TABLE `u_login_20170702` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=565646 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170702
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170703
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170703`;
CREATE TABLE `u_login_20170703` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=289646 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170703
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170704
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170704`;
CREATE TABLE `u_login_20170704` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170704
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170705
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170705`;
CREATE TABLE `u_login_20170705` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170705
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170706
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170706`;
CREATE TABLE `u_login_20170706` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170706
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170707
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170707`;
CREATE TABLE `u_login_20170707` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170707
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170708
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170708`;
CREATE TABLE `u_login_20170708` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170708
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170709
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170709`;
CREATE TABLE `u_login_20170709` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170709
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170710
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170710`;
CREATE TABLE `u_login_20170710` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170710
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170711
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170711`;
CREATE TABLE `u_login_20170711` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170711
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170712
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170712`;
CREATE TABLE `u_login_20170712` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170712
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170713
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170713`;
CREATE TABLE `u_login_20170713` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170713
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170714
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170714`;
CREATE TABLE `u_login_20170714` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170714
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170715
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170715`;
CREATE TABLE `u_login_20170715` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170715
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170716
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170716`;
CREATE TABLE `u_login_20170716` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170716
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170717
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170717`;
CREATE TABLE `u_login_20170717` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170717
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170718
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170718`;
CREATE TABLE `u_login_20170718` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170718
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170719
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170719`;
CREATE TABLE `u_login_20170719` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170719
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170720
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170720`;
CREATE TABLE `u_login_20170720` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170720
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170721
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170721`;
CREATE TABLE `u_login_20170721` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170721
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170722
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170722`;
CREATE TABLE `u_login_20170722` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170722
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170723
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170723`;
CREATE TABLE `u_login_20170723` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170723
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170724
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170724`;
CREATE TABLE `u_login_20170724` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170724
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170725
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170725`;
CREATE TABLE `u_login_20170725` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170725
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170726
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170726`;
CREATE TABLE `u_login_20170726` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170726
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170727
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170727`;
CREATE TABLE `u_login_20170727` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170727
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170728
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170728`;
CREATE TABLE `u_login_20170728` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170728
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170729
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170729`;
CREATE TABLE `u_login_20170729` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170729
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170730
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170730`;
CREATE TABLE `u_login_20170730` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170730
-- ----------------------------

-- ----------------------------
-- Table structure for u_login_20170731
-- ----------------------------
DROP TABLE IF EXISTS `u_login_20170731`;
CREATE TABLE `u_login_20170731` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_login` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_login_20170731
-- ----------------------------

-- ----------------------------
-- Table structure for u_logout
-- ----------------------------
DROP TABLE IF EXISTS `u_logout`;
CREATE TABLE `u_logout` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL DEFAULT '0',
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `online` int(10) NOT NULL COMMENT '本次在线时长',
  `viplev` int(10) NOT NULL COMMENT '充值成功前的vip等级',
  `trainer_lev` int(10) NOT NULL COMMENT '训练师等级',
  `created_at` int(10) NOT NULL,
  `client_timestamp` int(10) NOT NULL COMMENT '发送时间',
  PRIMARY KEY (`id`),
  KEY `idx_account` (`accountid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='登出';

-- ----------------------------
-- Records of u_logout
-- ----------------------------

-- ----------------------------
-- Table structure for u_open_game_channel
-- ----------------------------
DROP TABLE IF EXISTS `u_open_game_channel`;
CREATE TABLE `u_open_game_channel` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `mac` varchar(255) COLLATE utf8_bin NOT NULL,
  `created_at` int(10) NOT NULL,
  `client_timestamp` int(10) NOT NULL COMMENT '服务端发送时间',
  PRIMARY KEY (`id`),
  KEY `idx_channel` (`channel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of u_open_game_channel
-- ----------------------------

-- ----------------------------
-- Table structure for u_paylog
-- ----------------------------
DROP TABLE IF EXISTS `u_paylog`;
CREATE TABLE `u_paylog` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `accountid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `money` int(10) NOT NULL,
  `orderid` varchar(100) NOT NULL,
  `is_new` tinyint(1) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `is_pay` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `orderid` (`orderid`),
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_account` (`accountid`),
  KEY `time` (`created_at`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1699344 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_paylog
-- ----------------------------

-- ----------------------------
-- Table structure for u_photo_level
-- ----------------------------
DROP TABLE IF EXISTS `u_photo_level`;
CREATE TABLE `u_photo_level` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL DEFAULT '0',
  `channel` int(10) NOT NULL DEFAULT '0',
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `lev` smallint(4) NOT NULL COMMENT '玩家等级',
  `viplev` smallint(4) NOT NULL COMMENT 'vip等级',
  `created_at` int(10) NOT NULL COMMENT '服务器记录时间',
  `client_time` int(10) NOT NULL COMMENT '接口请求时间',
  `log_date` int(10) NOT NULL COMMENT '记录日期Ymd',
  `create_time` int(10) NOT NULL COMMENT '玩家注册时间',
  `pht_1` smallint(4) NOT NULL COMMENT '第1高图鉴等级',
  `pht_2` smallint(4) NOT NULL COMMENT '第2高图鉴等级',
  `pht_3` smallint(4) NOT NULL COMMENT '第3高图鉴等级',
  `pht_4` smallint(4) NOT NULL COMMENT '第4高图鉴等级',
  `pht_5` smallint(4) NOT NULL COMMENT '第5高图鉴等级',
  `pht_6` smallint(4) NOT NULL COMMENT '第6高图鉴等级',
  `pht_7` smallint(4) NOT NULL COMMENT '第7高图鉴等级',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_account_date` (`accountid`,`userid`,`log_date`)
) ENGINE=InnoDB AUTO_INCREMENT=119972 DEFAULT CHARSET=utf8mb4 COMMENT='图鉴等级';

-- ----------------------------
-- Records of u_photo_level
-- ----------------------------

-- ----------------------------
-- Table structure for u_player_active
-- ----------------------------
DROP TABLE IF EXISTS `u_player_active`;
CREATE TABLE `u_player_active` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL DEFAULT '0',
  `channel` int(10) NOT NULL DEFAULT '0',
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `lev` smallint(4) NOT NULL COMMENT '玩家等级',
  `viplev` smallint(4) NOT NULL COMMENT 'vip等级',
  `created_at` int(10) NOT NULL COMMENT '服务器记录时间',
  `client_time` int(10) NOT NULL COMMENT '接口请求时间',
  `log_date` int(10) NOT NULL COMMENT '记录日期Ymd',
  `create_time` int(10) NOT NULL COMMENT '玩家注册时间',
  `active` smallint(4) unsigned NOT NULL COMMENT '活跃值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_account_date` (`accountid`,`userid`,`log_date`),
  KEY `idx_date` (`log_date`)
) ENGINE=InnoDB AUTO_INCREMENT=15964670 DEFAULT CHARSET=utf8mb4 COMMENT='玩家活跃';

-- ----------------------------
-- Records of u_player_active
-- ----------------------------

-- ----------------------------
-- Table structure for u_players
-- ----------------------------
DROP TABLE IF EXISTS `u_players`;
CREATE TABLE `u_players` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `userid` int(10) NOT NULL,
  `accountid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `created_at` int(10) NOT NULL,
  `first_login_at` int(10) NOT NULL DEFAULT '0',
  `first_login_ip` varchar(255) NOT NULL DEFAULT '0',
  `first_login_mac` varchar(255) NOT NULL DEFAULT '0',
  `updated_at` int(10) unsigned NOT NULL COMMENT '更新时间',
  `appid` int(10) NOT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `role_create_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_account` (`accountid`,`serverid`,`appid`),
  KEY `idx_account` (`accountid`)
) ENGINE=InnoDB AUTO_INCREMENT=3587508 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of u_players
-- ----------------------------

-- ----------------------------
-- Table structure for u_playing_method
-- ----------------------------
DROP TABLE IF EXISTS `u_playing_method`;
CREATE TABLE `u_playing_method` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL DEFAULT '0',
  `channel` int(10) NOT NULL DEFAULT '0',
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `lev` smallint(4) NOT NULL COMMENT '玩家等级',
  `viplev` smallint(4) NOT NULL COMMENT 'vip等级',
  `created_at` int(10) NOT NULL COMMENT '服务器记录时间',
  `client_time` int(10) NOT NULL COMMENT '接口请求时间',
  `log_date` int(10) NOT NULL COMMENT '记录日期Ymd',
  `create_time` int(10) NOT NULL COMMENT '玩家注册时间',
  `method` smallint(4) NOT NULL COMMENT '玩法类型',
  `playing_times` int(10) NOT NULL COMMENT '次数，累加',
  `playing_time` int(10) NOT NULL COMMENT '改成战斗的回合数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_account_date` (`accountid`,`userid`,`log_date`,`method`),
  KEY `idx_date` (`log_date`)
) ENGINE=InnoDB AUTO_INCREMENT=424800 DEFAULT CHARSET=utf8mb4 COMMENT='玩法次数统计';

-- ----------------------------
-- Records of u_playing_method
-- ----------------------------

-- ----------------------------
-- Table structure for u_props
-- ----------------------------
DROP TABLE IF EXISTS `u_props`;
CREATE TABLE `u_props` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `accountid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `prop_type` int(10) NOT NULL,
  `prop_id` varchar(20) COLLATE utf8_bin NOT NULL,
  `prop_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `amounts` int(10) NOT NULL,
  `gain_way` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_account` (`accountid`)
) ENGINE=InnoDB AUTO_INCREMENT=41597 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of u_props
-- ----------------------------

-- ----------------------------
-- Table structure for u_props_used
-- ----------------------------
DROP TABLE IF EXISTS `u_props_used`;
CREATE TABLE `u_props_used` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `accountid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `prop_type` int(10) NOT NULL,
  `prop_id` varchar(20) COLLATE utf8_bin NOT NULL,
  `prop_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `amounts` int(10) NOT NULL,
  `gain_way` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_account` (`accountid`)
) ENGINE=InnoDB AUTO_INCREMENT=17038 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of u_props_used
-- ----------------------------

-- ----------------------------
-- Table structure for u_recharge
-- ----------------------------
DROP TABLE IF EXISTS `u_recharge`;
CREATE TABLE `u_recharge` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) NOT NULL DEFAULT '0',
  `serverid` int(10) NOT NULL DEFAULT '0',
  `accountid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `rmb` int(10) NOT NULL COMMENT '充值金额单位元',
  `viplev` int(10) NOT NULL COMMENT '充值成功前的vip等级',
  `trainer_lev` int(10) NOT NULL COMMENT '训练师等级',
  `created_at` int(10) NOT NULL,
  `client_timestamp` int(10) NOT NULL COMMENT '发送时间',
  PRIMARY KEY (`id`),
  KEY `idx_account` (`accountid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='充值成功事件';

-- ----------------------------
-- Records of u_recharge
-- ----------------------------

-- ----------------------------
-- Table structure for u_register
-- ----------------------------
DROP TABLE IF EXISTS `u_register`;
CREATE TABLE `u_register` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `client_version` varchar(255) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `regway` tinyint(1) NOT NULL COMMENT '注册方式：1游客登录，2手机登录，3邮箱登录',
  `reg_date` int(10) NOT NULL DEFAULT '0' COMMENT '注册日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_account_server` (`accountid`,`serverid`,`appid`),
  KEY `idx_created` (`created_at`),
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_date` (`reg_date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7390139 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of u_register
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170701
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170701`;
CREATE TABLE `u_register_process_20170701` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB AUTO_INCREMENT=425385 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170701
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170702
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170702`;
CREATE TABLE `u_register_process_20170702` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB AUTO_INCREMENT=418006 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170702
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170703
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170703`;
CREATE TABLE `u_register_process_20170703` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB AUTO_INCREMENT=190197 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170703
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170704
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170704`;
CREATE TABLE `u_register_process_20170704` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170704
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170705
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170705`;
CREATE TABLE `u_register_process_20170705` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170705
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170706
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170706`;
CREATE TABLE `u_register_process_20170706` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170706
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170707
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170707`;
CREATE TABLE `u_register_process_20170707` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170707
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170708
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170708`;
CREATE TABLE `u_register_process_20170708` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170708
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170709
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170709`;
CREATE TABLE `u_register_process_20170709` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170709
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170710
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170710`;
CREATE TABLE `u_register_process_20170710` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170710
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170711
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170711`;
CREATE TABLE `u_register_process_20170711` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170711
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170712
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170712`;
CREATE TABLE `u_register_process_20170712` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170712
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170713
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170713`;
CREATE TABLE `u_register_process_20170713` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170713
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170714
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170714`;
CREATE TABLE `u_register_process_20170714` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170714
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170715
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170715`;
CREATE TABLE `u_register_process_20170715` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170715
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170716
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170716`;
CREATE TABLE `u_register_process_20170716` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170716
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170717
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170717`;
CREATE TABLE `u_register_process_20170717` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170717
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170718
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170718`;
CREATE TABLE `u_register_process_20170718` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170718
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170719
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170719`;
CREATE TABLE `u_register_process_20170719` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170719
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170720
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170720`;
CREATE TABLE `u_register_process_20170720` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170720
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170721
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170721`;
CREATE TABLE `u_register_process_20170721` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170721
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170722
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170722`;
CREATE TABLE `u_register_process_20170722` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170722
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170723
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170723`;
CREATE TABLE `u_register_process_20170723` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170723
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170724
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170724`;
CREATE TABLE `u_register_process_20170724` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170724
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170725
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170725`;
CREATE TABLE `u_register_process_20170725` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170725
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170726
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170726`;
CREATE TABLE `u_register_process_20170726` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170726
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170727
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170727`;
CREATE TABLE `u_register_process_20170727` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170727
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170728
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170728`;
CREATE TABLE `u_register_process_20170728` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170728
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170729
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170729`;
CREATE TABLE `u_register_process_20170729` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170729
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170730
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170730`;
CREATE TABLE `u_register_process_20170730` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170730
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_20170731
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_20170731`;
CREATE TABLE `u_register_process_20170731` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_20170731
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_history
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_history`;
CREATE TABLE `u_register_process_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=InnoDB AUTO_INCREMENT=26312331 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_history
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_process_new
-- ----------------------------
DROP TABLE IF EXISTS `u_register_process_new`;
CREATE TABLE `u_register_process_new` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_appid_mac` (`appid`,`mac`)
) ENGINE=MyISAM AUTO_INCREMENT=62597792 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_process_new
-- ----------------------------

-- ----------------------------
-- Table structure for u_register_tmp
-- ----------------------------
DROP TABLE IF EXISTS `u_register_tmp`;
CREATE TABLE `u_register_tmp` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `client_version` varchar(255) NOT NULL,
  `ip` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `regway` tinyint(1) NOT NULL COMMENT '注册方式：1游客登录，2手机登录，3邮箱登录',
  `reg_date` int(10) NOT NULL DEFAULT '0' COMMENT '注册日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_account_server` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_created` (`created_at`),
  KEY `idx_sv_cnl` (`serverid`,`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=386476 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_register_tmp
-- ----------------------------

-- ----------------------------
-- Table structure for u_rmb
-- ----------------------------
DROP TABLE IF EXISTS `u_rmb`;
CREATE TABLE `u_rmb` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `accountid` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `emoney` int(10) NOT NULL,
  `type` int(10) NOT NULL,
  `itemtype` varchar(20) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_created` (`created_at`),
  KEY `idx_sv_cnl` (`serverid`,`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=861 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_rmb
-- ----------------------------

-- ----------------------------
-- Table structure for u_roles
-- ----------------------------
DROP TABLE IF EXISTS `u_roles`;
CREATE TABLE `u_roles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `userid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `role_create_time` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_account_server` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_account` (`accountid`),
  KEY `idx_sv_cnl` (`serverid`,`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=7510533 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_roles
-- ----------------------------

-- ----------------------------
-- Table structure for u_server_emoney
-- ----------------------------
DROP TABLE IF EXISTS `u_server_emoney`;
CREATE TABLE `u_server_emoney` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serverid` int(11) NOT NULL,
  `emoney` bigint(20) NOT NULL,
  `logdate` int(11) NOT NULL,
  `money` bigint(20) NOT NULL COMMENT '剩余金币',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`serverid`,`logdate`) USING BTREE,
  KEY `idx_time` (`serverid`,`logdate`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=36472 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_server_emoney
-- ----------------------------

-- ----------------------------
-- Table structure for u_server_emoney_active
-- ----------------------------
DROP TABLE IF EXISTS `u_server_emoney_active`;
CREATE TABLE `u_server_emoney_active` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serverid` int(11) NOT NULL,
  `emoney` bigint(20) NOT NULL,
  `logdate` int(11) NOT NULL,
  `money` bigint(20) NOT NULL COMMENT '剩余金币',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`serverid`,`logdate`) USING BTREE,
  KEY `idx_time` (`serverid`,`logdate`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=8379 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_server_emoney_active
-- ----------------------------

-- ----------------------------
-- Table structure for u_server_emoney_vip
-- ----------------------------
DROP TABLE IF EXISTS `u_server_emoney_vip`;
CREATE TABLE `u_server_emoney_vip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serverid` int(11) NOT NULL,
  `emoney` bigint(20) NOT NULL,
  `logdate` int(11) NOT NULL,
  `money` bigint(20) NOT NULL COMMENT '剩余金币',
  `caccount` bigint(20) NOT NULL,
  `vip_level` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uni` (`serverid`,`logdate`,`vip_level`) USING BTREE,
  KEY `idx_time` (`serverid`,`logdate`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=55111 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of u_server_emoney_vip
-- ----------------------------

-- ----------------------------
-- Table structure for u_server_online
-- ----------------------------
DROP TABLE IF EXISTS `u_server_online`;
CREATE TABLE `u_server_online` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) NOT NULL,
  `online` int(10) NOT NULL,
  `max_online` int(10) NOT NULL,
  `world_online` int(10) NOT NULL,
  `max_world_online` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of u_server_online
-- ----------------------------

-- ----------------------------
-- Table structure for u_success_process
-- ----------------------------
DROP TABLE IF EXISTS `u_success_process`;
CREATE TABLE `u_success_process` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `accountid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `success_type` int(10) NOT NULL,
  `success_id` int(10) unsigned NOT NULL,
  `highest_success` varchar(100) COLLATE utf8_bin NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_account` (`accountid`)
) ENGINE=InnoDB AUTO_INCREMENT=584 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of u_success_process
-- ----------------------------

-- ----------------------------
-- Table structure for u_upgrade_process
-- ----------------------------
DROP TABLE IF EXISTS `u_upgrade_process`;
CREATE TABLE `u_upgrade_process` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL,
  `accountid` int(10) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `upgrade_time` int(10) NOT NULL,
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sv_cnl` (`serverid`,`channel`),
  KEY `idx_account` (`accountid`)
) ENGINE=InnoDB AUTO_INCREMENT=910 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of u_upgrade_process
-- ----------------------------

-- ----------------------------
-- Table structure for warninfo
-- ----------------------------
DROP TABLE IF EXISTS `warninfo`;
CREATE TABLE `warninfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `info` varchar(255) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5724 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of warninfo
-- ----------------------------
