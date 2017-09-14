ALTER TABLE `pre_task`
ADD COLUMN `showStatus`  tinyint(1) NULL DEFAULT 1 COMMENT '是否显示' AFTER `sourceReward`;