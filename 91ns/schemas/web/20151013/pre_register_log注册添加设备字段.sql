ALTER TABLE `pre_register_log`
ADD COLUMN `platform`  tinyint(1) NULL DEFAULT 1 COMMENT '平台 1：pc 2:ios 3:android' AFTER `ip`;