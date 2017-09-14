CREATE TABLE `pre_week_star_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `giftId` int(11) DEFAULT '0' COMMENT '礼物ID',
  `thisweekInfo` text COMMENT '本周排行',
  `lastweekInfo` text COMMENT '上周排行',
  `lastTime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='礼物周星记录表';