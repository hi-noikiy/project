ALTER TABLE `pre_announcement_list`
ADD COLUMN `runNum`  int(11) NULL DEFAULT 1 COMMENT '轮播次数' AFTER `addtime`;