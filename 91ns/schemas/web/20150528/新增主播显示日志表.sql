CREATE TABLE `pre_show_room_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roomId` int(11) DEFAULT NULL,
  `startTime` int(11) DEFAULT NULL COMMENT '隐藏房间时间',
  `endTime` int(11) unsigned DEFAULT NULL COMMENT '解除隐藏房间时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='直播房间显示日志表';

