ALTER TABLE `pre_bet_points_log`
ADD COLUMN `platform`  tinyint(3) NULL DEFAULT 0 COMMENT '设备信息' AFTER `createTime`;