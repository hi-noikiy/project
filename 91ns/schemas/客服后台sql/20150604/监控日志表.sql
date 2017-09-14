CREATE TABLE `inv_monitor_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `streamname` varchar(30) DEFAULT NULL COMMENT '流名',
  `inbandwidth` int(11) DEFAULT NULL COMMENT '上行速度',
  `lfr` float(10,2) DEFAULT NULL COMMENT '丢帧率',
  `fps` int(11) DEFAULT NULL COMMENT '帧率',
  `deployaddress` varchar(15) DEFAULT NULL COMMENT '发布点IP',
  `inaddress` varchar(15) DEFAULT NULL COMMENT '主播地址',
  `bandwidth` int(11) DEFAULT NULL,
  `hists` int(11) DEFAULT NULL COMMENT '下行连接数',
  `logtime` int(11) DEFAULT NULL COMMENT '日志记录时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COMMENT='监控数据日志表';