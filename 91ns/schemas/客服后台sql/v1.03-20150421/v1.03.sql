-- ----------------------------
-- Table structure for `inv_accounts_log`
-- ----------------------------
DROP TABLE IF EXISTS `inv_accounts_log`;
CREATE TABLE `inv_accounts_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accountId` int(11) DEFAULT '0' COMMENT '对应inv_accounts表的id',
  `uid` int(11) DEFAULT '0' COMMENT '主播id或家族id',
  `type` tinyint(1) DEFAULT '1' COMMENT '类型 1主播2家族',
  `cash` float(32,3) DEFAULT '0.000' COMMENT '结算聊币',
  `basicSalary` float(32,3) DEFAULT '0.000' COMMENT '底薪',
  `rmb` float(32,3) DEFAULT '0.000' COMMENT '结算人民币',
  `auditUser` varchar(32) CHARACTER SET latin1 DEFAULT '' COMMENT '结算操作者',
  `auditTime` int(11) DEFAULT '0' COMMENT '结算时间',
  `auditImg` varchar(255) DEFAULT '' COMMENT '结算上传图片',
  `status` tinyint(1) DEFAULT '0' COMMENT '结算状态 0申请中 1已结算',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;