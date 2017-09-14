CREATE TABLE `pre_bet_points_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户uid',
  `times` int(11) DEFAULT '0' COMMENT '夺宝期数',
  `nums` int(11) DEFAULT '0' COMMENT '投注注数',
  `type` tinyint(3) DEFAULT '0' COMMENT '夺宝类型',
  `createTime` int(11) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='积分下注日志表';