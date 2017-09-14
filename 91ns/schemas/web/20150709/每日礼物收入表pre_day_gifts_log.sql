CREATE TABLE `pre_day_gifts_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uid` int(11) DEFAULT '0' COMMENT '主播ID',
  `familyId` int(11) DEFAULT '0' COMMENT '家族ID',
  `creatorUid` int(11) DEFAULT '0' COMMENT '家族长ID',
  `allIncome` decimal(32,3) DEFAULT '0.000' COMMENT '当日总收益',
  `myIncome` decimal(32,3) DEFAULT '0.000' COMMENT '当日主播最终收益',
  `platRatio` tinyint(4) DEFAULT '100' COMMENT '平台分成比例',
  `divideIncome` decimal(32,3) DEFAULT '0.000' COMMENT '当日分成收益',
  `divideRatio` smallint(6) DEFAULT '20' COMMENT '家族提成比例',
  `createTime` int(11) DEFAULT '0' COMMENT '创建时间',
  `source` tinyint(3) DEFAULT '0' COMMENT '收益来源',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='礼物日结算流水表';