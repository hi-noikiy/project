CREATE TABLE `pre_video_review` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT 'uid',
  `streamName` varchar(64) DEFAULT NULL COMMENT '流名',
  `createTime` int(11) DEFAULT '0' COMMENT '添加时间',
  `publicTime` int(11) DEFAULT '0' COMMENT '开播时间',
  `remark` varchar(32) DEFAULT NULL COMMENT '备用',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='视频回放';

