ALTER TABLE `pre_activity_income_log`
ADD COLUMN `proportion`  int(11) NULL DEFAULT 5 COMMENT '抽成比例' AFTER `createTime`;