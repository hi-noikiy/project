CREATE TABLE `pre_buy_show_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `buyUid` int(11) DEFAULT '0' COMMENT '用户uid',
  `uid` int(11) DEFAULT '0' COMMENT '主播uid',
  `showId` int(11) DEFAULT '0' COMMENT '节目id',
  `showName` varchar(32) DEFAULT '' COMMENT '节目名称',
  `showPrice` int(11) DEFAULT '0' COMMENT '节目价格',
  `showType` tinyint(3) DEFAULT '1' COMMENT '节目类型1-节目单2-自选节目',
  `buyMethod` tinyint(3) DEFAULT '1' COMMENT '购买方式1-聊币2-节目卡',
  `createTime` int(11) DEFAULT '0' COMMENT '添加时间',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态1-未处理2-已同意0-已拒绝',
  `isDelete` tinyint(3) DEFAULT '0' COMMENT '是否删除0-否1-删除',
  PRIMARY KEY (`id`),
  KEY `buyUid` (`buyUid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `showType` (`showType`) USING BTREE,
  KEY `buyMethod` (`buyMethod`) USING BTREE,
  KEY `createTime` (`createTime`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='点歌列表';