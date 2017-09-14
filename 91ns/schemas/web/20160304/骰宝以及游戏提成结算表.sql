CREATE TABLE `pre_dice` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '游戏id',
  `roomId` int(11) NOT NULL DEFAULT '0' COMMENT '房间号',
  `round` int(11) NOT NULL DEFAULT '1' COMMENT '游戏场次',
  PRIMARY KEY (`id`),
  UNIQUE KEY `roomId` (`roomId`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='骰子游戏表';

CREATE TABLE `pre_dice_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roomId` int(11) NOT NULL COMMENT '房间id',
  `round` int(11) NOT NULL DEFAULT '0' COMMENT '游戏场次',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `declarer` int(11) NOT NULL DEFAULT '0' COMMENT '庄家uid',
  `declareTime` int(11) NOT NULL DEFAULT '0' COMMENT '庄家上庄的时间',
  `cash` int(11) NOT NULL DEFAULT '0' COMMENT '庄家携带的聊币',
  `startTime` int(11) NOT NULL DEFAULT '0' COMMENT '庄家开启押注时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '游戏状态：0未开始 1进行中 2已结束',
  `result` int(11) NOT NULL DEFAULT '0' COMMENT '游戏结果',
  `resultTime` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `roomId,round` (`roomId`,`round`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='骰子游戏详细表';

CREATE TABLE `pre_dice_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameId` int(11) NOT NULL DEFAULT '0' COMMENT '游戏id，关联pre_dice_detail表的id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '投注类型:大、小、全围...',
  `cash` int(11) NOT NULL DEFAULT '0' COMMENT '投注聊币数',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '记录创建时间',
  `result` int(11) NOT NULL DEFAULT '0' COMMENT '本轮押注的输赢结果，单位:聊币',
  `resultTime` int(11) NOT NULL DEFAULT '0' COMMENT '结算时间',
  `fax` int(11) NOT NULL DEFAULT '0' COMMENT '税收',
  PRIMARY KEY (`id`),
  UNIQUE KEY `gameId,uid,type` (`gameId`,`uid`,`type`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='骰子游戏押注记录';

CREATE TABLE `pre_dice_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameId` int(11) NOT NULL DEFAULT '0' COMMENT '游戏id，关联pre_dice_detail表的id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户uid',
  `isDeclarer` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否庄家',
  `createTime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  `stakeCash` int(11) NOT NULL DEFAULT '0' COMMENT '押注聊币',
  `resultCash` int(11) NOT NULL DEFAULT '0' COMMENT '输赢的聊币,前端展示',
  `finalCash` int(11) NOT NULL DEFAULT '0' COMMENT '扣除税收后的聊币数',
  `fax` int(11) NOT NULL DEFAULT '0' COMMENT '税收',
  `anchorUid` int(11) NOT NULL DEFAULT '0' COMMENT '主播uid',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `pre_game_deduct_day_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户uid',
  `cash` decimal(32,3) DEFAULT '0.000' COMMENT '单日提成聊币',
  `type` tinyint(3) DEFAULT '1' COMMENT '类型1-骰宝',
  `remark` varchar(32) DEFAULT NULL COMMENT '说明',
  `createTime` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='每日游戏提成收入表';

CREATE TABLE `pre_game_deduct_detail_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `percentage` decimal(32,3) DEFAULT '0.000' COMMENT '提成聊币',
  `deductTime` int(11) DEFAULT '0' COMMENT '游戏提成时间戳',
  `gameType` tinyint(3) DEFAULT '1' COMMENT '游戏类型1-骰宝',
  `dealerUid` int(11) DEFAULT '0' COMMENT '庄家uid',
  `anchorUid` int(11) DEFAULT '0' COMMENT '主播uid',
  `createTime` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='游戏提成明细表';

