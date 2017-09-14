ALTER TABLE `pre_users`
ADD COLUMN `canSetUserName`  tinyint(1) NULL DEFAULT 0 COMMENT '是否可修改账号' AFTER `userName`,
ADD COLUMN `password`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '密码' AFTER `canSetUserName`,
ADD COLUMN `canSetPassword`  tinyint(1) NULL DEFAULT 0 COMMENT '是否可设置密码' AFTER `password`,
ADD COLUMN `openId`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '第三方登录的openId' AFTER `userType`;

