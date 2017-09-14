-- sdk数据库表
CREATE TABLE access_log(
  `id` bigint(20) not null AUTO_INCREMENT,
  `method` VARCHAR(20) not null,
  `reqtime` TIMESTAMP not null,
  `reqtoken` varchar(32) not null,
  `reqdata` text not null,
  `reqappid` char(18) not null,
  PRIMARY KEY (`id`),
  key `idx_method` (`method`)
)ENGINE=InnoDB;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- 玩家登录数据记录表
CREATE TABLE `u_login` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) NOT NULL,
  `accountid` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `serverid` int(10) NOT NULL,
  `channel` int(10) NOT NULL,
  `viplev` int(10) NOT NULL,
  `lev` int(10) NOT NULL,
  `client_type` varchar(255) NOT NULL,
  `ip` int(10) NOT NULL,
  `logindate` int(10) NOT NULL DEFAULT '0',
  `created_at` int(10) NOT NULL,
  `appid` int(10) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `trainer_lev` int(10) NOT NULL DEFAULT '0' COMMENT '训练师等级',
  `client_version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_created` (`created_at`),
  KEY `idx_sv_cnl` (`serverid`,`channel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 玩家注册数据记录表
CREATE TABLE `u_register` (
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
  `reg_date` int(10) not null DEFAULT 0 comment '注册日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_account_server` (`accountid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_created` (`created_at`),
  KEY `idx_sv_cnl` (`serverid`,`channel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 角色创建数据记录表
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
  UNIQUE KEY `uk_account_server` (`accountid`,`userid`,`serverid`,`appid`) USING BTREE,
  KEY `idx_account` (`accountid`),
  KEY `idx_sv_cnl` (`serverid`,`channel`)
) ENGINE=InnoDB AUTO_INCREMENT=67180 DEFAULT CHARSET=utf8;

-- 服务器实时在线数据
create table u_server_online(
  `id` bigint(20) not null AUTO_INCREMENT,
  `serverid` int(10) not null,
  `online` int(10) not null,
  `max_online` int(10) NOT NULL ,
  `world_online` int(10) NOT NULL ,
  `max_world_online` int(10) NOT NULL ,
  `created_at` int(10) not null,
  `appid` int(10) not null,
  PRIMARY KEY (`id`),
  key `idx_created` (`created_at`)
)ENGINE=InnoDB;

drop table if EXISTS u_dayonline;
CREATE TABLE u_dayonline(
  `id` bigint(20) not null AUTO_INCREMENT,
  `online` int(10) not null,/*玩家在线时长,单位:秒*/
  `userid` int(10) not null,
  `accountid` int(10) not null,
  `serverid` int(10) not null,
  `channel` int(10) not null,
  `viplev` int(10) not null,
  `lev` int(10) not null,
  `total_rmb` int(10) not null,/*账户金额*/
  `online_date` int(10) not null, /*在线日期,Ymd*/
  `create_time` int(10) not null,/*角色创建时间*/
  `created_at` int(10) not null,
  `client_time` int(10) not null,/*客户端时间*/
  `appid` int(10) not null,
  PRIMARY KEY (`id`),
  key `idx_created` (`created_at`),
  key `idx_sv_cnl` (`serverid`,`channel`),
  UNIQUE KEY `uk_accountid`(accountid,serverid,online_date)
)ENGINE=InnoDB;


-- 消费接口
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
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- 元宝获取
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

drop table if EXISTS u_players;
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
  KEY `idx_account` (`accountid`),
  UNIQUE `uk_account_server`(`accountid`,`serverid`,`appid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;



-- 玩家副本
create TABLE u_copy_progress(
  `id` bigint(20) not null AUTO_INCREMENT,
  `userid` int(10) not null,
  `accountid` int(10) not null,
  `serverid` int(10) not null,
  `channel` int(10) not null,
  `lev` int(10) not null,/*副本等级*/
  `type` int(10) not null,/*副本类型*/
  `copy_id` int(10) UNSIGNED not null DEFAULT 0,/*副本ID*/
  `title` VARCHAR(20) not null,/*副本名称*/
  `is_success` TINYINT(1) not null,/*成功1失败0*/
  `created_at` int(10) not null,
  `appid` int(10) not null,
  key `idx_sv_cnl` (`serverid`,`channel`),
  key `idx_copy_id` (`copy_id`),
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;

-- 养成&强化
create TABLE u_develop(
  `id` bigint(20) not null AUTO_INCREMENT,
  `userid` int(10) not null,
  `accountid` int(10) not null,
  `serverid` int(10) not null,
  `channel` int(10) not null,
  `viplev` int(10) not null,
  `lev` int(10) not null,
  `type` int(10) not null,/*副本类型*/
  `title` VARCHAR(20) not null,/*副本名称*/
  `progress` int(10) not null,/*进度*/
  `equip_id` varchar(20) not null,/*装备ID*/
  `created_at` int(10) not null,
  `appid` int(10) not null,
  key `idx_sv_cnl` (`serverid`,`channel`),
  key `idx_account` (`accountid`),
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;
-- 道具
create TABLE u_props(
  `id` bigint(20) not null AUTO_INCREMENT,
  `userid` int(10) not null,
  `accountid` int(10) not null,
  `serverid` int(10) not null,
  `channel` int(10) not null,
  `prop_type` int(10) not null,/*道具类型*/
  `prop_id` varchar(20) not null,/*道具ID*/
  `prop_name` varchar(100) not null,/*道具名称*/
  `amounts` int(10) not null,/*获取or使用数量*/
  `gain_way` int(10) not null,/*获取途径（需后台配置）*/
  `created_at` int(10) not null,
  `appid` int(10) not null,
  key `idx_sv_cnl` (`serverid`,`channel`),
  key `idx_account` (`accountid`),
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;
-- 道具消费
DROP table IF EXISTS u_props_used;
create TABLE u_props_used(
  `id` bigint(20) not null AUTO_INCREMENT,
  `userid` int(10) not null,
  `accountid` int(10) not null,
  `serverid` int(10) not null,
  `channel` int(10) not null,
  `prop_type` int(10) not null,/*道具类型*/
  `prop_id` VARCHAR(20) not null,/*道具ID*/
  `prop_name` varchar(100) not null,/*道具名称*/
  `amounts` int(10) not null,/*获取or使用数量*/
  `gain_way` int(10) not null,/*获取途径（需后台配置）*/
  `created_at` int(10) not null,
  `appid` int(10) not null,
  key `idx_sv_cnl` (`serverid`,`channel`),
  key `idx_account` (`accountid`),
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;


-- 关卡进度
create TABLE u_level_process(
  `id` bigint(20) not null AUTO_INCREMENT,
  `userid` int(10) not null,/*玩家ID*/
  `accountid` int(10) not null,/*玩家账号*/
  `serverid` int(10) not null,/*区服ID*/
  `channel` int(10) not null,
  `lev` int(10) not null,/*玩家等级*/
  `viplev` int(10) not null,/*会员等级*/
  `level_type` int(10) not null,/*关卡类型*/
  `level_id` int(10) not null,/*关卡ID*/
  `highest_level` varchar(100) not null,/*最高关卡名称*/
  `created_at` int(10) not null,/*创建时间*/
  `appid` int(10) not null,
  key `idx_sv_cnl` (`serverid`,`channel`),
  key `idx_account` (`accountid`),
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;

-- 成就进度
create TABLE u_success_process(
  `id` bigint(20) not null AUTO_INCREMENT,
  `userid` int(10) not null,
  `accountid` int(10) not null,
  `serverid` int(10) not null,
  `channel` int(10) not null,
  `lev` int(10) not null,
  `viplev` int(10) not null,
  `success_type` int(10) not null,
  `success_id` int(10) UNSIGNED not null,/*成就ID*/
  `highest_success` varchar(100) not null,
  `created_at` int(10) not null,
  `appid` int(10) not null,
  key `idx_sv_cnl` (`serverid`,`channel`),
  key `idx_account` (`accountid`),
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;

-- 日常行为
create TABLE u_daily_actions(
  `id` bigint(20) not null AUTO_INCREMENT,
  `userid` int(10) not null,
  `accountid` int(10) not null,
  `serverid` int(10) not null,
  `channel` int(10) not null,
  `lev` int(10) not null,
  `viplev` int(10) not null,
  `action_type` smallint(4) not null,/*行为类型*/
  `use_time` int(10) not null,/*行为耗时*/
  `created_at` int(10) not null,
  `appid` int(10) not null,
  key `idx_sv_cnl` (`serverid`,`channel`),
  key `idx_account` (`accountid`),
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;

-- 升级历程
create TABLE u_upgrade_process(
  `id` bigint(20) not null AUTO_INCREMENT,
  `userid` int(10) not null,
  `accountid` int(10) not null,
  `serverid` int(10) not null,
  `channel` int(10) not null,
  `lev` int(10) not null,
  `upgrade_time` int(10) not null,/*本等级时间-上一等级时间*/
  `created_at` int(10) not null,
  `appid` int(10) not null,
  key `idx_sv_cnl` (`serverid`,`channel`),
  key `idx_account` (`accountid`),
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;

-- 安装解压
drop table IF EXISTS u_device_active;
create TABLE u_device_active(
  `id` bigint(20) not null AUTO_INCREMENT,
  `serverid` int(10) not null,
  `channel` int(10) not null,
  `client_type` VARCHAR(255) not null,
  `client_version` VARCHAR(255) not null,
  `mac` VARCHAR(255) not null,
  `created_at` int(10) not null,
  `appid` int(10) not null,
  key `idx_sv_cnl` (`serverid`,`channel`),
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;

-- 设备激活
drop table IF EXISTS u_device_unique;
create TABLE u_device_unique(
  `id` bigint(20) not null,
  `serverid` int(10) not null,
  `channel` int(10) not null,
  `client_type` VARCHAR(255) not null,
  `client_version` VARCHAR(255) not null,
  `mac` VARCHAR(255) not null,
  `created_at` int(10) not null,
  `appid` int(10) not null,
  UNIQUE `idx_mac` (`mac`),
  key `idx_sv_cnl` (`serverid`,`channel`)
)ENGINE=InnoDB;
-- 注册流程统计
drop table IF EXISTS u_register_process;
CREATE TABLE `u_register_process` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` VARCHAR(255) not null default '',
  PRIMARY KEY (`id`),
  INDEX `idx_appid_mac`(`appid`,`mac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table IF EXISTS u_register_process_history;
CREATE TABLE `u_register_process_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL,
  `channel` int(10) DEFAULT NULL,
  `mac` varchar(255) NOT NULL DEFAULT '',
  `type_id` smallint(4) unsigned DEFAULT NULL,
  `reason_id` smallint(4) unsigned DEFAULT NULL,
  `created_at` int(10) NOT NULL,
  `client_version` varchar(50) NOT NULL DEFAULT '0',
  `client_type` VARCHAR(255) not null default '',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


alter table u_device_unique add column (`client_version` VARCHAR(255) not null);
alter table u_device_active add column (`client_version` VARCHAR(255) not null);
alter table u_register add column (`client_version` VARCHAR(255) not null);
alter table u_login add column (`client_version` VARCHAR(255) not null);

CREATE TABLE u_paylog(
  `id` bigint(20) not null AUTO_INCREMENT,
  `accountid` int(10) not null,
  `serverid` int(10) not null,
  `channel` int(10) not null,
  `lev` int(10) not null,
  `money` int(10) not null,
  `orderid` VARCHAR(100) not null,
  `is_new` tinyint(1) not null,/*0老用户1新用户*/
  `created_at` int(10) not null,
  `appid` int(10) not null,
  `is_pay` tinyint(1) not null,
  key `idx_sv_cnl` (`serverid`,`channel`),
  key `idx_account` (`accountid`),
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;

CREATE TABLE u_bugreport(
  `id` bigint(20) not null AUTO_INCREMENT,
  `accountid` int(10) not null,
  `userid` int(10) not null,
  `username` VARCHAR(255) not null,
  `client_type` VARCHAR(255) not null,
  `content` varchar(500) not null,
  `appid` int(10) not null,
  `created_at` int(10) not null,
  `serverid` int(10) not null,
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;


CREATE TABLE app_type_list(
  `id` int(10) not null AUTO_INCREMENT,
  `appid` int(10) not null,
  `typeid` int(10) not null,
  `typename` VARCHAR(255) not null,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- 数据库表名规则:type_TYPEID_APPID
CREATE TABLE type_001_100002(
  `id` bigint(20) not null AUTO_INCREMENT,
  `accountid` int(10) not null,
  `userid` int(10) not null,
  `serverid` int(10) not null,
  `channel` int(10) not null,
  `typeid`  int(10) not null,
  `appid` int(10) not null,
  `created_at` int(10) not null,
  `参数字段1` int(10) NOT NULL,
  `参数字段2` CHAR(10) NOT NULL,
  `参数字段3` int(10) NOT NULL,
  `参数字段4` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;


ALTER  table u_login add column `trainer_lev` int(10) not null DEFAULT 0 COMMENT '训练师等级';
ALTER  table u_register add column `regway` tinyint(1) not null COMMENT '注册方式：1游客登录，2手机登录，3邮箱登录';
CREATE TABLE u_open_game_channel(
  `id` bigint(10) not null AUTO_INCREMENT,
  `appid` int(10) not null,
  `channel` int(10) not null,
  `mac` VARCHAR(255) not null,
  `created_at` int(10) not null,
  `client_timestamp` int(10) not null COMMENT '服务端发送时间',
  PRIMARY KEY (`id`),
  key `idx_channel` (`channel`))
ENGINE=InnoDB;

CREATE TABLE u_recharge(
  `id` bigint(10) not null AUTO_INCREMENT,
  `appid` int(10) not null,
  `channel` int(10) not null DEFAULT 0,
  `serverid` int(10) not null DEFAULT 0,
  `accountid` int(10) not null,
  `userid` int(10) not null,
  `rmb` int(10) not null COMMENT '充值金额单位元',
  `viplev` int(10) not null COMMENT '充值成功前的vip等级',
  `trainer_lev` int(10) not null COMMENT '训练师等级',
  `created_at` int(10) not null,
  `client_timestamp` int(10) not null COMMENT '发送时间',
  PRIMARY KEY (`id`),
  key `idx_account` (`accountid`)
) ENGINE=InnoDB COMMENT '充值成功事件';

CREATE TABLE u_logout(
  `id` bigint(10) not null AUTO_INCREMENT,
  `appid` int(10) not null,
  `serverid` int(10) not null DEFAULT 0,
  `accountid` int(10) not null,
  `userid` int(10) not null,
  `online` int(10) not null COMMENT '本次在线时长',
  `viplev` int(10) not null COMMENT '充值成功前的vip等级',
  `trainer_lev` int(10) not null COMMENT '训练师等级',
  `created_at` int(10) not null,
  `client_timestamp` int(10) not null COMMENT '发送时间',
  PRIMARY KEY (`id`),
  key `idx_account` (`accountid`)
) ENGINE=InnoDB COMMENT '登出';




