ALTER TABLE `pre_rooms`
ADD COLUMN `publishRoute`  tinyint(3) NULL DEFAULT 0 COMMENT '发布' AFTER `robotNum`,
ADD COLUMN `useAccelarate`  tinyint(3) NULL DEFAULT 0 COMMENT '是否加速' AFTER `publishRoute`;