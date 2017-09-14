CREATE TABLE `pre_user_robot` (
  `uid` int(11) unsigned NOT NULL,
  `platform` varchar(20),
  `nickName` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `richerLevel` tinyint(3),
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='机器人用户数据';