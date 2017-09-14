/*
navicat mysql data transfer

source server         : localhost
source server version : 50051
source host           : localhost:3306
source database       : razor

target server type    : mysql
target server version : 50051
file encoding         : 65001

date: 2014-05-22 15:35:12
*/
-- ----------------------------
-- 服务器组
-- ----------------------------
drop table if exists `servers_group`;
create table `servers_group` (
  `id` smallint(4) auto_increment ,
  `group_name` varchar(32) not null default '',
  primary key(`id`)
)engine=myisam default charset=gbk;
-- ----------------------------
-- 服务器列表
-- ----------------------------
drop table if exists `servers_list`;
create table `servers_list` (
    `id` smallint(4) auto_increment ,
    `gameid` tinyint(1) unsigned  not null,
    `groupid` tinyint(1) not null default 0,/*所属服务器组*/
    `serverid` smallint(4) unsigned ,
    `servername` varchar(32) not null default '',
    `opentime` datetime ,
    primary key(`id`)
)engine=myisam default charset=gbk;

drop table if exists `sum_archive_daily`;
create table `sum_archive_daily` (
    `id` int(10) not null auto_increment,
    `date` date not null,
    `sum_reg` int(10) not null default 0,/*当天新增注册数*/
    `sum_login` int(10) not null default 0,/*当天登录数*/
    primary key(`id`)
) engine myisam;

-- ----------------------------
-- 每日登录详细统计
-- ----------------------------
drop table if exists `sum_login_daily_detail`;
create table `sum_login_daily_detail` (
    `id` int(11) not null auto_increment,
    `serverid` int(10) unsigned not null,
    `gameid` smallint(4) unsigned not null,
    `fenbaoid` int(10) unsigned not null,
    `clienttype` varchar(32) not null default '',                  /*客户端类型*/
    `sday` int(10) not null,
    `cnt` int(10) unsigned not null,/*登录数*/
    primary key(`id`),
    unique key `logindate` (`sday`,`serverid`,`gameid`,`clienttype`,`fenbaoid`)
) engine=myisam default charset=latin1;

-- ----------------------------
-- 每日新增详细统计
-- ----------------------------
drop table if exists `sum_newuser_daily_detail`;
create table `sum_newuser_daily_detail` (
    `id` int(11) not null auto_increment,
    `serverid` int(10) unsigned not null,
    `gameid` smallint(4) unsigned not null,
    `fenbaoid` int(10) unsigned not null,
    `clienttype` varchar(32) not null default '', /*客户端类型*/
    `sday` int(10) not null,
    `cnt` int(10) unsigned not null,/*新增数*/
    primary key(`id`),
    unique key `logindate` (`sday`,`serverid`,`gameid`,`clienttype`,`fenbaoid`)
) engine=myisam default charset=latin1;


-- ----------------------------
-- 每日留存
-- ----------------------------
drop table if exists `sum_reserveusers_daily`;
create table `sum_reserveusers_daily` (
  `id` int(11) not null auto_increment,
  `sday` int(11) unsigned not null,/*统计时间*/
  `serverid` int(10) unsigned not null default '0',/*服务器id*/
  `gameid` int(11) not null,/*游戏id*/
  `fenbaoid` int(4) not null,/*渠道*/
  `usercount` int(11) not null default '0',/*新增用户数（注册数）*/
  `newlogin` int(11) not null default '0',/*新增登录*/
  `dau` int(11) unsigned not null default '0',/**/
  `wau` int(11) unsigned not null default '0',/**/
  `mau` int(11) unsigned not null default '0',/**/
  `day1` int(11) unsigned not null default '0',/*留存率*/
  `day2` int(11) unsigned not null default '0',/*留存率*/
  `day3` int(11) unsigned not null default '0',/*留存率*/
  `day4` int(11) unsigned not null default '0',/*留存率*/
  `day5` int(11) unsigned not null default '0',/*留存率*/
  `day6` int(11) unsigned not null default '0',/*留存率*/
  `day7` int(11) unsigned not null default '0',/*留存率*/
  `day8` int(11) unsigned not null default '0',/*留存率*/
  `day15` int(11) unsigned not null default '0',/*留存率*/
  `day30` int(11) unsigned not null default '0',/*留存率*/
  primary key  (`id`),
  unique key `sday` (`sday`,`gameid`,`serverid`,`fenbaoid`)
) engine=myisam default charset=latin1;

