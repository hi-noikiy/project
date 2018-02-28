# 接口名称：GameServerData
#  type_id 对应表名称：
#  [
#     1     => 'u_player_active',//玩家活跃
#     2     => 'u_playing_method',//玩法次数统计
#     3     => 'u_common_currency',//通用货币获取消耗
#     4     => 'u_elf_starlev',//精灵星级&关卡统计
#     5     => 'u_level_difficulty',//关卡难易程度统计
#     6     => 'u_photo_level',//图鉴等级
# ];

DROP TABLE IF EXISTS u_player_active;
CREATE TABLE u_player_active (
  `id` bigint(10) not null AUTO_INCREMENT,
  `appid` int(10) not null,
  `serverid` int(10) not null DEFAULT 0,
  `channel` int(10) not null DEFAULT 0,
  `accountid` int(10) not null,
  `userid` int(10) not null,
  `lev`  SMALLINT(4) not null COMMENT '玩家等级',
  `viplev` SMALLINT(4) not null COMMENT 'vip等级',
  `created_at` int(10) not null COMMENT '服务器记录时间',
  `client_time` int(10) not null COMMENT '接口请求时间',
  `log_date` int(10) not null COMMENT '记录日期Ymd',
  `create_time` int(10) not null comment '玩家注册时间',
  `active` SMALLINT(4) UNSIGNED not null COMMENT '活跃值',
  PRIMARY KEY (`id`),
  key `idx_date` (`log_date`),
  UNIQUE `uk_account_date` (`accountid`,`userid`,`log_date`)
) ENGINE=InnoDB COMMENT '玩家活跃';

DROP TABLE IF EXISTS u_playing_method;
CREATE TABLE u_playing_method (
  `id` bigint(10) not null AUTO_INCREMENT,
  `appid` int(10) not null,
  `serverid` int(10) not null DEFAULT 0,
  `channel` int(10) not null DEFAULT 0,
  `accountid` int(10) not null,
  `userid` int(10) not null,
  `lev`  SMALLINT(4) not null COMMENT '玩家等级',
  `viplev` SMALLINT(4) not null COMMENT 'vip等级',
  `created_at` int(10) not null COMMENT '服务器记录时间',
  `client_time` int(10) not null COMMENT '接口请求时间',
  `log_date` int(10) not null COMMENT '记录日期Ymd',
  `create_time` int(10) not null comment '玩家注册时间',
  `method` SMALLINT(4) not null COMMENT '玩法类型',
  `playing_times` int(10) not null COMMENT '次数，累加',/*累加*/
  `playing_time` int(10) not null COMMENT '改成战斗的回合数',/*根据服务端发送的秒数累加计算得到*//*分钟数-转为秒存储*/
  PRIMARY KEY (`id`),
  key `idx_date` (`log_date`),
  UNIQUE `uk_account_date` (`accountid`,`userid`,`log_date`,`method`)
) ENGINE=InnoDB COMMENT '玩法次数统计';

DROP TABLE IF EXISTS u_common_currency;
CREATE TABLE u_common_currency (
  `id` bigint(10) not null AUTO_INCREMENT,
  `appid` int(10) not null,
  `serverid` int(10) not null DEFAULT 0,
  `channel` int(10) not null DEFAULT 0,
  `accountid` int(10) not null,
  `userid` int(10) not null,
  `lev`  SMALLINT(4) not null COMMENT '玩家等级',
  `viplev` SMALLINT(4) not null COMMENT 'vip等级',
  `created_at` int(10) not null COMMENT '服务器记录时间',
  `client_time` int(10) not null COMMENT '接口请求时间',
  `log_date` int(10) not null COMMENT '记录日期Ymd',
  `create_time` int(10) not null comment '玩家注册时间',
  `item_type` int(10) UNSIGNED not null COMMENT '物品类型',
  `daction` tinyint(1) not null COMMENT '消耗类型 1获得，2消耗',
  `amount` INT(10) not null COMMENT '货币/道具数量',/*发送每次获得或者消耗的增量，第一次10，第二次20，那么需要更新操作 set currency=currecy+20*/
  PRIMARY KEY (`id`),
  key `idx_date` (`log_date`),
  UNIQUE `uk_account_date` (`accountid`,`userid`,`log_date`,`daction`,`item_type`)
) ENGINE=InnoDB COMMENT '通用货币获取消耗';

