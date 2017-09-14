#模块名称修改
UPDATE inv_module SET moduleName = '活动发放' WHERE id = 62;

#模块添加电影众筹
INSERT INTO inv_module (`parentId`,`moduleName`,`moduleAction`,`moduleSort`,`moduleType`,`createTime`) VALUES (62,'电影众筹','movie',3,1,1458547367);


#添加电影礼物
INSERT INTO pre_gift_configs(id,typeId,name,coin,cash,recvCoin,discount,freeCount,littleFlag,orderType,createTime,configName,description,littleSwf) 
VALUES(88,6,'电影票',0,10,5,0,0,1,6,1458835200,'dyp','陪心爱的主播看场电影吧',0);

#电影众筹
CREATE TABLE `pre_activity_round` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `times` int(11) DEFAULT '0' COMMENT '期数',
  `createTime` int(11) DEFAULT '0',
  `startTime` int(11) DEFAULT '0',
  `type` tinyint(3) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `pre_activity_anchors` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT 'uid',
  `type` tinyint(3) DEFAULT '1',
  `createTime` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `pre_activity_result_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `times` int(11) DEFAULT '0' COMMENT '期数',
  `startTime` int(11) DEFAULT '0' COMMENT '本期开始时间',
  `endTime` int(11) DEFAULT '0' COMMENT '本期结束时间',
  `rankInfo` longtext COMMENT '排行版信息',
  `createTime` int(11) DEFAULT '0' COMMENT '创建时间',
  `type` tinyint(3) DEFAULT '1' COMMENT '类型1-众筹电影',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='电影众筹每期数据记录';