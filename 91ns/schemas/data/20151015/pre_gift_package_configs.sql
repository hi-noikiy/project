SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS  `pre_gift_package_configs`;
CREATE TABLE `pre_gift_package_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT '' COMMENT '礼包名称',
  `desc` varchar(255) DEFAULT '' COMMENT '礼包描述',
  `items` varchar(1000) DEFAULT '' COMMENT '【物品类型，物品id，物品数量,有效期】',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COMMENT='礼包配置表';

insert into `pre_gift_package_configs`(`id`,`name`,`desc`,`items`) values
('1','礼包1','','[{"type":4,"id":1,"num":1,"validity":0}]'),
('2','礼包2','','[{"type":4,"id":2,"num":1,"validity":0}]'),
('3','礼包3','','[{"type":2,"id":16,"num":1,"validity":2332800}]'),
('4','礼包4','','[{"type":3,"id":9,"num":5,"validity":0}]'),
('5','礼包5','','[{"type":3,"id":10,"num":10,"validity":0}]'),
('6','礼包6','','[{"type":3,"id":11,"num":20,"validity":0}]'),
('7','礼包7','','[{"type":3,"id":15,"num":10,"validity":0}]'),
('8','礼包8','','[{"type":3,"id":18,"num":1,"validity":0}]'),
('9','礼包9','','[{"type":4,"id":3,"num":1,"validity":0}]'),
('10','超值大礼包','','[{"type":0,"coin":1000},{"type":3,"id":10,"num":10,"validity":0},{"type":2,"id":29,"num":1,"validity":1209600}]'),
('11','土豪大礼包','','[{"type":0,"coin":10000},{"type":1,"id":1,"validity":604800},{"type":2,"id":28,"num":1,"validity":2419200},{"type":3,"id":9,"num":100,"validity":0}]'),
('12','首充礼包1','','[{"type":0,"coin":1000},{"type":1,"validity":604800},{"type":2,"id":20,"validity":864000}]'),
('13','首充礼包2','','[{"type":0,"coin":10000},{"type":1,"validity":2592000},{"type":2,"id":19,"validity":2592000}]'),
('14','联通礼包','','[{"type":0,"coin":10000,"img":"/public/web/images/gift/ld48.png"},{"type":2,"id":33,"num":1,"validity":2419200,"img":"/public/web/images/gift/Jaguar.png"},{"type":3,"id":9,"num":100,"validity":0,"img":"/public/web/images/gift/xxxy.png"},{"type":3,"id":10,"num":100,"validity":0,"img":"/public/web/images/gift/bbt.png"},{"type":3,"id":11,"num":100,"validity":0,"img":"/public/web/images/gift/dg.png"},{"type":4,"id":1,"num":1,"validity":0,"img":"/public/web/images/gift/ylb48.png"},{"type":4,"id":2,"num":1,"validity":0,"img":"/public/web/images/gift/jlb48.png"},{"type":4,"id":4,"num":1,"validity":0,"img":"/public/web/images/gift/lt48.png"}]'),
('15','棋牌礼包','','[{"type":0,"coin":10000,"img":"/public/web/images/gift/ld48.png"},{"type":2,"id":32,"num":1,"validity":2419200,"img":"/public/web/images/gift/Maserati.png"},{"type":3,"id":9,"num":100,"validity":0,"img":"/public/web/images/gift/xxxy.png"},{"type":3,"id":10,"num":100,"validity":0,"img":"/public/web/images/gift/bbt.png"},{"type":3,"id":11,"num":100,"validity":0,"img":"/public/web/images/gift/dg.png"},{"type":4,"id":1,"num":1,"validity":0,"img":"/public/web/images/gift/ylb48.png"},{"type":4,"id":2,"num":1,"validity":0,"img":"/public/web/images/gift/jlb48.png"},{"type":4,"id":5,"num":1,"validity":0,"img":"/public/web/images/gift/7pm48.png"}]'),
('16','棋牌新手引导登录','新手引导','[{"type":0,"coin":50}]'),
('17','渠道新手引导修改昵称','新手引导','[{"type":0,"coin":10000}]'),
('21','棋牌送礼','新手引导','[{"type":3,"id":11,"num":100,"validity":0}]'),
('18','渠道新手引导与主播聊天','新手引导','[{"type":3,"id":9,"num":100,"validity":0},{"type":4,"id":1,"num":1,"validity":0}]'),
('19','渠道新手引导关注主播','新手引导','[{"type":3,"id":10,"num":100,"validity":0},{"type":4,"id":2,"num":1,"validity":0}]'),
('20','联通送礼','新手引导','[{"type":3,"id":11,"num":100,"validity":0},{"type":4,"id":4,"num":1,"validity":0}]'),
('22','联通完成新手引导','新手引导','[{"type":2,"id":33,"num":1,"validity":2419200}]'),
('23','棋牌完成新手引导','新手引导','[{"type":2,"id":32,"num":1,"validity":1296000}]'),
('24','普通用户完成新手引导','新手引导','[{"type":2,"id":28,"num":1,"validity":1296000}]'),
('25','普通用户新手引导登录','新手引导','[{"type":0,"coin":50}]'),
('26','普通用户新手引导修改昵称','新手引导','[{"type":0,"coin":50}]'),
('27','普通用户新手引导与主播聊天','新手引导','[{"type":0,"coin":50}]'),
('28','普通用户新手引导关注主播','新手引导','[{"type":0,"coin":50}]'),
('29','普通用户新手引导送礼','新手引导','[{"type":0,"coin":50}]'),
('30','迷你青铜礼包','累计充值活动1','[{"type":0,"coin":50},{"type":3,"id":10,"num":10,"validity":0},{"type":2,"id":29,"num":1,"validity":604800}]'),
('31','超值青铜礼包','累计充值活动2','[{"type":0,"coin":1000},{"type":1,"id":1,"validity":604800},{"type":2,"id":28,"num":1,"validity":1296000},{"type":3,"id":9,"num":100,"validity":0}]'),
('32','土豪白银礼包','累计充值活动3','[{"type":0,"coin":2000},{"type":1,"id":1,"validity":1296000},{"type":2,"id":35,"num":1,"validity":2592000},{"type":3,"id":11,"num":200,"validity":0},{"type":4,"id":1,"num":1,"validity":0}]'),
('33','至尊白银礼包','累计充值活动4','[{"type":0,"coin":3000},{"type":1,"id":2,"validity":604800},{"type":2,"id":38,"num":1,"validity":1296000},{"type":3,"id":31,"num":3,"validity":0},{"type":4,"id":2,"num":1,"validity":0}]'),
('34','史诗黄金礼包','累计充值活动5','[{"type":0,"coin":5000},{"type":1,"id":2,"validity":1296000},{"type":2,"id":39,"num":1,"validity":2592000},{"type":3,"id":32,"num":3,"validity":0},{"type":4,"id":1,"num":2,"validity":0},{"type":4,"id":2,"num":1,"validity":0},{"type":4,"id":6,"num":1,"validity":0}]'),
('35','传说黄金礼包','累计充值活动6','[{"type":0,"coin":8000},{"type":1,"id":2,"validity":2592000},{"type":2,"id":40,"num":1,"validity":2592000},{"type":3,"id":24,"num":3,"validity":0},{"type":4,"id":1,"num":2,"validity":0},{"type":4,"id":2,"num":2,"validity":0},{"type":4,"id":7,"num":1,"validity":0}]'),
('36','棋牌徽章','棋牌账号注册','[{"type":4,"id":5,"num":1,"validity":0}]'),
('37','周星活动奖励','周星活动奖励','[{"type":2,"id":41,"num":1,"validity":604800},{"type":4,"id":8,"num":1,"validity":604800}]'),
('38','新手礼包','推荐活动的新手礼包','[{"type":0,"cash":888},{"type":3,"id":10,"num":88,"validity":0},{"type":2,"id":30,"num":1,"validity":2592000},{"type":4,"id":9,"num":1,"validity":2592000}]'),
('39','兑换礼包','兑换礼包','[{"type":2,"id":20,"num":1,"validity":2592000},{"type":3,"id":9,"num":50,"validity":0},{"type":3,"id":10,"num":50,"validity":0},{"type":3,"id":11,"num":30,"validity":0},{"type":3,"id":15,"num":20,"validity":0},{"type":3,"id":18,"num":10,"validity":0}]'),
('40','独角兽座驾礼包','','[{"type":2,"id":41,"num":1,"validity":2592000}]');
SET FOREIGN_KEY_CHECKS = 1;

