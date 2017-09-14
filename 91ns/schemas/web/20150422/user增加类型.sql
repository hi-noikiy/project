ALTER TABLE `pre_users`
ADD COLUMN `internalType`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-普通用户,1-托用户' AFTER `userType`;