-- ----------------------------
-- 每日支付 
 --    [paynopall] => 2
 -- [paynopnew] => 2
 -- [paycnt] => 8
 -- [income] => 100.00
 -- [arpu] => 50
-- ----------------------------
drop table if exists `sum_pay_daily`;
create table `sum_pay_daily` (
  `id` int(11) not null auto_increment,
  `sday` int(11) unsigned not null,/*统计时间*/
  `serverid` smallint(10) unsigned not null default '0',/*服务器id*/
  `gameid` tinyint(11) unsigned not null,/*游戏id*/
  `fenbaoid` smallint(4) unsigned not null,/*渠道*/
  `paynopall` int(11) not null default '0',/*付费人数*/
  `paynopnew` int(11) unsigned not null default '0',/*新增付费人数*/
  `paynopnew_money` int(11) unsigned not null default '0',/*新增付费人数充值金额*/
  `paycnt` int(11) unsigned not null default '0',/*付费次数*/
  `income` int(11) unsigned not null default '0',/*总收入*/
  `arpu` int(11) unsigned not null default '0',/*arpu*/
  primary key  (`id`),
  key `idx_sday` (`sday`,`gameid`,`serverid`,`fenbaoid`)
) engine=myisam default charset=latin1;

/*每日数据汇总总表*/
drop table if exists `sum_daily_archive_all`;
create table `sum_daily_archive_all` (
    `id` int(11) auto_increment,
    `gameid` tinyint(1) not null default 0,
    `login_cnt`  int(10) not null default 0,/*注册数*/
    `nl_cnt`     int(10) not null default 0,/*新增登录*/
    `role_cnt`   int(10) not null default 0,/*创建数*/
    `role_rate`  decimal(8,4),/*创建率*/
    `au_cnt`     int(10) not null default 0,/*活跃用户数*/
    `dau`        int(10) not null default 0,
    `wau`        int(10) not null default 0,
    `mau`        int(10) not null default 0,
    `income_cnt`  int(10) not null default 0,/*总收入,充值金额*/
    `pay_nop`     int(10) not null default 0,/*充值人数*/
    `pay_nop_n` int(10) not null default 0,/*新增充值人数*/
    `pay_nop_nm` int(10) not null default 0,/*新增充值人数充值金额*/
    `pay_cnt`     int(10) not null default 0,/*充值次数*/
    `pay_rate`    decimal(8,4),/*总的付费率：付费用户数/活跃用户数*/
    `reg_cnt`     int(10) not null default 0,/*注册数*/
    `arpu`        decimal(8,4),/*arpu:总收入/付费用户数*/
    `arpdau`      decimal(8,4),/*arpdau:总收入/活跃用户数*/
    `reg_arpu`    decimal(8,4),/*注册apru*/
    `sday`        int(10) not null default 0,
    primary key  (`id`),
    key `idx_sday` (`sday`)
) engine=myisam default charset=latin1;

