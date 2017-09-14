SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS  `pre_event_config`;
CREATE TABLE `pre_event_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `etype` tinyint(1) DEFAULT '0',
  `bannerurl` varchar(255) DEFAULT NULL,
  `extracontent` varchar(255) DEFAULT NULL,
  `description` text,
  `eventstarttime` int(11) DEFAULT NULL,
  `eventendtime` int(11) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

insert into `pre_event_config`(`id`,`title`,`etype`,`bannerurl`,`extracontent`,`description`,`eventstarttime`,`eventendtime`,`addtime`,`status`) values
('1','����','0','http://cdn.91ns.com/invupload/event/accounts/1439534250.png','activities/share','����ֱ���ճ��Ͳ�ͣ','1435921200','1999999999','1439446239','1'),
('2','��ֵ�ͺ���','0','http://cdn.91ns.com/invupload/event/accounts/1439534300.jpg','activities/charge','��ֵ�ͺ���','1435921200','1999999999','1439446239','1'),
('3','���߹ۿ��Ķ�����ˢ','0','http://cdn.91ns.com/invupload/event/accounts/1439534355.png','activities/online','���߹ۿ��Ķ�����ˢ','1435921200','1999999999','1439446222','1'),
('4','����','0','http://cdn.91ns.com/invupload/event/accounts/1439543531.png','activities/star','����','1435921200','1999999999','1439446239','1'),
('5','��Ϧ���˽�','0','http://cdn.91ns.com/invupload/event/accounts/1439534430.jpg','activities/love','��Ϧ���˽�','1439740800','1440086400','1439654400','1'),
('6','��Ϧ���˽�','1','http://cdn.91ns.com/invupload/mobile/banner/qixibg.png','http://m.91ns.com/activities/love','��Ϧ���˽ڣ�app��','1439740800','1440086400','1439654400','1'),
('7','����','1','http://cdn.91ns.com/invupload/mobile/banner/zouxinbg.png','http://m.91ns.com/activities/star','���ǣ�app��','1435921200','1999999999','1439446239','1');
SET FOREIGN_KEY_CHECKS = 1;

