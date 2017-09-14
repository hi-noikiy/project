CREATE TABLE `pre_reward_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '用户uid',
  `rewardId` int(11) DEFAULT '1' COMMENT '奖励id',
  `num` int(11) DEFAULT '1' COMMENT '次数',
  `addTime` int(11) DEFAULT NULL COMMENT '新增时间',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态(0-未领取1-已领取)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;