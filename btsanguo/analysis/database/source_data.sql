/*
mac登陆记录表，
mac为客户端唯一标识
*/
CREATE TABLE `loginmac` (
  `id` int(4) unsigned NOT NULL auto_increment,
  `mac` varchar(32) NOT NULL default '0',             /*mac,客户端唯一标识*/
  `accountid` int(32) unsigned NOT NULL default '0',  /*玩家账号id*/
  `server` varchar(32) NOT NULL default '0',          /*服务器名 中文名*/
  `gameid` int(4) unsigned NOT NULL default '0',      /*游戏id*/
  `logintime` int(4) unsigned NOT NULL default '0',   /*登陆时间*/
  `loginfirst` tinyint(2) unsigned zerofill NOT NULL default '00',/*这个字段目前无用*/
  `fenbaoid` int(4) unsigned NOT NULL default '0',               /*渠道标识*/
  `clienttype` varchar(32) NOT NULL default '',                  /*客户端类型*/
  `ip` varchar(17) NOT NULL default '0',
  `serverid` int(10) unsigned NOT NULL default '0',              /*服务器id*/
  PRIMARY KEY  (`id`),
  UNIQUE KEY `NewIndex` (`accountid`,`loginfirst`,`logintime`),
  KEY `logintime` (`logintime`)
) TYPE=MyISAM;


/*
mac账号新创建时间
*/
CREATE TABLE `newmac` (
  `id` int(4) unsigned NOT NULL auto_increment,
  `mac` varchar(32) binary NOT NULL default '0',/*mac,客户端唯一标识*/
  `gameid` int(4) unsigned NOT NULL default '0',/*游戏id*/
  `accountid` int(4) unsigned NOT NULL default '0', /*玩家账号id*/
  `createtime` int(4) unsigned NOT NULL default '0',/*玩家账号创建时间*/
  `fenbaoid` int(4) unsigned NOT NULL default '0',/*渠道标识*/
  `clienttype` varchar(32) NOT NULL default '',/*客户端类型*/
  `ip` varchar(17) NOT NULL default '0',
  `serverid` int(10) unsigned NOT NULL default '0',/*服务器id*/
  PRIMARY KEY  (`id`),
  KEY `mac` (`mac`),
  KEY `createtime` (`createtime`)
) TYPE=MyISAM;


/*
mac账号新创建时间
*/

CREATE TABLE `online` (
  `id` int(4) unsigned NOT NULL auto_increment,
  `servername` varchar(32) binary NOT NULL default '0',/*服务器名 中文名*/
  `online` int(4) unsigned NOT NULL default '0',       /*在线量*/
  `MaxOnline` int(4) unsigned NOT NULL default '0',    /*最大在线量*/
  `WorldOnline` int(4) unsigned NOT NULL default '0',  /*世界在线量*/
  `WorldMaxOnline` int(4) unsigned NOT NULL default '0',/*世界最大在线量*/
  `daytime` int(4) unsigned NOT NULL default '0',       /*时间*/
  `gameid` int(10) unsigned NOT NULL default '0',       /*游戏id*/
  `serverid` int(10) unsigned NOT NULL default '0',     /*服务器id*/
  PRIMARY KEY  (`id`),
  UNIQUE KEY `daytime` (`servername`,`daytime`)
) TYPE=MyISAM;


/*
 玩家每天的角色数据
*/

