ALTER TABLE `pre_apply_log`
ADD COLUMN `reason`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '拒绝理由' AFTER `auditTime`;