CREATE TABLE `pre_week_star_info_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `giftId` int(11) DEFAULT '0' COMMENT '礼物ID',
  `anchorId` int(11) DEFAULT '0' COMMENT '主播ID',
  `richerId` int(11) DEFAULT '0' COMMENT '富豪ID',
  `createTime` int(11) DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='周星获得者记录表';