CREATE TABLE `palyerday` (
  `id` int(4) unsigned NOT NULL auto_increment,
  `userid` int(4) unsigned NOT NULL default '0',   /*角色id*/
  `accountid` int(4) unsigned NOT NULL default '0',/*账号id*/
  `lev` smallint(3) unsigned NOT NULL default '0',/*等级*/
  `money` int(4) unsigned NOT NULL default '0',   /*金钱*/
  `MoneySave` int(4) unsigned NOT NULL default '0',
  `emoney` int(4) unsigned NOT NULL default '0',   /*元宝*/
  `serverid` int(4) unsigned NOT NULL default '0', /*服务器id*/
  `gameid` int(4) unsigned NOT NULL default '0',   /*游戏id*/
  `day` int(4) unsigned NOT NULL default '0',       /*时间*/
  `fenbaoid` varchar(128) binary NOT NULL default '',/*渠道标识*/
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userid` (`userid`,`day`)
) TYPE=MyISAM;

/*玩家在线数据*/
CREATE TABLE `palyeronline` (
  `id` int(4) unsigned NOT NULL auto_increment,
  `userid` int(4) unsigned NOT NULL default '0',/*角色id*/
  `accountid` int(4) unsigned NOT NULL default '0',/*账号id*/
  `online` int(4) unsigned NOT NULL default '0',/*在线时间*/
  `serverid` int(4) unsigned NOT NULL default '0',/*服务器id*/
  `daytime` int(4) unsigned NOT NULL default '0',/*日期*/
  `viplev` tinyint(1) NOT NULL default '0'/*>0表示RMB玩家*/
  `fenbaoid`  int(4) unsigned NOT NULL default '0',/*渠道ID*/
  `lev` smallint(4) unsigned NOT NULL default '0',/*玩家等级*/
  `createtime` int(4) unsigned NOT NULL default '0',/*角色创建时间，格式：20140619*/
  PRIMARY KEY  (`id`),
  KEY `idx_serverid_createtime` (`serverid`,`createtime`,`daytime`),
  KEY `idx_accountid` (`accountid`)
) TYPE=MyISAM;

/*玩家角色信息表*/
CREATE TABLE `player` (
  `id` int(4) unsigned NOT NULL auto_increment,
  `userid` int(4) unsigned NOT NULL default '0',/*角色id*/
  `name` varchar(32) NOT NULL default '0',/*角色名*/
  `accountid` int(4) unsigned NOT NULL default '0',/*账号id*/
  `lev` smallint(3) unsigned NOT NULL default '0',/*等级*/
  `prof` tinyint(1) unsigned NOT NULL default '0',/*职业*/
  `gender` tinyint(1) unsigned NOT NULL default '0',/*性别*/
  `serverid` int(4) unsigned NOT NULL default '0',/*服务器id*/
  `gameid` int(4) unsigned NOT NULL default '0',/*游戏id*/
  `fenbao` int(4) unsigned NOT NULL default '0',/*渠道标识*/
  `clienttype` varchar(32) NOT NULL default '',/*客户端类型*/
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userid` (`userid`,`serverid`),
  KEY `accountid` (`accountid`)
) TYPE=MyISAM;

/*元宝消耗表*/
CREATE TABLE `rmb` (
  `id` int(4) unsigned NOT NULL auto_increment,
  `userid` int(4) unsigned NOT NULL default '0',/*角色id*/
  `accountid` int(4) unsigned NOT NULL default '0',/*账号id*/
  `type` int(4) unsigned NOT NULL default '0',/*类型*/
  `emoney` int(4) unsigned NOT NULL default '0',/*元宝*/
  `itemtype` int(4) unsigned NOT NULL default '0',/*消耗的产品id*/
  `daytime` int(4) unsigned NOT NULL default '0',/*时间*/
  `serverid` int(4) unsigned zerofill NOT NULL default '0000',/*服务器id*/
  `fenbaoid` int(4) unsigned zerofill NOT NULL default '0000',/*渠道ID*/  `game_id` tinyint(1) NOT NULL default '1',/*游戏id*/
  PRIMARY KEY  (`id`),
  KEY `daytime` (`daytime`),
  KEY `userid` (`userid`),
  KEY `accountid` (`accountid`)
) TYPE=MyISAM;


/*玩家等级数据*/
CREATE TABLE `u_player_levinfo` (
  `id` int(11) NOT NULL auto_increment,
  `server_id` int(11) unsigned NOT NULL default '0',/*服务器id*/
  `player_id` int(11) unsigned NOT NULL default '0',/*角色id*/
  `name` varchar(15) NOT NULL default '0',/*角色名*/
  `level` int(11) unsigned NOT NULL default '0',/*等级*/
  `time` int(11) unsigned NOT NULL default '0',/*时间*/
  PRIMARY KEY  (`id`),
  KEY `time` (`time`),
  KEY `server_id` (`server_id`)
) TYPE=MyISAM;

/*新创建角色的表*/
CREATE TABLE `u_yreg_newbie` (
  `id` int(11) NOT NULL auto_increment,
  `server_id` int(11) unsigned NOT NULL default '0',/*服务器id*/
  `player_id` int(11) unsigned NOT NULL default '0',/*角色id*/
  `name` varchar(15) NOT NULL default '',/*角色名*/
  `time` int(4) unsigned NOT NULL default '0',/*时间戳*/
  `account_id` int(11) unsigned NOT NULL default '0',/*账号id*/
  PRIMARY KEY  (`id`),
  KEY `time` (`time`),
  KEY `server_id` (`server_id`)
) TYPE=MyISAM;

