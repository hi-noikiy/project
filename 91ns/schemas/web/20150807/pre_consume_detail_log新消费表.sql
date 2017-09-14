CREATE TABLE `pre_consume_detail_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uid` int(11) DEFAULT '0' COMMENT '消费者ID',
  `nickName` varchar(50) DEFAULT NULL COMMENT '赠送者昵称',
  `receiveUid` int(11) DEFAULT '0' COMMENT '接受者ID',
  `familyId` int(11) DEFAULT '0' COMMENT '家族ID',
  `type` int(11) DEFAULT '0' COMMENT '类型',
  `itemId` int(11) DEFAULT '0' COMMENT '消费项目ID',
  `count` int(11) DEFAULT '0' COMMENT '消费数量',
  `amount` decimal(32,3) DEFAULT '0.000' COMMENT '消费消费者金额',
  `income` decimal(32,3) DEFAULT '0.000' COMMENT '接受者收益金额',
  `remark` varchar(32) DEFAULT NULL COMMENT '备用信息',
  `createTime` int(11) DEFAULT '0' COMMENT '时间',
  `isTuo` tinyint(3) DEFAULT '0' COMMENT '是否托账号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消费记录表';
