ALTER TABLE `pre_type_config`
ADD COLUMN `showStatus`  tinyint(3) NULL DEFAULT 0 COMMENT '是否显示' AFTER `roomAnimate`,
ADD COLUMN `sellStatus`  tinyint(3) NULL DEFAULT 0 COMMENT '是否非卖品' AFTER `showStatus`;