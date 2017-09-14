CREATE TABLE `pre_anchor_jump` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '主播ID',
  `type` tinyint(3) DEFAULT '0' COMMENT '优先级',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;