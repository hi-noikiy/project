CREATE TABLE `pre_announcement_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) DEFAULT '0' COMMENT '轮播开启状态0-否1-是',
  `startTime` int(11) DEFAULT '0' COMMENT '开始轮播时间',
  `runHours` int(11) DEFAULT '0' COMMENT '结束轮播时间',
  `seconds` int(11) DEFAULT '300' COMMENT '秒数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='轮播池轮播记录表';
