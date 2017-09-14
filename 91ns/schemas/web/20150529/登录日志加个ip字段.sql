ALTER TABLE `pre_login_log`
ADD COLUMN `ip`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'ip地址' AFTER `createTime`;

