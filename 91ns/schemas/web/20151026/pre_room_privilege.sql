CREATE TABLE `pre_room_privilege` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `roomId` int(11) DEFAULT '0' COMMENT '房间号',
  `uid` int(11) DEFAULT '0' COMMENT 'uid',
  `useRole` tinyint(3) DEFAULT '0' COMMENT '是否使用角色限制',
  `isAnchor` tinyint(3) DEFAULT '0' COMMENT '是否限定主播进入',
  `isFamily` tinyint(3) DEFAULT '0' COMMENT '是否限定家族长进入',
  `isManage` tinyint(3) DEFAULT '0' COMMENT '是否限定管理员进入',
  `minRicherRank` tinyint(3) DEFAULT '0' COMMENT '富豪最低等级',
  `usePwd` tinyint(3) DEFAULT '0' COMMENT '是否开启密码限制',
  `roomPwd` varchar(10) DEFAULT NULL COMMENT '直播间密码',
  PRIMARY KEY (`id`),
  KEY `roomId` (`roomId`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;