ALTER TABLE `pre_apply_log`
ADD COLUMN `isRead`  tinyint(1) NULL DEFAULT 1 COMMENT '用户是否已读 0未读 1已读' AFTER `reason`;