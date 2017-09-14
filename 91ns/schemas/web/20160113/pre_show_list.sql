CREATE TABLE `pre_show_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '主播id',
  `showName` varchar(32) DEFAULT NULL COMMENT '节目名称',
  `showPrice` int(11) DEFAULT '0' COMMENT '节目价格',
  `showType` tinyint(3) DEFAULT '1' COMMENT '备用字段：1-歌曲2-舞蹈。。',
  `createTime` int(11) DEFAULT '0' COMMENT '添加时间',
  `updateTime` int(11) DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态0-正常1-删除',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='节目';