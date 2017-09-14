ALTER TABLE `pre_sign_anchor`
ADD COLUMN `email`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '邮箱' AFTER `money`;