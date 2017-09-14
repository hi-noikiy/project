CREATE TABLE `pre_user_images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT 'uid',
  `imgUrl` varchar(128) DEFAULT NULL,
  `imgWidth` int(11) DEFAULT '0' COMMENT '图片宽度',
  `imgHeight` int(11) DEFAULT '0' COMMENT '图片长度',
  `type` tinyint(3) DEFAULT '0' COMMENT '个人相册',
  `createTime` int(11) DEFAULT '0' COMMENT '上传时间',
  `dynamicId` int(11) DEFAULT '0' COMMENT '动态关联ID',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态0-正常1-删除',
  PRIMARY KEY (`id`),
  KEY `dynamicId` (`dynamicId`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;