/*每日数据汇总细表*/
drop table if exists `sum_daily_archive`;
create table `sum_daily_archive` (
    `id` int(11) auto_increment,
    `gameid` tinyint(1) not null default 0,
    `serverid` smallint(4) unsigned not null default 0,
    `fenbaoid` smallint(4) unsigned not null default 0,
    `login_cnt`int(10) not null default 0,/*注册数*/
    `nl_cnt`   int(10) not null default 0,/*新增登录*/
    `role_cnt` int(10) not null default 0,/*创建数*/
    `role_rate` decimal(8,4),/*创建率*/
    `au_cnt` int(10) not null default 0,/*活跃用户数*/
    `dau` int(10) not null default 0,
    `wau` int(10) not null default 0,
    `mau` int(10) not null default 0,
    `income_cnt` int(10) not null default 0,/*总收入,充值金额*/
    `pay_nop_n` int(10) not null default 0,/*新增充值人数*/
    `pay_nop_nm` int(10) not null default 0,/*新增充值人数充值金额*/
    `pay_cnt` int(10) not null default 0,/*充值次数*/
    `pay_rate` decimal(8,4),/*总的付费率：付费用户数/活跃用户数*/
    `reg_cnt` int(10) not null default 0,/*注册数*/
    `arpu` decimal(8,4),/*arpu:总收入/付费用户数*/
    `arpdau` decimal(8,4),/*arpdau:总收入/活跃用户数*/
    `reg_arpu` decimal(8,4),/*注册apru*/
    `sday` int(10) not null default 0,
    primary key  (`id`),
    key `idx_sday_serverid_fenbaoid` (`sday`,`serverid`,`fenbaoid`)
) engine=myisam default charset=latin1;
-- ----------------------------
-- 在线统计
-- 每间隔15-30分钟更新
-- ----------------------------
drop table if exists `sum_online`;
create table `sum_online`(
    `id` int(10) auto_increment,
    `serverid` smallint(4) unsigned not null,
    `gameid` smallint(4) unsigned not null,
    `sum_online` int(10) unsigned not null ,/*当前时间在线数量*/
    `sum_maxonline` int(10) unsigned not null ,/*最大线数量*/
    `sum_worldonline` int(10) unsigned not null ,/*全部服务器在线数量*/
    `sum_worldmaxonline` int(10) unsigned not null ,/*全部服务器总在线数量*/
    `avg_online` decimal(10, 2) unsigned not null ,/*平均在线数量*/
    `avg_maxonline` decimal(10, 2) unsigned not null ,/*平均最大在线数量*/
    `avg_worldonline` decimal(10, 2) unsigned not null ,/*全部服务器平均在线数量*/
    `avg_worldmaxonline` decimal(10, 2) unsigned not null ,/*全部服务器平均最大在线数量*/
    `sday` int(10) unsigned not null,/*统计日期*/
    primary key  (`id`),
    key `idx_serverid_day` using btree (`serverid`, `sday`)
)engine=myisam default charset=latin1;

-- ----------------------------
-- 在线时长统计
-- 当天数据，每隔30分钟更新；可人为更新。
-- 每天凌晨12点，重新统计后更新。
-- ----------------------------
drop table if exists `sum_playeronline`;
create table `sum_playeronline`(
    `id` int(10) auto_increment,
    `serverid` smallint(4) unsigned not null,
    `fenbaoid` smallint(4) unsigned not null,
    `rmb`       int(10) unsigned not null,
    `not_rmb`   int(10) unsigned not null,
    `player` int(10) unsigned not null,/*总玩家*/
    `online_lvl` tinyint(1) unsigned not null,/*在线时长等级*/
    `online_lvl_txt` varchar(32) not null,/*在线时长等级说明*/
    `sday`  int(10) unsigned not null,/*记录日期ymd格式，如20140527*/
    `daytime` int(10) unsigned not null,/*时间戳*/
    primary key(`pol_id`),
    key `idx_day` using btree(`sday`),
    unique key `sday` (`sday`,`serverid`,`fenbaoid`,`online_lvl`)
)engine=myisam default charset=latin1;

-- ----------------------------
-- 元宝消耗统计
-- 每天凌晨12点统计
-- ----------------------------
-- ----------------------------
-- 游戏服务器——u_playershare表
-- account_id 账号id
-- server_id 服务器编号
-- fenbaoid 渠道id
-- emoney 当前元宝数
-- unline_rechage 不在线尚未发放的元宝
-- give_emoney 系统给的元宝
-- rmb_emoney 充值获得的元宝
-- ----------------------------
drop table if exists `sum_rmbused`;
create table `sum_rmbused`(
    `id` int(10) auto_increment,
    `serverid` smallint(4) unsigned not null,
    `sday` int(10) unsigned not null,/*统计日期*/
    `daytime` datetime not null,/*时间*/
    `rmb_sum` int(10) unsigned not null,/*rmb总数*/
    `rmb_pay` int(10) unsigned not null,/*充值产出数*/
    `rmb_sys` int(10)unsigned not null,/*系统产出数*/
    `rmb_used` int(10) unsigned not null,/*元宝消耗数*/
    `rmb_left` int(10) unsigned not null,/*元宝剩余*/
    primary key(`id`),
    key `idx_sday` using btree(`sday`),
    key `idx_serverid` using btree(`serverid`)
)engine=myisam;
-- ----------------------------
-- 商城消费
-- 每天凌晨12点统计
-- ----------------------------
drop table if exists `sum_market_pay`;
create table `sum_market_pay`(
    `id` int(10) auto_increment,
    `serverid` smallint(4) unsigned not null,
    `fenbaoid` smallint(4) unsigned not null,
    `sday`  int(10) unsigned not null,/*记录日期ymd格式，如140527*/
    `daytime` int(10) unsigned not null,/*时间戳*/
    `sum_emoney` int(10) unsigned not null,/*元宝消费总数*/
    `itemtype` int(10) unsigned not null,/*消耗的产品id*/
    `rate` decimal(8, 4) unsigned not null,/*比重*/
    primary key(`id`),
    key `idx_day` using btree(`day`),
    key `idx_serverid_fenbaoid` using btree(`serverid`,`fenbaoid`)
)engine=myisam;

