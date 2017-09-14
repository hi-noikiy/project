ALTER TABLE `pre_users`
ADD COLUMN `key`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '密码加密用的key' AFTER `canSetUserName`;