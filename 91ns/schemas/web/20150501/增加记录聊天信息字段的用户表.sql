ALTER TABLE `pre_users`
ADD COLUMN `isChatRecord`  tinyint(1) NULL DEFAULT 0 COMMENT '�Ƿ��¼������Ϣ' AFTER `internalType`;

