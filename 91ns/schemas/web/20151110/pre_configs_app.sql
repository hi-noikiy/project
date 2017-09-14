CREATE TABLE `pre_configs_app` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(32) DEFAULT NULL COMMENT '键',
  `value` varchar(64) DEFAULT NULL COMMENT '值',
  `remark` varchar(64) DEFAULT NULL COMMENT '备用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
