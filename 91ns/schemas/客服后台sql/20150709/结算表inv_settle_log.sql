CREATE TABLE `inv_settle_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `changeId` int(11) DEFAULT '0' COMMENT '对应pre_change_log表的id',
  `uid` int(11) DEFAULT '0' COMMENT '主播id或家族id',
  `rmb` decimal(32,3) DEFAULT '0.000' COMMENT '结算人民币',
  `auditUser` varchar(32) CHARACTER SET latin1 DEFAULT '' COMMENT '结算操作者',
  `createTime` int(11) DEFAULT '0' COMMENT '创建时间',
  `auditTime` int(11) DEFAULT '0' COMMENT '结算时间',
  `auditImg` varchar(255) DEFAULT '' COMMENT '结算上传图片',
  `status` tinyint(1) DEFAULT '0' COMMENT '结算状态 0预扣费 1已结算',
  `type` tinyint(3) DEFAULT '0' COMMENT '类型',
  `remark` varchar(2000) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='结算表';