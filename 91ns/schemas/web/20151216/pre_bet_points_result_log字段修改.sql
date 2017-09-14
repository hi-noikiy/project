ALTER TABLE `pre_bet_points_result_log`
MODIFY COLUMN `type`  int(11) NULL DEFAULT 0 COMMENT '夺宝类型[夺宝商品id]' AFTER `times`,
ADD INDEX (`type`) USING BTREE ;


ALTER TABLE `pre_bet_points_result_log`
ADD INDEX (`times`) USING BTREE ;