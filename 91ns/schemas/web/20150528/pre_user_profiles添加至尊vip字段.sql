ALTER TABLE `pre_user_profiles`
ADD COLUMN `level6`  tinyint(3) NULL DEFAULT 0 COMMENT '����vip' AFTER `level5`,
ADD COLUMN `vipExpireTime2`  int(11) NULL DEFAULT 0 COMMENT '����vip����ʱ��' AFTER `vipExpireTime`;