/*
Navicat MySQL Data Transfer

Source Server         : localhost-V5
Source Server Version : 50538
Source Host           : localhost:3306
Source Database       : u591

Target Server Type    : MYSQL
Target Server Version : 50538
File Encoding         : 65001

Date: 2016-10-27 21:33:23
*/

SET FOREIGN_KEY_CHECKS=0;





-- ----------------------------
-- Table structure for emoney_type
-- ----------------------------
DROP TABLE IF EXISTS `emoney_type`;
CREATE TABLE `emoney_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT '0',
  `type_name` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=178 DEFAULT CHARSET=gbk;



-- ----------------------------
-- Table structure for servers_group
-- ----------------------------
DROP TABLE IF EXISTS `servers_group`;
CREATE TABLE `servers_group` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Table structure for servers_list
-- ----------------------------
DROP TABLE IF EXISTS `servers_list`;
CREATE TABLE `servers_list` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `groupid` tinyint(1) NOT NULL DEFAULT '0',
  `sid` smallint(4) NOT NULL DEFAULT '0',
  `serverid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `servername` varchar(32) NOT NULL DEFAULT '',
  `opentime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=310 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sum_behavior_pay
-- ----------------------------
DROP TABLE IF EXISTS `sum_behavior_pay`;
CREATE TABLE `sum_behavior_pay` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` smallint(4) unsigned NOT NULL,
  `fenbaoid` smallint(4) unsigned NOT NULL,
  `sday` int(10) unsigned NOT NULL,
  `daytime` int(10) unsigned DEFAULT NULL,
  `sum_emoney` int(10) unsigned NOT NULL,
  `stype` int(10) unsigned NOT NULL,
  `ratio` decimal(8,4) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_day` (`sday`) USING BTREE,
  KEY `idx_serverid_fenbaoid` (`serverid`,`fenbaoid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5475781 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Table structure for sum_daily_archive
