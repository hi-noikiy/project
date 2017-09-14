CREATE TABLE `inv_inform` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '举报用户ID',
  `targetId` int(11) DEFAULT '0' COMMENT '主播ID',
  `type` varchar(20) DEFAULT NULL COMMENT '举报类型',
  `content` varchar(255) DEFAULT NULL COMMENT '举报内容描述',
  `addTime` int(11) DEFAULT NULL COMMENT '添加时间',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态（是否处理）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='举报信息表';