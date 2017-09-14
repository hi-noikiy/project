ALTER TABLE `pre_bet_points_log`
MODIFY COLUMN `type`  int(11) NULL DEFAULT 0 COMMENT '夺宝类型[夺宝商品id]' AFTER `nums`,
ADD COLUMN `kind`  tinyint(3) NULL DEFAULT 1 COMMENT '投注方式1-积分2-聊币' AFTER `platform`,
ADD INDEX (`type`) USING BTREE ;

ALTER TABLE `pre_bet_points_log`
ADD INDEX (`times`) USING BTREE ,
ADD INDEX (`platform`) USING BTREE ,
ADD INDEX (`kind`) USING BTREE ;
