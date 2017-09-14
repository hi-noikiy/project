CREATE TABLE `pre_points_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT 'uid',
  `points` int(11) DEFAULT '0' COMMENT '获得积分',
  `type` tinyint(3) DEFAULT '0' COMMENT '积分来源类型',
  `createTime` int(11) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='积分记录表';