ALTER TABLE `pre_users`
ADD COLUMN `isChatRecord`  tinyint(1) NULL DEFAULT 0 COMMENT '是否记录聊天信息' AFTER `internalType`;