-- ----------------------------
DROP TABLE IF EXISTS `sum_daily_archive`;
CREATE TABLE `sum_daily_archive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` tinyint(1) NOT NULL DEFAULT '0',
  `serverid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `fenbaoid` int(10) unsigned NOT NULL DEFAULT '0',
  `login_cnt` int(10) NOT NULL DEFAULT '0',
  `nl_cnt` int(10) NOT NULL DEFAULT '0',
  `role_cnt` int(10) NOT NULL DEFAULT '0',
  `role_rate` decimal(8,4) DEFAULT NULL,
  `au_cnt` int(10) NOT NULL DEFAULT '0',
  `dau` int(10) NOT NULL DEFAULT '0',
  `wau` int(10) NOT NULL DEFAULT '0',
  `mau` int(10) NOT NULL DEFAULT '0',
  `income_cnt` int(10) NOT NULL DEFAULT '0',
  `pay_nop` int(10) NOT NULL DEFAULT '0',
  `pay_nop_n` int(10) NOT NULL DEFAULT '0',
  `pay_nop_nm` int(10) unsigned NOT NULL DEFAULT '0',
  `pay_cnt` int(10) NOT NULL DEFAULT '0',
  `pay_rate` decimal(8,4) DEFAULT NULL,
  `reg_cnt` int(10) NOT NULL DEFAULT '0',
  `arpu` decimal(8,4) DEFAULT NULL,
  `arpdau` decimal(8,4) DEFAULT NULL,
  `reg_arpu` decimal(8,4) DEFAULT NULL,
  `sday` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_sday_serverid_fenbaoid` (`sday`,`serverid`,`fenbaoid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2289669 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sum_daily_archive_all
-- ----------------------------
DROP TABLE IF EXISTS `sum_daily_archive_all`;
CREATE TABLE `sum_daily_archive_all` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` tinyint(1) NOT NULL DEFAULT '0',
  `login_cnt` int(10) NOT NULL DEFAULT '0',
  `nl_cnt` int(10) NOT NULL DEFAULT '0',
  `role_cnt` int(10) NOT NULL DEFAULT '0',
  `role_rate` decimal(8,4) DEFAULT NULL,
  `au_cnt` int(10) NOT NULL DEFAULT '0',
  `dau` int(10) NOT NULL DEFAULT '0',
  `wau` int(10) NOT NULL DEFAULT '0',
  `mau` int(10) NOT NULL DEFAULT '0',
  `income_cnt` int(10) NOT NULL DEFAULT '0',
  `pay_nop` int(10) NOT NULL DEFAULT '0',
  `pay_nop_n` int(10) NOT NULL DEFAULT '0',
  `pay_nop_nm` int(10) unsigned DEFAULT '0',
  `pay_cnt` int(10) NOT NULL DEFAULT '0',
  `pay_rate` decimal(8,4) DEFAULT NULL,
  `reg_cnt` int(10) NOT NULL DEFAULT '0',
  `arpu` decimal(8,4) DEFAULT NULL,
  `arpdau` decimal(8,4) DEFAULT NULL,
  `reg_arpu` decimal(8,4) DEFAULT NULL,
  `sday` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_sday` (`sday`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=846 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sum_give_emoney
-- ----------------------------
DROP TABLE IF EXISTS `sum_give_emoney`;
CREATE TABLE `sum_give_emoney` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cnt` int(10) unsigned NOT NULL,
  `sum_emoney` int(10) unsigned NOT NULL,
  `stype` int(10) unsigned NOT NULL,
  `serverid` smallint(4) unsigned NOT NULL,
  `fenbaoid` smallint(4) unsigned NOT NULL,
  `sday` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sday` (`sday`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=19040083 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sum_login_daily_detail
-- ----------------------------
DROP TABLE IF EXISTS `sum_login_daily_detail`;
CREATE TABLE `sum_login_daily_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) unsigned NOT NULL,
  `gameid` smallint(4) unsigned NOT NULL,
  `fenbaoid` int(10) unsigned NOT NULL,
  `clienttype` varchar(32) NOT NULL DEFAULT '',
  `sday` int(10) NOT NULL,
  `cnt` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `logindate` (`sday`,`serverid`,`gameid`,`clienttype`,`fenbaoid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2264867 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sum_market_pay
-- ----------------------------
DROP TABLE IF EXISTS `sum_market_pay`;
CREATE TABLE `sum_market_pay` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` smallint(4) unsigned NOT NULL,
  `fenbaoid` smallint(4) unsigned NOT NULL,
  `sday` int(10) unsigned NOT NULL,
  `daytime` int(10) unsigned DEFAULT NULL,
  `sum_emoney` int(10) unsigned NOT NULL,
  `itemtype` int(10) unsigned NOT NULL,
  `ratio` decimal(8,4) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_day` (`sday`) USING BTREE,
  KEY `idx_serverid_fenbaoid` (`serverid`,`fenbaoid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=650386 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Table structure for sum_month_pay
-- ----------------------------
DROP TABLE IF EXISTS `sum_month_pay`;
CREATE TABLE `sum_month_pay` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `server_id` smallint(4) unsigned NOT NULL,
  `total_pay` int(10) NOT NULL,
  `total_money` int(10) NOT NULL,
  `first_pay` int(10) NOT NULL,
  `first_money` int(10) NOT NULL,
  `sday` int(10) NOT NULL,
  `run_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=105981 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sum_newuser_daily_detail
-- ----------------------------
DROP TABLE IF EXISTS `sum_newuser_daily_detail`;
CREATE TABLE `sum_newuser_daily_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serverid` int(10) unsigned NOT NULL,
  `gameid` smallint(4) unsigned NOT NULL,
  `fenbaoid` int(10) unsigned NOT NULL,
  `clienttype` varchar(32) NOT NULL DEFAULT '',
  `sday` int(10) NOT NULL,
  `cnt` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `logindate` (`sday`,`serverid`,`gameid`,`clienttype`,`fenbaoid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=135023 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sum_online
-- ----------------------------
DROP TABLE IF EXISTS `sum_online`;
CREATE TABLE `sum_online` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` smallint(4) unsigned NOT NULL,
  `gameid` smallint(4) unsigned NOT NULL,
  `sum_online` int(10) unsigned NOT NULL,
  `sum_maxonline` int(10) unsigned NOT NULL,
  `sum_worldonline` int(10) unsigned NOT NULL,
  `sum_worldmaxonline` int(10) unsigned NOT NULL,
  `avg_online` decimal(10,2) unsigned NOT NULL,
  `avg_maxonline` decimal(10,2) unsigned NOT NULL,
  `avg_worldonline` decimal(10,2) unsigned NOT NULL,
  `avg_worldmaxonline` decimal(10,2) unsigned NOT NULL,
  `sday` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_serverid_day` (`serverid`,`sday`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=51715 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sum_pay_daily
-- ----------------------------
DROP TABLE IF EXISTS `sum_pay_daily`;
CREATE TABLE `sum_pay_daily` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sday` int(11) unsigned NOT NULL,
  `serverid` smallint(10) unsigned NOT NULL DEFAULT '0',
  `gameid` tinyint(11) unsigned NOT NULL,
  `fenbaoid` int(4) unsigned NOT NULL,
  `paynopall` int(11) NOT NULL DEFAULT '0',
  `paynopnew` int(11) unsigned NOT NULL DEFAULT '0',
  `paynopnew_money` int(11) unsigned NOT NULL DEFAULT '0',
  `paycnt` int(11) unsigned NOT NULL DEFAULT '0',
  `income` int(11) unsigned NOT NULL DEFAULT '0',
  `arpu` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_sday` (`sday`,`serverid`,`fenbaoid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=425803 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sum_playerlvl
-- ----------------------------
DROP TABLE IF EXISTS `sum_playerlvl`;
CREATE TABLE `sum_playerlvl` (
  `pl_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `serverid` smallint(4) unsigned NOT NULL,
  `fenbaoid` smallint(4) unsigned NOT NULL,
  `level` int(11) unsigned NOT NULL DEFAULT '0',
  `cnt_level` int(11) unsigned NOT NULL,
  PRIMARY KEY (`pl_id`),
  KEY `idx_serverid_fenbaoid` (`serverid`,`fenbaoid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sum_playeronline
-- ----------------------------
DROP TABLE IF EXISTS `sum_playeronline`;
CREATE TABLE `sum_playeronline` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` smallint(4) unsigned NOT NULL,
  `fenbaoid` smallint(4) unsigned NOT NULL,
  `rmb` int(10) unsigned NOT NULL DEFAULT '0',
  `not_rmb` int(10) unsigned NOT NULL DEFAULT '0',
  `player` int(10) unsigned NOT NULL DEFAULT '0',
  `online_lvl` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `online_lvl_txt` varchar(32) NOT NULL DEFAULT '',
  `sday` int(10) unsigned NOT NULL DEFAULT '0',
  `daytime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_day` (`sday`) USING BTREE,
  KEY `idx_serverid_fenbaoid` (`sday`,`online_lvl`,`serverid`,`fenbaoid`)
) ENGINE=MyISAM AUTO_INCREMENT=469833 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sum_player_level
-- ----------------------------
DROP TABLE IF EXISTS `sum_player_level`;
CREATE TABLE `sum_player_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sday` int(10) unsigned NOT NULL,
  `gameid` smallint(4) unsigned NOT NULL,
  `lev` smallint(4) unsigned NOT NULL DEFAULT '0',
  `nop` int(10) unsigned NOT NULL DEFAULT '0',
  `serverid` int(10) unsigned NOT NULL DEFAULT '0',
  `fenbaoid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=635078 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sum_player_lost
-- ----------------------------
DROP TABLE IF EXISTS `sum_player_lost`;
CREATE TABLE `sum_player_lost` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sday` int(10) unsigned NOT NULL,
  `serverid` int(10) unsigned NOT NULL,
  `fenbaoid` int(10) unsigned NOT NULL DEFAULT '0',
  `gameid` smallint(4) unsigned NOT NULL DEFAULT '0',
  `lev` smallint(4) unsigned NOT NULL DEFAULT '0',
  `nop` int(10) unsigned NOT NULL DEFAULT '0',
  `online_cnt` int(10) unsigned NOT NULL DEFAULT '0',
  `lost_day1` int(10) unsigned NOT NULL DEFAULT '0',
  `lost_day3` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sday` (`sday`,`serverid`,`fenbaoid`,`gameid`,`lev`)
) ENGINE=MyISAM AUTO_INCREMENT=2181230 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sum_player_lost_all
-- ----------------------------
DROP TABLE IF EXISTS `sum_player_lost_all`;
CREATE TABLE `sum_player_lost_all` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sday` int(10) unsigned NOT NULL,
  `gameid` smallint(4) unsigned NOT NULL,
  `lev` smallint(4) unsigned NOT NULL DEFAULT '0',
  `nop` int(10) unsigned NOT NULL DEFAULT '0',
  `nl` int(10) unsigned NOT NULL DEFAULT '0',
  `online_cnt` int(10) unsigned NOT NULL DEFAULT '0',
  `lost_day1` int(10) unsigned NOT NULL DEFAULT '0',
  `lost_day3` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sday` (`sday`,`gameid`,`lev`)
) ENGINE=MyISAM AUTO_INCREMENT=44403 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sum_player_money
-- ----------------------------
DROP TABLE IF EXISTS `sum_player_money`;
CREATE TABLE `sum_player_money` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sday` int(10) unsigned NOT NULL,
  `gameid` smallint(4) unsigned NOT NULL,
  `lev` smallint(4) unsigned NOT NULL DEFAULT '0',
  `money` int(10) unsigned NOT NULL DEFAULT '0',
  `nop` int(10) unsigned NOT NULL DEFAULT '0',
  `emoney` int(10) unsigned NOT NULL DEFAULT '0',
  `serverid` int(10) unsigned NOT NULL DEFAULT '0',
  `fenbaoid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7959311 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sum_reg_trans
-- ----------------------------
DROP TABLE IF EXISTS `sum_reg_trans`;
CREATE TABLE `sum_reg_trans` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` smallint(4) unsigned NOT NULL,
  `fenbaoid` smallint(4) unsigned NOT NULL,
  `gameid` smallint(4) unsigned NOT NULL,
  `sday` int(10) unsigned NOT NULL,
  `stime` int(10) unsigned NOT NULL DEFAULT '0',
  `prof` smallint(4) unsigned NOT NULL,
  `sum_new` int(10) unsigned NOT NULL,
  `sum_cre` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stime` (`stime`,`gameid`,`serverid`,`fenbaoid`,`prof`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=303630 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Table structure for sum_reserveusers_daily
-- ----------------------------
DROP TABLE IF EXISTS `sum_reserveusers_daily`;
CREATE TABLE `sum_reserveusers_daily` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sday` int(11) unsigned NOT NULL,
  `serverid` int(10) unsigned NOT NULL DEFAULT '0',
  `gameid` int(11) NOT NULL,
  `fenbaoid` int(4) NOT NULL,
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
  `day15` int(11) unsigned NOT NULL DEFAULT '0',
  `day30` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sday` (`sday`,`serverid`,`fenbaoid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2313694 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sum_rmbused
-- ----------------------------
DROP TABLE IF EXISTS `sum_rmbused`;
CREATE TABLE `sum_rmbused` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `serverid` smallint(4) unsigned NOT NULL,
  `sday` int(10) unsigned NOT NULL,
  `daytime` datetime NOT NULL,
  `rmb_sum` int(10) unsigned NOT NULL DEFAULT '0',
  `rmb_pay` int(10) unsigned NOT NULL DEFAULT '0',
  `rmb_sys` int(10) unsigned NOT NULL DEFAULT '0',
  `rmb_used` int(10) unsigned NOT NULL DEFAULT '0',
  `cnt` int(10) unsigned NOT NULL DEFAULT '0',
  `rmb_left` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_sday` (`sday`) USING BTREE,
  KEY `idx_serverid` (`serverid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=293542 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for s_code
-- ----------------------------
DROP TABLE IF EXISTS `s_code`;
CREATE TABLE `s_code` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `item_id` char(20) NOT NULL DEFAULT '',
  `item_name` varchar(30) NOT NULL DEFAULT '',
  `createtime` int(11) unsigned NOT NULL,
  `endtime` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_itemid` (`item_id`,`createtime`)
) ENGINE=MyISAM AUTO_INCREMENT=963 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for s_files
-- ----------------------------
DROP TABLE IF EXISTS `s_files`;
CREATE TABLE `s_files` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `gid` tinyint(1) NOT NULL,
  `fsort` tinyint(1) unsigned DEFAULT '0',
  `fpath` varchar(32) NOT NULL,
  `ftitle_zh` varchar(32) NOT NULL,
  `ftitle_en` varchar(32) NOT NULL,
  `fstatus` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_fpath` (`fpath`)
) ENGINE=MyISAM AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for s_user
-- ----------------------------
DROP TABLE IF EXISTS `s_user`;
CREATE TABLE `s_user` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `uname` varchar(32) NOT NULL,
  `account` varchar(32) NOT NULL,
  `ugrp` tinyint(1) unsigned NOT NULL,
  `upwd` varchar(32) NOT NULL,
  `urights` varchar(200) DEFAULT NULL,
  `createtime` datetime NOT NULL,
  `logintime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `loginip` varchar(50) NOT NULL DEFAULT '0',
  `ustatus` tinyint(1) NOT NULL DEFAULT '1',
  `logincnt` int(10) NOT NULL DEFAULT '0',
  `u_channel_limit` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_account` (`account`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for s_user_grp
-- ----------------------------
DROP TABLE IF EXISTS `s_user_grp`;
CREATE TABLE `s_user_grp` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `gname` varchar(32) NOT NULL,
  `gtype` tinyint(1) NOT NULL DEFAULT '3',
  `grights` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;





-- ----------------------------
-- Table structure for u_player_through_time
-- ----------------------------
DROP TABLE IF EXISTS `u_player_through_time`;
CREATE TABLE `u_player_through_time` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `accountid` int(4) unsigned NOT NULL DEFAULT '0',
  `serverid` int(4) unsigned NOT NULL DEFAULT '0',
  `through_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `combat` int(4) unsigned NOT NULL DEFAULT '0',
  `level` int(4) unsigned NOT NULL DEFAULT '0',
  `logtime` int(4) unsigned NOT NULL DEFAULT '0',
  `gk` smallint(4) unsigned NOT NULL DEFAULT '0',
  `min_time` int(4) unsigned NOT NULL DEFAULT '0',
  `flag` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_key` (`accountid`,`serverid`,`through_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for u_vipgoods
-- ----------------------------
DROP TABLE IF EXISTS `u_vipgoods`;
CREATE TABLE `u_vipgoods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4000000003 DEFAULT CHARSET=gbk;
