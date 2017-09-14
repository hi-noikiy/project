SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS  `inv_banner_config`;
CREATE TABLE `inv_banner_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `btype` tinyint(1) DEFAULT '0',
  `bannerurl` varchar(255) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `backgroundcolor` varchar(20) DEFAULT NULL,
  `extracontent` varchar(255) DEFAULT NULL,
  `description` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `type` (`btype`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

insert into `inv_banner_config`(`id`,`btype`,`bannerurl`,`time`,`backgroundcolor`,`extracontent`,`description`,`status`) values
('1','0','http://cdn.91ns.com/invupload/banner/accounts/1439535131.png','0','#eebe3e','activities/charge','fads','1'),
('2','0','http://cdn.91ns.com/invupload/banner/accounts/1439534587.png','0','#03093d','activities/love','fasd','1'),
('3','1','http://cdn.91ns.com/invupload/mobile/qixi_shanping.png','3',null,'http://m.91ns.com/activities/love',null,'1');
SET FOREIGN_KEY_CHECKS = 1;

