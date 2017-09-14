CREATE TABLE `inv_suggestions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '建议者ID',
  `type` tinyint(3) DEFAULT NULL COMMENT '反馈类型',
  `content` varchar(255) DEFAULT NULL COMMENT '返回内容',
  `pic1` varchar(100) DEFAULT NULL COMMENT '截图地址1',
  `pic2` varchar(100) DEFAULT NULL COMMENT '截图地址2',
  `pic3` varchar(100) DEFAULT NULL COMMENT '截图地址3',
  `log` varchar(100) DEFAULT NULL COMMENT '日志文件地址',
  `mobile` char(11) DEFAULT NULL COMMENT '手机号',
  `email` varchar(30) DEFAULT NULL COMMENT '邮箱',
  `qq` varchar(20) DEFAULT NULL COMMENT 'QQ号',
  `addTime` int(11) DEFAULT NULL COMMENT '添加时间',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='意见反馈表';