-- 当前任务u_player_quest表user_id等于自己的记录 quest_id 记录任务id
-- 完成的任务
-- select * from u_quest WHERE type=110 ORDER BY system_id ASC
-- 每个玩家自由一条记录，system_id递增，表示当前已经完成的任务。
DROP TABLE `over_quest` IF EXISTS `over_quest`;
CREATE TABLE `over_quest` (
  `userid` int(4) unsigned NOT NULL default '0',
  `serverid` int(4) unsigned NOT NULL default '0',
  `accountid` int(4) unsigned NOT NULL default '0',
  `systemid` int(4) unsigned NOT NULL default '0',
  `idQuest` int(4) unsigned NOT NULL default '0',
  `szQuest` varchar(16) NOT NULL default '',/*任务名称*/
  `fenbaoid` int(4) unsigned NOT NULL default '0',
  `time` int(4) unsigned NOT NULL default '0',/*注册时间*/
  PRIMARY KEY  (`userid`,`serverid`),
  KEY `time` (`time`,`serverid`,`fenbaoid`)
) TYPE=MyISAM;

-- 统计当前系统元宝总数
CREATE TABLE `total_emoney` (
  `daytime` int(4) unsigned NOT NULL default '0',
  `serverid` int(4) unsigned NOT NULL default '0',
  `emoney` int(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`daytime`,`serverid`)
) TYPE=MyISAM;

-- 充值产出的元宝
CREATE TABLE `rmb_emoney` (
  `id` int(4) unsigned NOT NULL auto_increment,
  `idUser` int(4) unsigned NOT NULL default '0',
  `serverid` int(4) unsigned NOT NULL default '0',
  `daytime` int(4) unsigned NOT NULL default '0',
  `emoney` int(4) unsigned NOT NULL default '0',
  `fenbaoid` int(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
-- 系统赠送元宝
CREATE TABLE `give_emoney` (
  `id` int(4) unsigned NOT NULL auto_increment,
  `idUser` int(4) unsigned NOT NULL default '0',
  `serverid` int(4) unsigned NOT NULL default '0',
  `daytime` int(4) unsigned NOT NULL default '0',
  `emoney` int(4) unsigned NOT NULL default '0',
  `fenbaoid` int(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- 首次充值,所有服务器公用账号,accountid不会唯一

CREATE TABLE `first_rmb` (
  `idUser` int(4) unsigned NOT NULL auto_increment,
  `serverid` int(4) unsigned NOT NULL default '0',
  `accountid` int(4) unsigned NOT NULL default '0',
  `daytime` int(4) unsigned NOT NULL default '0',/*充值时间 分钟,1406200958*/
  `payemoney` int(4) unsigned NOT NULL default '0',/*本次充值元宝,要换算成rmb，就是除以10*/
  `fenbaoid` int(4) unsigned NOT NULL default '0',
  `lev` smallint(2) unsigned NOT NULL default '0',/* 角色等级*/
  `createtime` int(4) unsigned NOT NULL default '0',/* 1406200958，到分钟*/
  PRIMARY KEY  (`idUser`,`serverid`)
) TYPE=MyISAM;



/*游服----公告表*/
-- u_gmtool
CREATE TABLE `u_gmtool` (
  `id` int(11) NOT NULL auto_increment,
  `type` int(4) unsigned NOT NULL default '0',/*100，运营公告*/
  `message` varchar(255) NOT NULL default '',/*公告内容*/
  `status` tinyint(1) unsigned NOT NULL default '0',
  `time_begin` int(4) unsigned NOT NULL default '0',/*开始时间，ymdHi*/
  `time_end` int(4) unsigned NOT NULL default '0',/*开始时间*/
  `time_dis` int(4) unsigned NOT NULL default '0',/*间隔时间（s）*/
  `createtime_min` int(4) unsigned NOT NULL default '0',/*注册时间*/
  `createtime_max` int(4) unsigned NOT NULL default '0',/*注册时间*/
  `level_min` smallint(2) unsigned NOT NULL default '0',/*玩家等级*/
  `level_max` int(4) unsigned NOT NULL default '0',/*玩家等级*/
  `rmb_min` int(4) unsigned NOT NULL default '0',/*充值范围*/
  `rmb_max` int(4) unsigned NOT NULL default '0',/*充值范围*/
  `fenbao` int(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;