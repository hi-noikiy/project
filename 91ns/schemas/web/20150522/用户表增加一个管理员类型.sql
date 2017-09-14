ALTER TABLE `pre_users`
ADD COLUMN `manageType`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '管理员类型 1：超级管理员' AFTER `internalType`;

