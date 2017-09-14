CREATE TABLE `pre_videos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `streamName` varchar(64) DEFAULT NULL COMMENT '流名',
  `isUsing` tinyint(3) DEFAULT '0' COMMENT '是否使用中',
  `createTime` int(11) DEFAULT '0' COMMENT '添加时间',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态0-正常1-删除',
  `publicTime` int(11) DEFAULT '0' COMMENT '开播时间',
  `videoPic` varchar(64) DEFAULT NULL COMMENT '视频封面名称',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;