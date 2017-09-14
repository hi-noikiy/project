ALTER TABLE `pre_type_config`
MODIFY COLUMN `showStatus`  tinyint(3) NULL DEFAULT 1 COMMENT '是否显示' AFTER `roomAnimate`,
MODIFY COLUMN `sellStatus`  tinyint(3) NULL DEFAULT 1 COMMENT '是否非卖品' AFTER `showStatus`;