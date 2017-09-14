ALTER TABLE `pre_user_give_log`
ADD COLUMN `consumeLogId`  int(11) NULL DEFAULT 0 COMMENT '关联消费日志表id' AFTER `createTime`;

