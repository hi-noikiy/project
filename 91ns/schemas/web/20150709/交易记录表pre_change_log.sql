CREATE TABLE `pre_change_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uid` int(11) DEFAULT '0' COMMENT '主播ID',
  `orderNum` varchar(32) DEFAULT NULL COMMENT '单号',
  `money` decimal(32,3) DEFAULT '0.000' COMMENT '金额',
  `createTime` int(11) DEFAULT '0' COMMENT '创建时间',
  `type` tinyint(3) DEFAULT '1' COMMENT '类型',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='交易明细表';