DROP TABLE IF EXISTS u_elf_starlev;
CREATE TABLE u_elf_starlev (
  `id` bigint(10) not null AUTO_INCREMENT,
  `appid` int(10) not null,
  `serverid` int(10) not null DEFAULT 0,
  `channel` int(10) not null DEFAULT 0,
  `accountid` int(10) not null,
  `userid` int(10) not null,
  `lev`  SMALLINT(4) not null COMMENT '玩家等级',
  `viplev` SMALLINT(4) not null COMMENT 'vip等级',
  `created_at` int(10) not null COMMENT '服务器记录时间',
  `client_time` int(10) not null COMMENT '接口请求时间',
  `log_date` int(10) not null COMMENT '记录日期Ymd',
  `create_time` int(10) not null comment '玩家注册时间',
  `elf_1` smallint(4) not null COMMENT '第1高星精灵战斗力数值',
  `elf_2` smallint(4) not null COMMENT '第2高星精灵战斗力数值',
  `elf_3` smallint(4) not null COMMENT '第3高星精灵战斗力数值',
  `elf_4` smallint(4) not null COMMENT '第4高星精灵战斗力数值',
  `elf_5` smallint(4) not null COMMENT '第5高星精灵战斗力数值',
  `elf_6` smallint(4) not null COMMENT '第6高星精灵战斗力数值',
  `elf_7` smallint(4) not null COMMENT '第7高星精灵战斗力数值',
  `fighting` int(10) not null COMMENT  '战斗力',
  `nomal_copy` int(10) not null COMMENT '普通副本进度',-- 进度数如何计算的？
  `nomal_elite` int(10) not null COMMENT '精英副本进度',
  PRIMARY KEY (`id`),
  UNIQUE `uk_account_date` (`accountid`,`userid`,`log_date`)
) ENGINE=InnoDB COMMENT '精灵星级&关卡统计';
#   `is_success` tinyint(1) not null COMMENT '是否胜利0否1是',

 DROP TABLE IF EXISTS u_photo_level;
 CREATE TABLE u_photo_level (
   `id` bigint(10) not null AUTO_INCREMENT,
   `appid` int(10) not null,
   `serverid` int(10) not null DEFAULT 0,
   `channel` int(10) not null DEFAULT 0,
   `accountid` int(10) not null,
   `userid` int(10) not null,
   `lev`  SMALLINT(4) not null COMMENT '玩家等级',
   `viplev` SMALLINT(4) not null COMMENT 'vip等级',
   `created_at` int(10) not null COMMENT '服务器记录时间',
   `client_time` int(10) not null COMMENT '接口请求时间',
   `log_date` int(10) not null COMMENT '记录日期Ymd',
   `create_time` int(10) not null comment '玩家注册时间',
   `pht_1` smallint(4) not null COMMENT '第1高图鉴等级',
   `pht_2` smallint(4) not null COMMENT '第2高图鉴等级',
   `pht_3` smallint(4) not null COMMENT '第3高图鉴等级',
   `pht_4` smallint(4) not null COMMENT '第4高图鉴等级',
   `pht_5` smallint(4) not null COMMENT '第5高图鉴等级',
   `pht_6` smallint(4) not null COMMENT '第6高图鉴等级',
   `pht_7` smallint(4) not null COMMENT '第7高图鉴等级',
   PRIMARY KEY (`id`),
   UNIQUE `uk_account_date` (`accountid`,`userid`,`log_date`)
 ) ENGINE=InnoDB COMMENT '图鉴等级';

DROP TABLE IF EXISTS u_level_difficulty;
-- 总次数= 失败的次数 + 成功的次数
CREATE TABLE u_level_difficulty (
  `id` bigint(10) not null AUTO_INCREMENT,
  `appid` int(10) not null,
  `serverid` int(10) not null DEFAULT 0,
  `channel` int(10) not null DEFAULT 0,
  `accountid` int(10) not null,
  `userid` int(10) not null,
  `lev`  SMALLINT(4) not null COMMENT '玩家等级',
  `viplev` SMALLINT(4) not null COMMENT 'vip等级',
  `created_at` int(10) not null COMMENT '服务器记录时间',
  `client_time` int(10) not null COMMENT '接口请求时间',
  `log_date` int(10) not null COMMENT '记录日期Ymd',
  `create_time` int(10) not null comment '玩家注册时间',
  `level_id` int(10) not null COMMENT  '关卡ID',
  `copy_type` int(10) not null COMMENT '副本类型',
  `is_first_pass` tinyint(1) not null COMMENT '是否首次通过0否1是',
  `success_times` SMALLINT(4) NOT NULL COMMENT '成功次数,累加',
  `failure_times` SMALLINT(4) NOT NULL COMMENT '失败次数,累加',
  `total_times` SMALLINT(4) NOT NULL COMMENT '总次数,累加',
  `star` tinyint(1) not null COMMENT '获得星级数',/*记录第一次的星级，后面就不管了*/
  `fighting` int(10) not null COMMENT '战斗力',
  `total_lev` SMALLINT(4) not null COMMENT '总的等级',/*记录打这个副本的总等级 ，到时候要平均等级的话就是总等级除以总次数*/
  `total_fighting` int(10) not null COMMENT '总的战斗力',/*记录打这个副本的战斗力 ，到时候要平均战斗力的话就是总战斗力除以总次数*/
  `avg_fighting` int(10) not null COMMENT '平均战斗力',/*记录打这个副本的战斗力 ，到时候要平均战斗力的话就是总战斗力除以总次数*/
  `avg_lev` int(10) not null COMMENT '平均等级',/*记录打这个副本的等级 ，到时候要平均等级的话就是总等级除以总次数*/
  `max_star` tinyint(1) not null COMMENT '最高星级数',/*根据提交过来的星级，跟旧的对比，比旧的记录大就更新*/
  `max_star_times` SMALLINT(4) not null COMMENT '挑战至三星的次数',/*max_star等于3之前，要累加这个字段的次数*/
  PRIMARY KEY (`id`),
  key `idx_log_date` (`log_date`),
  key `idx_level_id` (`level_id`),
  UNIQUE `uk_account_date` (`accountid`,`userid`,`log_date`,`level_id`)
) ENGINE=InnoDB COMMENT '关卡难易程度统计';
# PLAYER BASIC INFO
# DROP TABLE IF EXISTS
/*
  `id` bigint(10) not null AUTO_INCREMENT,
  `appid` int(10) not null,
  `serverid` int(10) not null DEFAULT 0,
  `channel` int(10) not null DEFAULT 0,
  `accountid` int(10) not null,
  `userid` int(10) not null,
  `lev`  SMALLINT(4) not null COMMENT '玩家等级',
  `viplev` SMALLINT(4) not null COMMENT 'vip等级',
  `created_at` int(10) not null COMMENT '服务器记录时间',
  `client_time` int(10) not null COMMENT '接口请求时间',
  `log_date` int(10) not null COMMENT '记录日期Ymd',
  `create_time` int(10) not null comment '玩家注册时间',
*/
