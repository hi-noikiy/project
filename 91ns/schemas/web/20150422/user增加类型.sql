ALTER TABLE `pre_users`
ADD COLUMN `internalType`  tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-��ͨ�û�,1-���û�' AFTER `userType`;
