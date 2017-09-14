
ALTER TABLE `pre_register_log`
ADD COLUMN `ip`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ip' AFTER `createTime`;