drop table if exists `sum_behavior_pay`;
create table `sum_behavior_pay`(
    `id` int(10) auto_increment,
    `serverid` smallint(4) unsigned not null,
    `fenbaoid` smallint(4) unsigned not null,
    `sday`  int(10) unsigned not null,/*记录日期ymd格式，如140527*/
    `daytime` int(10) unsigned not null,/*时间戳*/
    `sum_emoney` int(10) unsigned not null,/*元宝消费总数*/
    `stype` int(10) unsigned not null,/*类型*/
    `rate` decimal(8, 4) unsigned not null,/*比重*/
    primary key(`id`),
    key `idx_day` using btree(`day`),
    key `idx_serverid_fenbaoid` using btree(`serverid`,`fenbaoid`)
)engine=myisam;

-- ----------------------------
-- 注册转化
-- ----------------------------
drop table if exists `sum_reg_trans`;
create table `sum_reg_trans`(
    `id` int(10) auto_increment,
    `serverid` smallint(4) unsigned not null,
    `fenbaoid` smallint(4) unsigned not null,
    `gameid` smallint(4) unsigned not null,
    `sday`  int(10) unsigned not null,/*记录日期ymd格式，如20140527*/
    `stime` int(10) unsigned not null default 0,/*ymdHi格式*/
    `prof` smallint(4) unsigned not null,/*职业*/
    `sum_new` int(10) unsigned not null,/*注册数*/
    `sum_cre` int(10) unsigned not null,/*创建数*/
    primary key(`id`),
    unique key `stime` (`stime`,`gameid`,`serverid`,`fenbaoid`)
)engine=myisam;


drop table if exists `sum_playerlvl`;
create table `sum_playerlvl` (
  `pl_id` int(10) unsigned not null auto_increment,
  `serverid` smallint(4) unsigned not null,
  `fenbaoid` smallint(4) unsigned not null,
  `level` int(11) unsigned not null default '0',
  `cnt_level` int(11) unsigned not null,
  primary key  (`pl_id`),
  key `idx_serverid_fenbaoid` using btree (`serverid`,`fenbaoid`)
) engine=myisam default charset=latin1;

# DROP TABLE IF EXISTS `sum_account_lev`;
# CREATE TABLE `sum_account_lev` (
#     `id` int(11) not null auto_increment,
#     `sday` int(10) unsigned not null,
#     `serverid` int(10) unsigned not null,
#     `fenbaoid` int(10) unsigned not null default 0,
#     `gameid` smallint(4) unsigned not null default 0,
#     `lev` smallint(4) unsigned not null default 0,/*等级*/
#     `accounts` text NOT NULL DEFAULT '0',
#     unique key `sday` (`sday`,`serverid`,`fenbaoid`,`gameid`,`lev`)
# )engine=myisam default charset=latin1;

-- ------------------------------
-- 玩家流失、等级统计
-- ----------------------------
drop table if exists `sum_player_lost`;
create table `sum_player_lost` (
    `id` int(11) not null auto_increment,
    `sday` int(10) unsigned not null,
    `serverid` int(10) unsigned not null,
    `fenbaoid` int(10) unsigned not null default 0,
    `gameid` smallint(4) unsigned not null default 0,
    `lev` smallint(4) unsigned not null default 0,/*等级*/
    `nop` int(10) unsigned not null default 0,/*人数*/
    `online_cnt` int(10) unsigned not null  default 0,/*今日在线数*/
    `lost_day1` int(10) unsigned not null default 0,/*次日流失*/
    `lost_day3` int(10) unsigned not null default 0,/*3日流失*/
    primary key(`id`),
    unique key `sday` (`sday`,`serverid`,`fenbaoid`,`gameid`,`lev`)
) engine=myisam default charset=latin1;

