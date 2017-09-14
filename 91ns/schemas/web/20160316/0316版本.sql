#骰宝
ALTER TABLE `pre_game_deduct_detail_log`
ADD COLUMN `remark`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '骰宝游戏主播提成' COMMENT '说明' AFTER `createTime`;

#座驾新增字段
ALTER TABLE `pre_car_configs`
ADD COLUMN `appSpecial`  tinyint(3) NULL DEFAULT 0 COMMENT 'APP高级座驾进场显示' AFTER `positionY2`;

#新增新的座驾
insert into pre_car_configs (`id`,`typeId`,`name`,`description`,`price`,`orderType`,`status`,`configName`,`hasBigCar`,`positionX1`,`positionY1`,`sort`,`positionX2`,`positionY2`,`appSpecial`) values 
	(65,1,'魅影小丑','我很丑，但我很温柔。',99900,0,0,'myxc',0,0,0,365,0,0,1),
	(66,1,'便便车','夜香，收夜香咯~~~',22200,0,0,'bbc',0,0,0,295,0,0,0);

#设置app高级座驾
UPDATE pre_car_configs SET appSpecial = 1 WHERE id IN (15,18,25,39,40,41,42,43,45,46,47,48,53,54,55,56,57,58,59,60,61,62,63,64,65);

#推荐码拒绝记录表
CREATE TABLE `pre_rec_refuse_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT 'uid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;