drop table if exists `sum_player_lost_all`;
create table `sum_player_lost_all` (
    `id` int(11) auto_increment,
    `sday` int(10) unsigned not null,
    `gameid` smallint(4) unsigned not null,
    `lev` smallint(4) unsigned not null default 0,/*等级*/
    `nop` int(10) unsigned not null default 0,/*人数*/
    `nl` int(10) unsigned not null default  0,/*该等级今日新增人数*/
    `online_cnt` int(10) unsigned not null default 0,/*今日在线数*/
    `lost_day1` int(10) unsigned not null default 0,/*次日流失*/
    `lost_day3` int(10) unsigned not null default 0,/*3日流失*/
    primary key(`id`),
    unique key `sday` (`sday`,`gameid`,`lev`)
) engine=myisam default charset=latin1;

DROP TABLE IF EXISTS `pay_log`;
CREATE TABLE `pay_log` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `PayID` int(4) DEFAULT NULL,/*accountid*/
  `ServerID` int(4) DEFAULT NULL,
  `PayMoney` float(6,2) DEFAULT '0.00',
  `dwFenBaoID` varchar(50) DEFAULT NULL,
  `Add_Time` datetime DEFAULT NULL,
  `game_id` smallint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_payid` (`PayID`) USING BTREE,
  KEY `idx_Add_Time` (`Add_Time`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# drop table if exists `sum_player_nopay`;
# create table `sum_player_nopay` (
#   `id` int(11) not null auto_increment,
#   `sday` int(10) unsigned not null,
#   `accountid` int(10) unsigned not null,
#   `has_pay` tinyint(1) not null,
#   primary key (`id`),
#   unique key `accountid`(`accountid`)
# )engine=myisam;

-- explain SELECT * FROM sum_daily_archive ORDER BY sday
DROP TABLE IF EXISTS `s_user`;
CREATE TABLE `s_user`(
    `id` smallint(4) auto_increment,
    `uname` varchar(32) NOT NULL,
    `account` varchar(32) NOT NULL,
    `ugrp` tinyint(1) UNSIGNED NOT NULL,
    `upwd` varchar(32) NOT NULL,
    `urights` varchar(100) NOT NULL DEFAULT '',/*用户权限*/
    `createtime` datetime NOT NULL,/*创建时间*/
    `logincnt` int(10) NOT NULL DEFAULT 0,/*登录次数*/
    `logintime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',/*登录时间*/
    `loginip` varchar(50) NOT NULL DEFAULT 0,/*登录IP*/
    `ustatus` tinyint(1) NOT NULL DEFAULT 1,/*用户状态1正常，0禁用*/
    PRIMARY KEY(`id`),
    UNIQUE KEY `uk_account`(`account`)
) ENGINE=MyISAM DEFAULT CHARSET=UTF8;

DROP TABLE IF EXISTS `s_user_grp`;
CREATE TABLE `s_user_grp`(
    `id` smallint(4) auto_increment,
    `gname` varchar(32) NOT NULL,/*分组名称*/
    `gtype` tinyint(1) NOT NULL DEFAULT 3,/*分组类型：1超级管理员2管理员3普通用户*/
    `grights` varchar(100) NOT NULL,/*该组默认拥有的权限*/
    primary key (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=UTF8;

DROP TABLE IF EXISTS `s_files`;
CREATE TABLE `s_files`(
    `id` int(10) auto_increment,
    `gid` tinyint(1) NOT NULL,/*所属组*/
    `fsort` tinyint(1) UNSIGNED NOT NULL,/*排序*/
    `fpath` varchar(32) NOT NULL,/*文件所在路径*/
    `ftitle_zh` varchar(32) NOT NULL,/*文件标题-中文*/
    `ftitle_en` varchar(32) NOT NULL,/*文件标题-英文*/
    `fstatus` tinyint(1) NOT NULL DEFAULT 1,/*文件状态1在用0失效*/
    `frights` int(10) NOT NULL DEFAULT 3000,/*文件访问权限*/
    primary key(`id`),
    unique key `uk_fpath`(`fpath`)
) ENGINE=MyISAM DEFAULT CHARSET=UTF8;
